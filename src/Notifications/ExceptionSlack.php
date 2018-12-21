<?php

namespace XRA\XRA\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;

class ExceptionSlack extends Notification implements ShouldQueue
{
    use Queueable;

    public $msg='';

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(\Exception $e)
    {
        //ddd($e);
        $this->msg.=chr(13).'Line   :    '.$e->getLine();
        $this->msg.=chr(13).'File   :    '.$e->getFile();
        $this->msg.=chr(13).'Url    :    '.URL::current();
        $this->msg.=chr(13).'Msg    :    '.$e->getMessage();
        $this->msg.=chr(13).'at     :    '.Carbon::now();
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['slack'];
    }

    public function toSlack($notifiable)
    {
        $content=$this->msg;//$e->getMessage();
        return (new SlackMessage)
            ->content($content);
    }
}
