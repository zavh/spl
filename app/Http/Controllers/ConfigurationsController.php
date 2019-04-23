<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Configuration;

class ConfigurationsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('configurations.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $config = new Configuration;
        $config->name = $request->field;
        $config->data = $request->data;
        $config->save();

        return response()->json($request);
    }

    public function gettaxconfig(){
        $config = Configuration::where('name','taxconfig')->first();
        if($config == null)
            $response['status'] = 'failed';
        else {
            $response['data'] = json_decode($config->data);
            $response['status'] = 'success';
        }            
        return response()->json($response);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        return response()->json($request->all());
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
