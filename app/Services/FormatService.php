<?php


namespace App\Services;


use App\Exceptions\PosException;
use App\Models\Format;
use App\Traits\ListTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class FormatService
{
    use ListTrait;

    public function index($params)
    {
        $this->query = Format::query()->with(['formatTags']);
        $this->params = $params;
        $this->addBooleanParam('is_private');
        $this->addParams('title', true);
        $this->addParams('file_name', true);
        return $this->query();
    }

    public function show($id)
    {
        $model = Format::query()->with(['formatTags'])->find($id);
        if (!$model) {
            throw new PosException('09', '001', 404);
        }
        return $model;
    }

    public function update($params, $id)
    {
        $model = Format::with(['formatTags'])->find($id);
        if(!$model) {
            throw new PosException('09', '002', 404);
        }
        if (isset($params['file'])) {
            $file = $params['file'];
            $params['file_name'] = $file->getClientOriginalName();
            $params['mime_type'] = $file->getClientMimeType();
            $this->validatorFile($params);
            $path =  config('filesystems.disks.s3.bucket'). '/format/'. $id;
            $params['file_path'] = config('filesystems.disks.s3.url'). '/'. $path;
            Storage::put($path, file_get_contents($file), 'public');
        }
        if(array_key_exists('format_tags', $params)) {
            $model->formatTags()->delete();
            foreach ($params['format_tags'] as $data) {
                $model->formatTags()->create($data);
            }
        }
        $model->fill($params)->save();
        return $model->refresh();
    }

    public function store($params)
    {
        return DB::transaction(function () use ($params) {
            $model = new Format;
            $model->fill($params)->save();
            if (isset($params['file'])) {
                $file = $params['file'];
                $params['file_name'] = $file->getClientOriginalName();
                $params['mime_type'] = $file->getClientMimeType();
                $path = config('filesystems.disks.s3.bucket'). '/format/'. $model->id;
                $params['file_path'] = config('filesystems.disks.s3.url'). '/'. $path;
                $this->validatorFile($params);
                Storage::put($path, file_get_contents($file), 'public');
            }
            if(!empty($params['format_tags']) ) {
                foreach ($params['format_tags'] as $data) {
                    $model->formatTags()->create($data);
                }
            }
            $model->fill($params)->save();
            return Format::with(['formatTags'])->find($model->id);
        });
    }

    public function destroy($id)
    {
        $model = Format::find($id);
        if (!$model) {
            throw new PosException('09', '003', 404);
        }
        $model->formatTags()->delete();
        $model->delete();
    }

    public function download($id)
    {
        $model = Format::find($id);
        if (!$model) {
            throw new PosException('09', '004', 404);
        }

        $fileContent = Storage::get(config('filesystems.disks.s3.bucket'). '/format/'. $model->id);

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
