<?php

namespace App\Http\Controllers;
use App\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ProductsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $department = Auth::User()->department;
        $product = Product::where('department_id',$department->id)->get();
        return view('products.index',['department'=>$department,'product'=>$product]);
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
        //
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
        //
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

    public function addchild(Request $request)
    {
        //dd($request->all());
        $product['name'] = $request->product['name'];
        $product['level'] = $request->product['level'] + 1;
        $product['index'] = $request->product['index'];
        return view('products.addchild',['product'=>$product]);
    }

    public function addsubcat(Request $request)
    {
        $level = $request->level;
        $index = $request->index;
        $subcat = $request->subcat;
        return view('products.addcatform',['level'=>$level,'index'=>$index, 'subcat'=>$subcat]);
    }

    public function addparam(Request $request)
    {
        $level = $request->level;
        $params = $request->params;
        $index = 0;
        return view('products.addparams',['level'=>$level,'index'=>$index, 'params'=>$params]);
    }

    public function addcheckgroup(Request $request)
    {
        $level = $request->level;
        $params = $request->params;
        $index = 0;
        
        return view('products.addcheckgroup',['level'=>$level,'index'=>$index, 'params'=>$params]);
    }

    public function preview($product_json){
        $product = json_decode($product_json);
        return view('products.preview',['product'=>$product]);
    }
}
