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
            @if(Route::current()->getName() != 'productEdit')
            <form action="{{url('products/')}}" method="POST">
            @endif
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
                        
                    @endif
                    </span>                        
                    @csrf
                    <div class="row">
                        <div class="col-xs-2">
                            <div class="form-group">
                                <label>Product Name</label>
                                <input type="text" id='name' name="name" class="form-control" placeholder="product name" value="{{$data['name'] ?? ''}}">
                            </div>
                        </div>
                        <div class="col-xs-2">
                            <div class="form-group">
                                <label>Price</label>
                                <input type="number" id='price' name="price" class="form-control" placeholder="Price" value="{{$data['price'] ?? ''}}">
                            </div>
                        </div>
                        <div class="col-xs-2">
                            <div class="form-group">
                                <label>Quantity</label>
                                <input type="number" id ='qt' name='qt' class="form-control" placeholder="Quantity" value="{{$data['qt'] ?? ''}}">
                            </div>
                        </div>
                    </div>                 
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    @if(Route::current()->getName() == 'productView')
                    
                    @elseif(Route::current()->getName() == 'productEdit')
                        <button class="pull-right" id ="updatebt" value ="{{$data['id']}}">Update</button>
                            <!-- <i class="fa fa-fw fa-edit"></i> -->
                        <!-- <a href="route('productUpdate')" class="pull-right" id ="updatebt" value ="{{$data['id']}}">Update</button> -->
                    @else
                        <button type="submit" class="btn btn-default">clear</button>
                        <button type="submit" class="btn btn-info pull-right">add</button>
                    @endif
                </div>
                <!-- /.box-footer --> 
            @if(Route::current()->getName() != 'productEdit')            
            </form>
            @endif
        </div>
        <!-- /.box -->                
    </div>            
</div>
@endsection
@section('script_area')
<script>
    $("#updatebt").click(function(){
        let jsonObject = getProductData(); 
        let product_id = $(this).attr("value");
        let html = "";
        $.ajax({
            url: "{{ route('productUpdate', [':product_id']) }}".replace(':product_id', product_id),
            method: 'PUT',
            data: {data: JSON.stringify(jsonObject) },
            success: function(data) {
                if(data.success)
                {
                    html = '<div class="alert alert-success">' + data.success + '</div>';
                }
                else{
                    html = '<div class="alert alert-danger">';
                    data.error.forEach(function(item, index){
                        html += '<p>' + item + '</p>';
                    });
                    html += '</div>';
                }
                $('#respond_result').html(html);
            }
        });
    });

    function getProductData(){
        let jsonObject = new Object();
        jsonObject["name"] = $("#name").val();
        jsonObject["price"] = Number($("#price").val());
        jsonObject["qt"] = Number($("#qt").val());
        return jsonObject;
    }
</script>
@endsection
