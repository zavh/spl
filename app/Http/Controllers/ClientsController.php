<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Client;
use App\Role;
use Illuminate\Support\Facades\Auth;
use DB;

class ClientsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        
        $assignments = Client::all();
        return view('clients.index')->with('assignments', $assignments);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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
            'name' => 'required|max:50|min:4',
            'organization' => 'required|max:191:min:4',
            'address' => 'required|max:191',
            'contact' => 'required|min:6'
        ]);

        $client = new Client;
        $client->name = $request->input('name');
        $client->organization = $request->input('organization');
        $client->address = $request->input('address');
        $client->contact = $request->input('contact');
        $newClient = $client->save();

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
    public function destroy(Client $client)
    {
        $findClient = Client::find($client->id);

        if($findClient->delete()){
            return redirect()->route('clients.index')
            ->with('success', 'Client deleted successfully');
        }

        return back()->withInput()->with('error', 'Client could not be deleted');
    }
}
