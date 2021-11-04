<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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
        $qt = (int)$data['qt'];
        $sqlQuery = "INSERT INTO Products (name,price,qt)
                    VALUES('$product_name',$price,$qt);";
        try{
            DB::insert($sqlQuery);
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
        $sqlQuery = "SELECT *
                    FROM Products
                    where id = ?";       
        $result = DB::select($sqlQuery, [$product_id]);
        $data = json_encode($result);
        $data = json_decode($data, true);   //store data in array        
        if(empty($data) == false){
            return $data[0];
        }        
        return $data;
    }    
    public function update(Request $request){
        $product_id = (int)$request->product_id;
        $name = $request['name'];
        $price = $request['price'];
        $qt = $request['qt'];
        $sqlQuery = "Update Products
                        set name = ?,
                            price = ?,
                            qt = ?
                    where id = ?";
        try{ 
            $affected = DB::update($sqlQuery, [$name, $price, $qt, $product_id]);
        }
        catch(Exception $e)
        {
            $errorCode = $e->errorInfo[1];                     
            return $e;
        }        
        return back()->with('status', 'Data successfully updated');
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
