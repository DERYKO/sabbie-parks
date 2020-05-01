<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use LaravelFCM\Facades\FCM;
use LaravelFCM\Message\PayloadNotificationBuilder;
use LaravelFCM\Message\Topics;

class BroadcastMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $title, $message, $user_id;

    /**
     * BroadcastMessage constructor.
     * @param $title
     * @param $message
     * @param $user_id
     */
    public function __construct($title, $message, $user_id)
    {
        $this->title = $title;
        $this->message = $message;
        $this->user_id = $user_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $notificationBuilder = new PayloadNotificationBuilder($this->title);
        $notificationBuilder->setBody($this->message)
            ->setSound('default');
        $notification = $notificationBuilder->build();
        $topic = new Topics();
        $topic->topic('user' . $this->user_id);
        FCM::sendToTopic($topic, null, $notification, null);
    }
}
