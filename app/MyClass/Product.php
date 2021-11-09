<?php
namespace App\MyClass;
use Illuminate\Support\Facades\DB;

class Product {
   function __constructor()
    {

    }
    public function create($name, $price, $qt){
        $sqlQuery = "INSERT INTO Products (name, price, qt)
                    VALUES(?, ?, ?);";
        DB::insert($sqlQuery, [$name, $price, $qt]);
    }
    public function getList()
    {
        $data = DB::select("SELECT * FROM Products");        
        $data  = json_decode(json_encode($data),true);
        return $data;
    }
    public function getIdNameList(){
        $sql = "SELECT id, name
                 FROM Products";
        $data = DB::select($sql);
        $data  = json_decode(json_encode($data),true);
        return $data;
    }
    public function getDetails($product_id){
        $sql = "SELECT *
                 FROM Products
                 where id = ?";
        $data = DB::select($sql, [$product_id]);
        $data  = json_decode(json_encode($data),true);
        if(empty($data) == false){
            return $data[0];
        }
        return $data;
    }
    public function update($arr){
        $output = $this->updateHelper($arr);
        $sqlQuery = $output[0];
        $placeHolders = $output[1];
        // return response()->json(['data'=> $sqlQuery]);
        $rowAffected = DB::update($sqlQuery, $placeHolders);            
        return $rowAffected;
    }
     public function updateHelper($product_data){
        $dynamic_query = "";        
        $flag = 0;
        $arr = array();
        // checking $product_data['name'] value exist nor not
        if(isset($product_data['name'])){
            $dynamic_query .= $this->updateHelper2($flag, "name");
            array_push($arr, $product_data['name']);
            $flag = 1;
        }
        // checking $product_data['price'] value exist nor not
        if(isset($product_data['price'])){        
            $dynamic_query .= $this->updateHelper2($flag, "price");
            array_push($arr, $product_data['price']);
            $flag = 1;
        }
        // checking $product_data['qt'] value exist nor not
        if(isset($product_data['qt'])){
            $dynamic_query .= $this->updateHelper2($flag, "qt");
            array_push($arr, $product_data['qt']);
            $flag = 1;
        }
        if($flag == 1){
            $dynamic_query .= " WHERE id = ?;";
            array_push($arr, $product_data['id']);
        }        
        return array($dynamic_query, $arr);
    }
    public function updateHelper2($flag, $field_name){
        $statement = "";
        if($flag == 0){
            $statement .= "UPDATE Products
                            SET ";
        }
        else if($flag == 1){
            $statement .= ",";
        }
        $statement .= $field_name ." = ?";
        return $statement;
    }
}