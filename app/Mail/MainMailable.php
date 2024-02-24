<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use App\Models\MailTemplate;
use App\Exceptions\PosException;

use Mail;
use Exception;
use Throwable;

class MainMailable extends Mailable
{
    use Queueable, SerializesModels;

    private $mailTemplate;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($templateId)
    {
        $this->mailTemplate = MailTemplate::where('template_id', $templateId)->first();
        if (!$this->mailTemplate) {
            throw new PosException('99', '001', 500);
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->text('emails.main')->from($this->mailTemplate->from)->subject($this->mailTemplate->title);
    }

    public function setViewData($param)
    {
        try {
            $this->viewData = [
                'text' => $this->parseBlade($this->mailTemplate->content, $param)
            ];
        } catch(Throwable $e) {
            throw new PosException('99', '002', 500);
        }
    }

    private function parseBlade($string, $param = null)
    {
        app(\Illuminate\Contracts\View\Factory::class)
            ->share('errors', app(\Illuminate\Support\MessageBag::class));

        extract(app('view')->getShared(), EXTR_SKIP);
        $__env->incrementRender();

        if ($param) {
            extract($param, EXTR_SKIP);
        }
        unset($param);

        ob_start();
        eval('?>' . app('blade.compiler')->compileString($string));
        $content = ltrim(ob_get_clean());

        $__env->decrementRender();
        $__env->flushStateIfDoneRendering();

        return $content;
    }

    public function sendMail($to)
    {
        try {
            return Mail::to($to)->send($this);
        } catch(Exception $e) {
            throw new PosException('99', '003', 500);
        }
    }
}
