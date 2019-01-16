<?php



namespace XRA\XRA\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class ExceptionSlack extends Notification implements ShouldQueue
{
    use Queueable;

    public $msg = '';

    /**
     * Create a new notification instance.
     */
    public function __construct(\Exception $e)
    {
        //ddd($e);
        $this->msg .= \chr(13).'Line   :    '.$e->getLine();
        $this->msg .= \chr(13).'File   :    '.$e->getFile();
        $this->msg .= \chr(13).'Url    :    '.URL::current();
        $this->msg .= \chr(13).'Msg    :    '.$e->getMessage();
        $this->msg .= \chr(13).'at     :    '.Carbon::now();
        if (\Auth::check()) {
            $this->msg .= \chr(13).'user     :    '.\Auth::user()->handle;
        }
        $data = \json_encode(\Request::all(), JSON_PRETTY_PRINT);
        $this->msg .= \chr(13).'request : '.$data;
        //$this->msg.=chr(13).' -- debug backtrace --';
        //$this->msg.=chr(13).$e->getTraceAsString();
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        return ['slack'];
    }

    public function toSlack($notifiable)
    {
        $content = $this->msg; //$e->getMessage();
        return (new SlackMessage())
            ->content($content);
    }
}
