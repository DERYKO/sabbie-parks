<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Security;
use Illuminate\Http\Request;

class SecurityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $security = Security::get();
        return response()->json($security);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Security::create([
            'name' => $request->name,
            'icon' => $request->icon
        ]);
        return $this->index();
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $security = Security::findOrfail($id);
        return response()->json($security);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $security = Security::findOrfail($id);
        $security->update([
            'icon' => $request->icon,
            'name' => $request->name
        ]);
        return response()->json($security);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
