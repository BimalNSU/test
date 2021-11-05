@extends('master')

@section('content_area')

<div class="row">
    <div class="col-xs-12">
        <!-- Horizontal Form -->
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Product Add</h3>
            </div>
                <!-- /.box-header -->
            <!-- form start -->
            <form action="{{url('products/')}}" method="POST">
                <div class="box-body">
                    <span id="respond_result">
                    @if(session()->get('errors'))
                        <div class="alert alert-danger">                                    
                            @foreach($errors as $value)
                                <p>{{$value}}</p>
                            @endforeach
                        </div>
                    @endif
                    @if(session()->get('success'))
                        <div class="alert alert-success">{{ session()->get('success') }}</div>
                        </span>
                    @endif                            
                    @csrf
                    <div class="row">
                        <div class="col-xs-2">
                            <div class="form-group">
                                <label>Product Name</label>
                                <input type="text" name="name" class="form-control" placeholder="product name">
                            </div>
                        </div>
                        <div class="col-xs-2">
                            <div class="form-group">
                                <label>Price</label>
                                <input type="number" name="price" class="form-control" placeholder="Price">
                            </div>
                        </div>
                        <div class="col-xs-2">
                            <div class="form-group">
                                <label>Quantity</label>
                                <input type="number" name='qt' class="form-control" placeholder="Quantity">
                            </div>
                        </div>
                    </div>                 
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <button type="submit" class="btn btn-default">clear</button>
                    <button type="submit" class="btn btn-info pull-right">add</button>
                </div>
                <!-- /.box-footer --> 
            </form>                    
        </div>
        <!-- /.box -->                
    </div>            
</div>
@endsection
