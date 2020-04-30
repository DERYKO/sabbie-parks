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

class PaymentStatusSuccess implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $message, $user, $expiry_time, $fee, $parking_spot;

    /**
     * PaymentStatusSuccess constructor.
     * @param $message
     * @param $user
     * @param $expiry_time
     * @param $fee
     * @param $parking_spot
     */
    public function __construct($message, $user, $expiry_time, $fee, $parking_spot)
    {
        $this->message = $message;
        $this->user = $user;
        $this->expiry_time = $expiry_time;
        $this->fee = $fee;
        $this->parking_spot = $parking_spot;
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $notificationBuilder = new PayloadNotificationBuilder('Payment status');
        $notificationBuilder->setBody($this->message . ' Booking expires in ' . $this->expiry_time . ' which in case of an inconvenience a fee of ' . $this->fee . ' will be added to your account.')
            ->setSound('default');
        $notification = $notificationBuilder->build();
        $topic = new Topics();
        $topic->topic('user'.$this->user->id);
        FCM::sendToTopic($topic, null, $notification, null);
    }
}
