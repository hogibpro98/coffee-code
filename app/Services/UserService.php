<?php

namespace App\Services;

use Illuminate\Support\Str;

use App\Traits\ListTrait;
use App\Models\User;
use App\Mail\MainMailable;
use App\Exceptions\PosException;

use DB;

class UserService
{
    use ListTrait;

    public function index($params)
    {
        $this->query = User::query();
        $this->params = $params;
        $this->addParams('name', true);
        $this->addParams('email', true);
        if (isset($params['is_include_deleted']) && $params['is_include_deleted'] == "true") {
            $this->query->withTrashed();
        }
        return $this->query();
    }

    public function show($id)
    {
        $model = User::find($id);
        // 存在しなかったら404
        if (!$model) {
            throw new PosException('01', '001', 404);
        }
        return $model;
    }

    public function update($params, $id)
    {
        $model = User::find($id);
        if (!$model) {
            throw new PosException('01', '002', 404);
        }
        $model->fill($params)->save();
        return $model;
    }

    public function store($params)
    {
        return DB::transaction(function () use($params) {
            $password = Str::random(8);
            $model = new User();
            $model->password = bcrypt($password);
            $model->fill($params)->save();

            $mailTemplate = new MainMailable(1);
            $mailTemplate->setViewData([
                'name' => $model->name,
                'email' => $model->email,
                'password' => $password,
                'url' => config('app.admin_site_url'),
            ]);
            $mailTemplate->sendMail($model->email);

            return $model;
        });
    }

    public function destroy($id)
    {
        $model = User::find($id);
        // 存在しなかったら404
        if (!$model) {
            throw new PosException('01', '001', 404);
        }
        // 削除ユーザがログインユーザの場合は400
        if (auth()->user()->id === $model->id) {
            throw new PosException('01', '003', 400);
        }
        $model->delete();
    }

    public function changePassword($params)
    {
        $model = auth()->user();

        $model->password = bcrypt($params['password']);
        $model->save();
    }

    public function resetPassword($id)
    {
        return DB::transaction(function () use($id) {
            $password = Str::random(8);
            $model = User::find($id);
            if (!$model) {
                throw new PosException('01', '004', 400);
            }
            $model->password = bcrypt($password);
            $model->save();

            $mailTemplate = new MainMailable(2);
            $mailTemplate->setViewData([
                'name' => $model->name,
                'email' => $model->email,
                'password' => $password,
                'url' => config('app.admin_site_url'),
            ]);
            $mailTemplate->sendMail($model->email);
        });
    }
}
