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
    return view('home');
});


Route::group(['prefix' => 'sales'],function () {
    Route::get('create','SalesController@index');                // web api
    Route::get('/','SalesController@getSalesList');              // web & rest api
    Route::POST('/','SalesController@create');                  // rest api
    // Route::get('{sales_id}','SalesController@details');      // rest api
    Route::get('{sales_id}/view','SalesController@getSalesView')->name('salesView');  // web api
    Route::get('{sales_id}/edit','SalesController@getSalesEdit')->name('salesEdit');  // web api
    Route::PUT('{sales_id}','SalesController@edit')->name('salesUpdate');             // rest api
    Route::DELETE('{sales_id}','SalesController@delete')->name('salesDelete');        // rest api
});

Route::group(['prefix' => 'products'],function () {
    Route::get('create','ProductController@index');          // web api
    Route::get('/','ProductController@getProductList');     // web & rest api
    Route::POST('/','ProductController@create');            // rest api
    Route::get('{product_id}','ProductController@details')->name('product_details');     // rest api
    Route::get('{product_id}/view','ProductController@details')->name('productView');   //web api
    Route::get('{product_id}/edit','ProductController@editPage')->name('productEdit');  //web api
    Route::PUT('{product_id}','ProductController@update')->name('productUpdate');      // rest api
    Route::DELETE('{product_id}','ProductController@delete')->name('productDelete');   // rest api
});