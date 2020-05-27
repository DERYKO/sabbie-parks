<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use LaravelFCM\Facades\FCM;
use LaravelFCM\Message\PayloadNotificationBuilder;
use LaravelFCM\Message\Topics;

class notifyBookingDue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'booking:due';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        User::get()->each(function ($user){
            $notificationBuilder = new PayloadNotificationBuilder("Booking expiry");
            $notificationBuilder->setBody("Hurry!! You booking expires in 5 mins.")
                ->setSound('default');
            $notification = $notificationBuilder->build();
            $topic = new Topics();
            $topic->topic('user' . $user->id);
            FCM::sendToTopic($topic, null, $notification, null);
        });
    }
}
