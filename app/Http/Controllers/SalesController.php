<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    public function index(){
        // $sqlQuery = "SELECT id, name
        //             FROM Products";
        // $result = DB::select($sqlQuery);
        // $data = json_encode($result);
        // $data = json_decode($data, true);   //store data in array
        $product_list = $this->productList();
        return view('sales.index',['product_list'=> $product_list]);
    }
    public function productList(){
        $sqlQuery = "SELECT id, name
            FROM Products";
        $result = DB::select($sqlQuery);
        $data = json_encode($result);
        $data = json_decode($data, true);
        return $data;
    }
    public function create(Request $request){
        $data = $request->data;
        $data = json_decode($data,true);
        $paid = $data['paid'];
        $customer_name = $data['customerName'];
        $customer_phone = $data['customerPhone'];
        $customer_address = $data['customerAddress'];
        $sqlQuery = "Insert into Sales (paid,customer_name,customer_phone, customer_address)
                    values(?,?,?,?)";
        
        DB::beginTransaction();
        try{
            $result = DB::insert($sqlQuery,[$paid, $customer_name, $customer_phone, $customer_address]);
            $sales_id = DB::getPdo()->lastInsertId();
            $sales_items = $data['sales_items'];
            foreach($sales_items as $values){
                $sqlQuery2 = "Insert into Sales_items (sales_id, product_id, sales_price, qt)
                        values(?,?,?,?)";
                DB::insert($sqlQuery2, [$sales_id, 
                                        $values['product_id'], 
                                        $values['price'],
                                        $values['qt'] ]);
            }
            DB::commit();
        }catch(Exception $e)
        {
            DB::rollback();
            $errorCode = $e->errorInfo[1];                
            return $e;
        }
    }
    public function getSalesList(){
        $sqlQuery = "SELECT i.sales_id, s.customer_name customer, s.customer_phone phone, s.customer_address address, 
                     sum(i.sales_price*i.qt) total, s.paid
                    FROM Sales s join Sales_items i
                        ON s.id = i.sales_id
                    group by i.sales_id";
        $result = DB::select($sqlQuery);
        $data = json_encode($result);
        $data = json_decode($data, true);   //store data in array
        // dd($data);
        return view('sales.report',['data'=> $data]);
    }
    public function getSalesDetails($sales_id){
        $sqlQuery = "SELECT id, paid, customer_name, customer_phone, customer_address
                    FROM Sales
                    where id = ?";
        $sqlQuery2 = "SELECT s.product_id, p.name, s.sales_price, s.qt
                    FROM Sales_items s JOIN Products p
                        on s.product_id = p.id
                    where s.sales_id = ?";
        $result = DB::select($sqlQuery, [$sales_id]);
        $data = json_encode($result);
        $data = json_decode($data, true);   //store data in array
        if(empty($data) == false){
            $data = $data[0];
        }
        $result = DB::select($sqlQuery2, [$sales_id]);
        $data2 = json_encode($result);
        $data2 = json_decode($data2, true);   //store data in array
        // if(empty($data2) == false){
        //     $data2 = $data2[0];
        // }
        $data = array($data, $data2);
        $product_list = $this->productList();
        return view('sales.index', ['data' => $data,'product_list' => $product_list]);
        // dd( $data);
    }
    public function editHelper($sales_data){
        $dynamic_query = "";        
        $flag = 0;
        $arr = array();
        if($sales_data['paid'] != null){
            $dynamic_query .= editHelper2($flag, "paid");
            array_push($arr, $sales_data['paid']);
            $flag = 1;
        }
        else if($sales_data['customer_name'] != null){
            $dynamic_query .= editHelper2($flag, "customer_name");
            array_push($arr, $sales_data['customer_name']);
            $flag = 1;
        }
        else if($sales_data['customer_mobile'] != null){
            $dynamic_query .= editHelper2($flag, "customer_mobile");
            array_push($arr, $sales_data['customer_mobile']);
            $flag = 1;
        }
        else if($sales_data['customer_address'] != null){
            $dynamic_query .= editHelper2($flag, "customer_address");
            array_push($arr, $sales_data['customer_address']);
            $flag = 1;
        }
        $dynamic_query .= "WHERE id = ?;";
        $result = array($dynamic_query, $arr);
        return $result;
    }
    public function editHelper2($flag, $field_name){
        $statement = "";
        if($flag == 0){
            $statement .= "UPDATE sales
                            SET ";
        }
        else if($flag == 1){
            $statement .= ",";
        }
        $statement .= $field_name ." = ?";
        return $statement;
    }
    public function edit(Request $request){
        $sales_id = (int)$request->sales_id;
        $sales_data = $data['sales_data'];
        $sales_items = $sales_data['sales_items'];
        $result = editHelper($sales_data);
        $sqlQuery = $result[0];
        $placeHolderData = $result[1];
        
        array_push($placeHolderData, $sales_id);
        DB::beginTransaction();
        try{ 
            // check for new updates for sales table
            if(empty($sqlQuery) == false){
                $affected = DB::update($sqlQuery, $placeHolderData);
            }            
            foreach ($sales_items as $value){
                $sqlQuery2 = "DELETE 
                    FROM Sales_items
                    WHERE sales_id = ? AND product_id = ?";
                DB::delete($sqlQuery2, [$sales_id, $value]);               
            }
            DB::commit();
        }
        catch(Exception $e)
        {
            DB::rollback();
            $errorCode = $e->errorInfo[1];                     
            return $e;
        }        
        return back()->with('status', 'Data successfully updated');
    }
    public function delete($sales_id){
        $sqlQuery = "DELETE 
            FROM Sales 
            WHERE id = ?";
        DB::beginTransaction();
        try{ 
            $deleted = DB::delete($sqlQuery, [$sales_id]);
            DB::commit();
        }
        catch(Exception $e)
        {
            DB::rollback();
            $errorCode = $e->errorInfo[1];         
            return $e;
        }
        return response()->json(['response' => 'Delete successfully']);
    }
}