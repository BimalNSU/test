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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/','SalesController@index');
Route::get('sales/','SalesController@getSalesList');
Route::get('sales/{sales_id}','SalesController@getSalesDetails')->name('salesDetails');
Route::get('sales/{sales_id}/edit','SalesController@editPage')->name('salesEditPage');;
Route::POST('sales/','SalesController@create')->name('createSales');
Route::PUT('sales/{sales_id}','SalesController@update')->name('salesUpdate');
Route::DELETE('sales/{sales_id}','SalesController@delete')->name('salesDelete');

Route::POST('products/','ProductController@create')->name('createProduct');
Route::get('products/','ProductController@getProductList');

Route::get('products/create','ProductController@createPage');
Route::get('products/{product_id}/edit','ProductController@editPage');

Route::get('products/{product_id}','ProductController@details')->name('product_details');

Route::PUT('products/{product_id}','ProductController@update')->name('productUpdate');
Route::DELETE('products/{product_id}','ProductController@delete');