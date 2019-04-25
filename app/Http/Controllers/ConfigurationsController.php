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
        if($config->id > 0){
            $response['status'] = 'success';
            $response['message'] = 'Created new configuration successfully';
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
        $config = Configuration::where('name',$id)->first();
        if($config == null){
            $response['status'] = 'failed';
            $response['message'] = 'Could not find configuration with name $id';
        }
        else {
            $response['data'] = json_decode($config->data);
            $response['status'] = 'success';
        }            
        return response()->json($response);
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
        $config = Configuration::where('name',$id)->first();
        if($config == null){
            $response['status'] = 'failed';
            $response['message'] = 'Could not find the configuration in database';
        }
        else {
            $config->data = $request->data;
            $config->save();
            $response['status'] = 'success';
            $response['message'] = 'Configuration updated successfully';
        }

        return response()->json($response);
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
