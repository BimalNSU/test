@extends('master')
@section('title_area') 
	 Product List
 @endsection() 
@section('content_area')
<div class="row">
    <div class="col-xs-12">
        <div class="box box-default">
            <div class="box-header with-border">
            </div>
            <div class="box-body">
                <a href="{{ url('products/create') }}">
                    <button type="button" class="btn btn-success mb-1" ><i class="fa fa-plus"></i></button>
                </a>              
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
        <div class="box box-default">
            <div class="box-header with-border">
        </div>
        <div class="box-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>price</th>
                        <th>Quantity</th>
                        <th>action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>pizza</td>
                        <td>300</td>
                        <td>2</td>
                        <td>bt</td>
                    </tr>
                    <tr>
                        <td>pizza2</td>
                        <td>200</td>
                        <td>4</td>
                        <td>bt</td>
                    </tr>                   
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('script_area')

@endsection