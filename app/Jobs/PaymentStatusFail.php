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

class PaymentStatusFail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $message,$user;

    /**
     * PaymentStatusFail constructor.
     * @param $message
     * @param $user
     */
    public function __construct($message, $user)
    {
        $this->message = $message;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $notificationBuilder = new PayloadNotificationBuilder('Payment status');
        $notificationBuilder->setBody($this->message)
            ->setSound('default');

        $notification = $notificationBuilder->build();
        $topic = new Topics();
        $topic->topic('user'.$this->user->id);
        FCM::sendToTopic($topic, null, $notification, null);
    }
}
