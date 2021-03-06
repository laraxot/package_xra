<?php



namespace XRA\XRA\Utilities\AdminNotify;

use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class SlackAdminNotify extends AdminNotify
{
    protected $webhookUrl;

    protected function setConfig($config)
    {
        $this->webhookUrl = $config['webhook_url'];
    }

    public function send(Notification $notification)
    {
        $this->http->post($this->webhookUrl, $this->buildJsonPayload(
            $notification->toSlack(null)
        ));
    }

    /**
     * Build up a JSON payload for the Slack webhook.
     *
     * @param \Illuminate\Notifications\Messages\SlackMessage $message
     *
     * @return array
     */
    protected function buildJsonPayload(SlackMessage $message)
    {
        $optionalFields = \array_filter([
            'channel' => data_get($message, 'channel'),
            'icon_emoji' => data_get($message, 'icon'),
            'icon_url' => data_get($message, 'image'),
            'link_names' => data_get($message, 'linkNames'),
            'username' => data_get($message, 'username'),
        ]);

        return \array_merge([
            'json' => \array_merge([
                'text' => $message->content,
            ], $optionalFields),
        ], $message->http);
    }
}
