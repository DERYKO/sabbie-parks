<?php

namespace App\Http\Controllers\Api;

use App\Client;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clients = Client::with('spots')->get();
        return response()->json($clients);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Client::create(
            $request->only('name', 'logo', 'country_code', 'country', 'region_id', 'location_id', 'address', 'telephone_no', 'telephone_no_1', 'email_address', 'website', 'postal_address', 'postal_code', 'latitude', 'longitude')
        );
        return $this->index();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $client = Client::findOrfail($id);
        return response()->json($client);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $client = Client::findOrfail($id);
        $client->update(
          $request->only('name', 'logo','location_id', 'address', 'telephone_no', 'telephone_no_1', 'email_address', 'website', 'postal_address', 'postal_code', 'latitude', 'longitude')
        );
        return response()->json($client);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
