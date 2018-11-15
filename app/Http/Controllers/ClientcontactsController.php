<?php

namespace App\Http\Controllers;

use App\Clientcontact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClientcontactsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($client_id)
    {
        $clinetContacts = Clientcontact::where('client_id', $client_id)->get();
        return view('clientcontacts.index',['contacts'=>$clinetContacts]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($client_id)
    {
        return view('clientcontacts.create')->with('client_id', $client_id);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { 
        $messages = [
            'contact_name.required' => 'contact_name|Please enter a valid name',
            'designation.required' => 'designation|Designation is required',
            'contact.required' => 'contact|Enter a valid phone number',
            'contact.min' => 'contact|Contact number should be at least 6 digits',
            'contact.max' => 'contact|Contact number should cannot exceed 17 digits',
            'contact.integer' => 'contact|Phone number should contain digits only',
        ];

        $validator = Validator::make($request->all(), [
            'contact_name' => 'required|max:100|min:3',
            'designation' => 'required|max:100:min:4',
            'contact' => 'required|integer|min:100000|max:99999999999999999'],
            $messages);
        
        if($validator->fails()){
            $result['status'] = 'failed';
            $result['message'] = $validator->errors()->all();
            return response()->json(['result'=>$result]);
        }
        $contactCreate = Clientcontact::create([
            'name' => $request['contact_name'],
            'designation' => $request['designation'],
            'contact' => $request['contact'],
            'client_id' => $request['client_id'],
            ]);
        $result['status'] = 'success';
        $result['client_id'] = $request['client_id'];
        return response()->json(['result'=>$result]);
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
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $cc = Clientcontact::find($id);
        return view('clientcontacts.edit', ['cc'=>$cc]);
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
        // dd($request);   
        $messages = [
            'name.required' => 'name|Please enter a valid name',
            'designation.required' => 'designation|Designation is required',
            'contact.required' => 'contact|Enter a valid phone number',
            'contact.min' => 'contact|Contact number should be at least 6 digits',
            'contact.max' => 'contact|Contact number should cannot exceed 17 digits',
            'contact.integer' => 'contact|Phone number should contain digits only',
        ];

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:100|min:3',
            'designation' => 'required|max:100:min:4',
            'contact' => 'required|integer|min:100000|max:99999999999999999'],
            $messages);
        
        if($validator->fails()){
            $result['status'] = 'failed';
            $result['message'] = $validator->errors()->all();
            return response()->json(['result'=>$result]);
        }
        $contact = Clientcontact::find($id);
        $contact->task_name = $request->input('contact_name');
        $contact->designation = $request->input('designation');
        $contact->contact = $request->input('contact');
        $contact->client_id = $request->input('client_id');
            
        $result['status'] = 'success';
        $result['client_id'] = $request['client_id'];
        return response()->json(['result'=>$result]);
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

    public function contactheader($client_id)
    {
        return view('clientcontacts.contactheader')->with('client_id', $client_id);
    }

    public function contactlist($client_id)
    {
        $clinetContacts = Clientcontact::where('client_id', $client_id)->get();
        //return view('clientcontacts.contactlisting',['contacts'=>$clinetContacts]);
        $response['view'] = view('clientcontacts.contactlisting',['contacts'=>$clinetContacts])->render();
        $response['contacts'] = json_encode($clinetContacts);
        return response()->json(['data'=>$response]);
    }
}
