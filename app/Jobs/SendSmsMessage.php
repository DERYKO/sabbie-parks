<?php

namespace App\Jobs;

use AfricasTalking\SDK\AfricasTalking;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendSmsMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public $number, $message;

    /**
     * SendSmsMessage constructor.
     * @param $number
     * @param $message
     */
    public function __construct($number, $message)
    {
        $this->number = $number;
        $this->message = $message;
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $AT = new AfricasTalking('sandbox', 'c0e0b268367d01d7efbd98c3db87fd6e26544806ad9a872534ea459358074c8c');
        $sms = $AT->sms();
        $sms->send([
            'to' => $this->number,
            'message' => $this->message
        ]);

    }
}
