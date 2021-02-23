<?php

use App\ParkingSpot;
use Dingo\Api\Routing\Router;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$myapp = app(Router::class);
$myapp->version('v1', function ($api) {
    $api->group(['namespace' => 'App\Http\Controllers\Api', 'prefix' => 'v1'], function (Router $api) {
        $api->post('/login', 'Auth\\AuthController@login');
        $api->post('/code', 'Auth\\AuthController@codeValidation');
        $api->get('/spaces', function () {
            $spots = ParkingSpot::with('client:id,name')->get(['id', 'client_id', 'parking_spot_code', 'land_mark', 'latitude', 'longitude']);
            return response()->json($spots);
        });
        $api->resource('/spot', 'SpotController');
        $api->post('/transactions/{id}','MpesaController@transaction_logs');
        $api->post('/load-wallets/{id}','MpesaController@walletCallBack');
        $api->group(['middleware' => ['auth:api']], function (Router $api) {
            $api->get('/recharge-account','MpesaController@loadWallet');
            $api->get('/lipa-na-mpesa', 'MpesaController@lipa_na_mpesa');
            $api->get('/lipa-na-wallet', 'WalletController@lipaNaWallet');
            $api->resource('/vehicle-type', 'VehicleTypeController');
            $api->resource('/card', 'CreditCardController');
            $api->get('/logout', 'Auth\\AuthController@logout');
            $api->resource('/profile', 'Auth\\ProfileController');
            $api->resource('/client', 'ClientController');
            $api->resource('/booking', 'BookingController');
            $api->resource('/reservation', 'ReservationController');
            $api->resource('/allowed', 'AllowedController');
            $api->resource('/collection', 'CollectionController');
            $api->resource('/feature', 'FeatureController');
            $api->resource('/pricing', 'PricingController');
            $api->resource('/security', 'SecurityController');
            $api->resource('/vehicle', 'VehicleController');
            $api->resource('/wallet', 'WalletController');
        });
    });
});
