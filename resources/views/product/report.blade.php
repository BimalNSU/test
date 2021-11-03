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
                        <th width = "20%">Item</th>
                        <th width = "10%">price</th>
                        <th width = "10%">Quantity</th>
                        <th width = "2%">action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $value)
                        <tr>                                
                            <td>{{$value['name']}}</td>
                            <td>{{$value['price']}}</td>
                            <td>{{$value['qt']}}</td>                            
                            <td>
                                <a href="{{route('productView',$value['id'])}}">
                                    <span><i class="fa fa-fw fa-eye"></i><span>
                                </a>
                                <a href="{{route('productEdit',$value['id'])}}">
                                    <i class="fa fa-fw fa-edit"></i>
                                </a>
                                <a href="" id="deletebt" value="{{$value['id']}}">
                                    <i class="fa fa-fw fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach                           
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('script_area')

@endsection