<?php

namespace App\Observers;

use App\Jobs\ParkingSpotAddress;
use App\ParkingSpot;

class ParkingSpotObserver
{
    /**
     * Handle the parking spot "created" event.
     *
     * @param  \App\ParkingSpot  $parkingSpot
     * @return void
     */
    public function created(ParkingSpot $parkingSpot)
    {
        dispatch(new ParkingSpotAddress($parkingSpot));
    }

    /**
     * Handle the parking spot "updated" event.
     *
     * @param  \App\ParkingSpot  $parkingSpot
     * @return void
     */
    public function updated(ParkingSpot $parkingSpot)
    {
        dispatch(new ParkingSpotAddress($parkingSpot));
    }

    /**
     * Handle the parking spot "deleted" event.
     *
     * @param  \App\ParkingSpot  $parkingSpot
     * @return void
     */
    public function deleted(ParkingSpot $parkingSpot)
    {
        //
    }

    /**
     * Handle the parking spot "restored" event.
     *
     * @param  \App\ParkingSpot  $parkingSpot
     * @return void
     */
    public function restored(ParkingSpot $parkingSpot)
    {
        //
    }

    /**
     * Handle the parking spot "force deleted" event.
     *
     * @param  \App\ParkingSpot  $parkingSpot
     * @return void
     */
    public function forceDeleted(ParkingSpot $parkingSpot)
    {
        //
    }
}
