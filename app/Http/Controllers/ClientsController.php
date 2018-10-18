<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Client;
use DB;

class ClientsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clients = Client::all();
        return view('clients.index')->with('clients',$clients);
        // return view('clients.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('clients.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $this->validate($request, [
            'name' => 'required',
            'organization' => 'required',
            'address' => 'required'
        ]);

        $client = new Client;
        $client->name = $request->input('name');
        $client->organization = $request->input('organization');
        $client->address = $request->input('address');

        $client->save();

        return redirect('/clients')->with('success', 'Client Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $assignment = Client::find($id);
        return view('clients.show')->with('assignment',$assignment);
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
        $assignment = Client::find($id);
        return view('clients.edit')->with('assignment', $assignment);
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
        $this->validate($request, [
            'name' => 'required',
            'organization' => 'required',
            'address' => 'required'
        ]);

        // Create Post
        $client = Client::find($id);
        $client->name = $request->input('name');
        $client->organization = $request->input('organization');
        $client->address = $request->input('address');

        
        $client->save();

        return redirect('/clients')->with('success', 'Client Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $client = Client::find($id);

        // Check for correct user
        
        $client->delete();
        return redirect('/clients')->with('success', 'Post Removed');
    }
}
