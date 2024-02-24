<?php


namespace App\Services;


use App\Exceptions\PosException;
use App\Models\MailTemplate;
use App\Traits\ListTrait;
use Illuminate\Support\Facades\DB;

class MailTemplateService
{
    use ListTrait;

    public function index()
    {
        return MailTemplate::get();
    }

    public function show($code)
    {
        $model = MailTemplate::where('template_id', $code)->first();
        if (!$model) {
            throw new PosException('04', '001', 404);
        }
        return $model;
    }

    public function update($params, $code)
    {
        $model = MailTemplate::where('template_id', $code)->first();
        if(!$model) {
            throw new PosException('04', '002', 404);
        }
        if (!$this->validateTemplate($params['content'])) {
            throw new PosException('04', '003', 422);
        }
        $model->fill($params)->save();
        return $model;
    }

    private function validateTemplate($content)
    {
        $isValid = true;
        $successPattern = '/\{{2}\${1}[a-zA-Z\_]{1,}\S\}{2}/';
        $errorPattern1 = '/\{{3,}\${1}[a-zA-Z\_]{1,}\}{0,}/';
        $errorPattern2 = '/\{{0,}\${1}[a-zA-Z\_]{1,}\}{3,}/';

        if(preg_match($errorPattern1, $content)) {
            $isValid = false;
        } else if(preg_match($errorPattern2, $content)) {
            $isValid = false;
        } else {
            $tmp = preg_replace($successPattern, '', $content);
            if (strpos($tmp, '{') > -1 || strpos($tmp, '}' > -1)) {
                $isValid = false;
            }
            else {
                $isValid = true;
            }
        }
        return $isValid;
    }

}
