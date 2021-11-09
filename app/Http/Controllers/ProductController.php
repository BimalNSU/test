<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\MyClass\Product;

class ProductController extends Controller
{
    public function index(){
        return view('product.index');
    }

    public function create(Request $request){
        $rules = array(
            'name' => 'required|string|max:20',
            'price' => 'required',
            'qt' => 'required|int'
        );

        // getting json data
        $data = $request->all();
        $error = Validator::make($request->all(), $rules);
        if($error->fails())
        {
            return back()->with('errors', $error->errors()->all());
        }

        //extracting json data
        $product_name = $data['name'];
        $price = (float)$data['price'];
        $qt = $data['qt'];
        try{
            $product = new Product();
            $product->create($product_name, $price, $qt);
            // return response()->json(['success' => 'Data Added successfully.']);
            return back()->with('success','Data Added successfully.');
        }
        catch(Exception $e)
        {
            $errorCode = $e->errorInfo[1];
            if($errorCode == 1062)
            {
                return "duplicate data insertion error";
            }
            return $e;
        }       
    }
    public function editPage($product_id){
        $data = $this->details($product_id);
        return view('product.index', ['data' => $data]);
    }
    public function getProductList(){
        $sqlQuery = "SELECT *
                    FROM Products";
        $result = DB::select($sqlQuery);
        $data = json_encode($result);
        $data = json_decode($data, true);   //store data in array
        return view('product.report', ['data' => $data]);
    }
    public function details($product_id){
        $product= new Product();
        return $product->getDetails($product_id);
    }    
    public function update(Request $request){        
        $rules = array(
            'name' => 'required|string|max:20',
            'price' => 'required',
            'qt' => 'required|int|min:1'
        );
        $product_id = (int)$request->product_id;
        // getting encoded json data
        $data = $request->data;
        $data = json_decode($data, true);   
        $error = Validator::make($data, $rules);
        if($error->fails())
        {
            return response()->json(['error'=> $error->errors()->all() ]);
        }     
        $product_data = array("id"=>$product_id,
                            "name"=>$data['name'],
                            "price"=>$data['price'],
                            "qt"=>$data['qt']);        
        try{ 
            $product = new Product();
            $product->update($product_data);
        }
        catch(Exception $e)
        {
            $errorCode = $e->errorInfo[1];                     
            // return $e;
            return response()->json(['error'=> $e]);
        }        
        // return JSON response for ajax call
        return response()->json(['success' => 'Product id ' . $product_id . ' is updated successfully.']);
    }
    public function delete($product_id){
        $sqlQuery = "DELETE 
            FROM Products 
            WHERE id = ?";
        try{ 
            $deleted = DB::delete($sqlQuery, [$product_id]);
        }
        catch(Exception $e)
        {
            $errorCode = $e->errorInfo[1];         
            return $e;
        }
        return response()->json(['success' => 'Delete successfully']);
    }

}
