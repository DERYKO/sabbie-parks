<?php

namespace App\Jobs;

use App\Location;
use App\ParkingSpot;
use App\Region;
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
        $country = null;
        $region = null;
        collect($result->results)->reverse()->each(function ($item) use ($region,$country){
            if (collect($item->types)->contains('country')) {
                $country = $item->formatted_address;
            }
            if (collect($item->types)->contains('administrative_area_level_1')) {
                Region::updateOrCreate([
                    'country_code' => $country ? $country : 'KENYA',
                    'name' => $item->formatted_address
                ]);
                $region = Region::where('name',$item->formatted_address)->first();
            }
            Log::info(json_encode($item));
            Log::info($country);
            if (collect($item->types)->contains('sublocality')) {
                Location::updateOrCreate([
                    'region_id' => $region ? $region->id : null,
                    'name' => $item->formatted_address
                ]);
            }
            if (collect($item->types)->contains('administrative_area_level_2')) {
                Location::updateOrCreate([
                    'region_id' => $region ? $region->id : null,
                    'name' => $item->formatted_address
                ]);
            }
            if (collect($item->types)->contains('administrative_area_level_3')) {
                $location = Location::updateOrCreate([
                    'region_id' => $region ? $region->id : null,
                    'name' => $item->formatted_address
                ]);
                $this->parking_spot->update([
                    'location_id' => $location->id,
                ]);
            }
            if (collect($item->types)->contains('administrative_area_level_4')) {
                Location::updateOrCreate([
                    'region_id' => $region ? $region->id : null,
                    'name' => $item->formatted_address
                ]);
            }
        });
    }
}
