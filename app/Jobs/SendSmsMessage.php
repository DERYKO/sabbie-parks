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
        $AT = new AfricasTalking('sandbox', 'f5b2003cc36ab9aab42adff382f94436472059163b7a627d0f8abdf08c31c8dc');
        $sms = $AT->sms();
        $sms->send([
            'to' => $this->number,
            'message' => $this->message
        ]);

    }
}
