<?php

namespace App\Jobs;

use App\AdministrativeAreaLevel1;
use App\AdministrativeAreaLevel2;
use App\AdministrativeAreaLevel3;
use App\AdministrativeAreaLevel4;
use App\Location;
use App\ParkingSpot;
use App\Region;
use App\SubLocality;
use Http\Adapter\Guzzle6\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class ParkingSpotAddress implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $parking_spot;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(ParkingSpot $parking_spot)
    {
        $this->parking_spot = $parking_spot;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->get('https://maps.googleapis.com/maps/api/geocode/json?latlng=' . $this->parking_spot->latitude . ',' . $this->parking_spot->longitude . '&key=AIzaSyAwB-YqrFP1K_TdPNAJ_DapYcqC4v6FM58');
        $response = $response->getBody()->getContents();
        $result = json_decode($response);
        collect($result->results)->reverse()->each(function ($item){
            if (collect($item->types)->contains('locality')) {
                Region::updateOrCreate([
                    'country_code' => 'ke',
                    'name' => $item->formatted_address
                ]);
            }
            if (collect($item->types)->contains('administrative_area_level_1')) {
                AdministrativeAreaLevel1::updateOrCreate([
                    'parking_spot_id' => $this->parking_spot->id,
                    'formatted_address' => $item->formatted_address
                ]);
            }
            if (collect($item->types)->contains('sublocality')) {
                SubLocality::updateOrCreate([
                    'parking_spot_id' => $this->parking_spot->id,
                    'formatted_address' => $item->formatted_address
                ]);
            }
            if (collect($item->types)->contains('administrative_area_level_2')) {
                AdministrativeAreaLevel2::updateOrCreate([
                    'parking_spot_id' => $this->parking_spot->id,
                    'formatted_address' => $item->formatted_address
                ]);
            }
            if (collect($item->types)->contains('administrative_area_level_3')) {
                AdministrativeAreaLevel3::updateOrCreate([
                    'parking_spot_id' => $this->parking_spot->id,
                    'formatted_address' => $item->formatted_address
                ]);
            }
            if (collect($item->types)->contains('administrative_area_level_4')) {
                AdministrativeAreaLevel4::updateOrCreate([
                    'parking_spot_id' => $this->parking_spot->id,
                    'formatted_address' => $item->formatted_address
                ]);
            }
        });
    }
}
