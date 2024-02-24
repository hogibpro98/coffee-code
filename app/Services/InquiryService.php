<?php

namespace App\Services;

use App\Traits\ListTrait;
use App\Models\Link;
use App\Exceptions\PosException;
use App\Mail\MainMailable;
use App\Models\Inquiry;
use App\Models\InquiryComment;
use App\Models\InquiryCommentFile;
use App\Models\Member;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class InquiryService
{
    use ListTrait;

    public function index($params)
    {
        $this->query = Inquiry::with(
            [
                'member'=> function($query) {
                    $query->select('id', 'name_kanji' );
                }
            ]
        );
        if (!empty($params['member_name'])) {
            $getIds = $this->query->whereRelation('member', 'name_kanji', 'LIKE', '%'. $params['member_name'] . '%')
                        ->orwhereRelation('member', 'name_furigana', 'LIKE', '%'. $params['member_name'] . '%')->get();
            $listIds = [];
            foreach ($getIds as $item) {
                $listIds[] = $item['id'];
            }
            $this->query->whereIn('member_id', $listIds);
        }
        if (!empty($params['status'])) $this->query->whereIn('status', $params['status']);
        $this->params = $params;
        if (!empty($params['title'])) $this->addParams('title', true);
        if (!empty($params['content'])) $this->addParams('content', true);

        return $this->query();
    }

    public function show($id)
    {
        $model = Inquiry::with([
            'member'=> function($query) {
                $query->select('id', 'name_kanji');
            },
            'inquiryComments' => function($query) {
                $query->with([
                    'inquiryCommentFiles',
                    'member' => function($query) {
                        $query->select('id', 'name_kanji');
                    }
                ]);
            }
        ])->find($id);
        unset($model['member']['id']);
        if (!$model) {
        throw new PosException('08', '001', 404);
        }
        return $model;
    }

    public function updateRestart($id)
    {
        $model = Inquiry::find($id);
        if (!$model) {
            throw new PosException('08', '002', 404);
        }
        if ($model->status !== Inquiry::COMPLETE) {
            throw new PosException('08', '003', 400);
        }
        $model->fill(['status' => Inquiry::NO_ANSWER])->save();

        return $model;
    }

    public function updateStart($id)
    {
        $model = Inquiry::find($id);
        if (!$model) {
            throw new PosException('08', '004', 404);
        }
        if ($model->status !== Inquiry::NO_ANSWER) {
            throw new PosException('08', '005', 400);
        }
        $model->fill(['status' => Inquiry::ANSWERED])->save();

        return $model;
    }

    public function updateSupportInEmail($id)
    {
        $model = Inquiry::find($id);
        if (!$model) {
            throw new PosException('08', '006', 404);
        }
        $model->fill(['status' => Inquiry::SUPPORT_EMAL])->save();

        return $model;
    }

    public function updateClose($id)
    {
        $model = Inquiry::find($id);
        if (!$model) {
            throw new PosException('08', '007', 404);
        }
        if ($model->status !== Inquiry::SUPPORT_EMAL && $model->status !== Inquiry::ANSWERED) {
            throw new PosException('08', '008', 400);
        }
        $model->fill(['status' => Inquiry::COMPLETE])->save();

        return $model;
    }

    public function storeComment($params, $id)
    {
        return DB::transaction(function () use ($params, $id) {
            $model = Inquiry::find($id);
            if (!$model) {
                throw new PosException('08', '009', 404);
            }
            $model['user_id'] = auth()->user()->id;
            $model->save();
            $inquiryComment = $model->inquiryComments()->create([
                'member_id' => !empty($params['member_id']) ? $params['member_id'] : null,
                'user_id' => auth()->user()->id,
                'content' => !empty($params['content']) ? $params['content'] : null,
                'is_read' => !empty($params['is_read']) ? $params['is_read'] : 0,
            ]);
            if (!empty($params['inquiry_comment_files'])) {
                if (count($params['inquiry_comment_files']) >= 6) {
                    throw new PosException('08', '010', 422);
                }
                foreach ($params['inquiry_comment_files'] as $item) {
                    $file = $item;
                    $dataFile = [];
                    $dataFile['file_name'] = $file->getClientOriginalName();
                    $dataFile['mime_type'] = $file->getClientMimeType();
                    $dataFile['file_path'] = 'path';
                    $this->validatorFile($dataFile);
                    $inquiryCommentFile = $inquiryComment->inquiryCommentFiles()->create($dataFile);
                    $path = config('filesystems.disks.s3.bucket'). '/inquiry_comment_files/'. $inquiryCommentFile->id;
                    Storage::put($path, file_get_contents($file), 'public');
                    $dataFile['file_path'] = config('filesystems.disks.s3.url'). '/'. $path;
                    $inquiryCommentFile->fill($dataFile)->save();
                }
            }

            // 本会員にメール送信
            $member = Member::find($model->member_id);
            $mailTemplate = new MainMailable(10);
            $mailTemplate->setViewData([
                'name' => $member->name_kanji,
                'title' => $model->title,
                'content' => $model->content,
                'url' => config('app.member_site_url').'/inquiry',//意見一覧画面のURL
            ]);
            $mailTemplate->sendMail($member->email);

            return Inquiry::with(['inquiryComments.inquiryCommentFiles'])->find($id)->inquiryComments;
        });
    }

    public function destroy($id)
    {
        $model = InquiryComment::find($id);
        if (!$model) {
            throw new PosException('08', '011', 404);
        }
        if ($model->inquiryCommentFiles()->count()) {
            foreach ($model->inquiryCommentFiles()->get() as $inquiryCommentFile) {
                $path = config('filesystems.disks.s3.bucket'). '/inquiry_comment_files/'. $inquiryCommentFile->id;
                if(Storage::exists($path)) {
                    Storage::delete($path);
                }
            }
        }
        $model->delete();
    }

    public function download($id)
    {
        $model = InquiryCommentFile::find($id);
        if (!$model) {
            throw new PosException('08', '012', 404);
        }
        $fileContent = Storage::get(config('filesystems.disks.s3.bucket'). '/inquiry_comment_files/'. $model->id);

        return response($fileContent, 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="'.$model->file_name.'"',
        ]);

    }

    private function validatorFile($params) {
        $validator = Validator::make($params, [
            'file_name' => 'string|max:255',
            'mime_type' => 'string|max:100',
        ]);
        if ($validator->fails()) {
            $response['errors']  = $validator->errors()->toArray();
            throw new HttpResponseException(
                response()->json($response, 422)
            );
        }
    }

}
