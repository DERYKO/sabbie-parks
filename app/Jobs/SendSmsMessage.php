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
        $AT = new AfricasTalking('DigiTours', 'e580128f1696de9b3b3184c96fc66173775f70e59818a1e0ae986eca80db67df');
        $sms = $AT->sms();
        $sms->send([
            'to' => $this->number,
            'message' => $this->message
        ]);

    }
}
