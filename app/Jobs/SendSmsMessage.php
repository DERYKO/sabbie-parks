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
        $AT = new AfricasTalking('sandbox', 'a4f17e867430bc2c6e5a0627229b8f2d6d1860203d13a4d2426231a7a678a8ce');
        $sms = $AT->sms();
        $sms->send([
            'to' => $this->number,
            'message' => $this->message
        ]);

    }
}
