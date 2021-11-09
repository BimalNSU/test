<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\MyClass\Product;

class SalesController extends Controller
{
    public function index(){
        $data['product_list'] = $this->getAllProductsId_n_Name();
        return view('sales.index',['data'=> $data]);
    }
    public function getAllProductsId_n_Name(){
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
            $product = new Product();
            foreach($sales_items as $value){
                $sqlQuery2 = "Insert into Sales_items (sales_id, product_id, sales_price, qt)
                        values(?,?,?,?)";
                $product_id = $value['product_id'];
                $product_data = $product->getDetails($product_id);
                $remainingQt = $product_data['qt'] - $value['qt'];                
                if($remainingQt >= 0){
                    $product->update(array("id"=>$value['product_id'], "qt"=>$remainingQt));
                    DB::insert($sqlQuery2, [$sales_id, 
                                            $value['product_id'], 
                                            $value['price'],
                                            $value['qt'] ]);
                }
                else{
                    return response()->json(['success' => $product_data['name'].'available stock: '.$product_data['qt'] . ' Insufficient stock']);
                }
            }
            DB::commit();
        }catch(Exception $e)
        {
            DB::rollback();
            $errorCode = $e->errorInfo[1];                
            return $e;
        }
        // return redirect()->back()->with('success', 'IT WORKS!');
        return response()->json(['success' => 'Data is created successfully.']);
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
        $data = array($data, $data2);
        
        // return view('sales.index', ['data' => $data,'product_list' => $product_list]);        
        return $data;
    }
    public function getSalesView($sales_id){
        $data = $this->getSalesDetails($sales_id);
        return view('sales.index', ['data' => $data]);
    }
    public function getSalesEdit($sales_id){
        $data = $this->getSalesDetails($sales_id);
        $data['product_list'] = $this->getAllProductsId_n_Name();
        return view('sales.index', ['data' => $data]);
    }

    // public function editHelper($sales_data){
    //     $dynamic_query = "";        
    //     $flag = 0;
    //     $arr = array();
    //     if($sales_data['paid'] != null){
    //         $dynamic_query .= editHelper2($flag, "paid");
    //         array_push($arr, $sales_data['paid']);
    //         $flag = 1;
    //     }
    //     else if($sales_data['customer_name'] != null){
    //         $dynamic_query .= editHelper2($flag, "customer_name");
    //         array_push($arr, $sales_data['customer_name']);
    //         $flag = 1;
    //     }
    //     else if($sales_data['customer_mobile'] != null){
    //         $dynamic_query .= editHelper2($flag, "customer_mobile");
    //         array_push($arr, $sales_data['customer_mobile']);
    //         $flag = 1;
    //     }
    //     else if($sales_data['customer_address'] != null){
    //         $dynamic_query .= editHelper2($flag, "customer_address");
    //         array_push($arr, $sales_data['customer_address']);
    //         $flag = 1;
    //     }
    //     $dynamic_query .= "WHERE id = ?;";
    //     $result = array($dynamic_query, $arr);
    //     return $result;
    // }
    // public function editHelper2($flag, $field_name){
    //     $statement = "";
    //     if($flag == 0){
    //         $statement .= "UPDATE sales
    //                         SET ";
    //     }
    //     else if($flag == 1){
    //         $statement .= ",";
    //     }
    //     $statement .= $field_name ." = ?";
    //     return $statement;
    // }

    public function edit(Request $request){
        $sales_id = (int)$request->sales_id;
        $data = $request->data;
        $data = json_decode($data, true);
        $sales_items = $data['sales_items'];
       
        $sqlQuery = "Update Sales
                        set paid = ?,
                            customer_name = ?,
                            customer_phone = ?,
                            customer_address = ?
                    WHERE id = ?";
        DB::beginTransaction();
        try{ 
            $affected = DB::update($sqlQuery, [$data['paid'],
                                                $data['customerName'],
                                                $data['customerPhone'],
                                                $data['customerAddress'],
                                                $sales_id ]);
            //update stock before updating sales items
            //todo

            $sqlQuery3 = "DELETE 
                    FROM Sales_items
                    WHERE sales_id = ?";
            DB::delete($sqlQuery3, [$sales_id]);
            foreach ($sales_items as $value){
                $sqlQuery4 = "INSERT INTO Sales_items (sales_id, product_id, sales_price, qt)
                                values(?, ?, ?, ?)";
                DB::insert($sqlQuery4, [$sales_id, $value['product_id'], $value['price'], $value['qt'] ]);               
            }            
            DB::commit();            
        }
        catch(Exception $e)
        {
            DB::rollback();
            $errorCode = $e->errorInfo[1];                     
            return $e;
        }
        // return back()->with('status', 'Data successfully updated');
        // return response('status', 'Data successfully updated');
        return response()->json(['success' => 'Sale id ' . $sales_id . ' is updated successfully.']);
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
        return response()->json(['success' => 'Data is deleted successfully']);
    }
}
