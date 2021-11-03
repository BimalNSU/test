<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/','SalesController@index');
Route::get('sales/','SalesController@getSalesList');
// Route::get('sales/{sales_id}','SalesController@details');
Route::get('sales/{sales_id}/view','SalesController@getSalesView')->name('salesView');
Route::get('sales/{sales_id}/edit','SalesController@getSalesEdit')->name('salesEdit');
Route::POST('sales/','SalesController@create')->name('createSales');
Route::PUT('sales/{sales_id}','SalesController@edit')->name('salesUpdate');
Route::DELETE('sales/{sales_id}','SalesController@delete')->name('salesDelete');

Route::POST('products/','ProductController@create');             // rest api
Route::POST('products/create','ProductController@createPage');  // web api
Route::get('products/','ProductController@getProductList');     // web & rest api
Route::get('product/{product_id}','ProductController@details')->name('product_details');     // rest api
Route::get('products/{product_id}/view','ProductController@details')->name('productView');   //web api
Route::get('products/{product_id}/edit','ProductController@editPage')->name('productEdit');  //web api
Route::PUT('products/{product_id}','ProductController@update')->name('productUpdate');      // rest api
Route::DELETE('products/{product_id}','ProductController@delete')->name('productDelete');   // rest api