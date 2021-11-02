@extends('master')

@section('content_area')
<div class="row">
    <div class="col-xs-12">
        <div class="box box-default">
            <div class="box-header with-border">Sales Header</div>
            <div class="box-body">
                <div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label">Customer Name</label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="customerName" placeholder="Name" value="{{$data[0]['customer_name'] ?? ''}}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputPhone" class="col-sm-2 control-label" >Customer Phone</label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="customerPhone" placeholder="Phone" value="{{$data[0]['customer_phone'] ?? ''}}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputAddress" class="col-sm-2 control-label">Customer Address</label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="customerAddress" placeholder="Address" value="{{$data[0]['customer_address'] ?? ''}}">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
        <div class="box box-default">
            <div class="box-header with-border">Sales Items
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-xs-4">
                        <div class="form-group">
                            <label>Item</label>
                            <select class="form-control select2" style="width: 100%;">
                                <option selected="selected">...</option>
                                @foreach($product_list as $value)
                                    <option id ="{{$value['id']}}">{{$value['name']}}</option>
                                @endforeach
                            </select>
                        </div>                                           
                    </div>
                    <div class="col-xs-2">
                        <div class="form-group">
                            <label>Price</label>
                            <input type="text" id='product_price' class="form-control" placeholder="Price">
                        </div>
                    </div>
                    <div class="col-xs-2">
                        <div class="form-group">
                            <label>Quantity</label>
                            <input type="text" id='product_qt' class="form-control" placeholder="Quantity">
                        </div>
                    </div>
                    <div class="col-xs-2">
                        <div class="form-group">
                            <label>Total</label>
                            <label type="text" id='product_total' class="form-control" placeholder="Total">
                        </div>
                    </div>
                    <div class="col-xs-2">
                        <button type="button" id ="addRow" class="btn btn-success mb-1" ><i class="fa fa-plus"></i></button>                    
                        <button id ="clearbt">clear all</button>
                    </div>

                </div>
                <table id="sales_table" class="table table-bordered">
                    <thead>
                        <tr>
                            <th width="40%">Item</th>
                            <th width="10%">price</th>
                            <th width="10%">Quantity</th>
                            <th width="20%">total</th>
                            <th width="5%">action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if(@isset($data[1]))
                    @foreach($data[1] as $value)
                        <tr>
                            <td id="{{$value['product_id']}}">{{$value['name']}}</td>
                            <td>{{$value['sales_price']}}</td>
                            <td>{{$value['qt']}}</td>
                            <td>{{$value['sales_price']*$value['qt']}}</td>
                            <td><button class = "deletebt">delete</button></td>
                        </tr>
                    @endforeach
                    @endif             
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
        <div class="box box-default">
            <div class="box-header with-border">Sales Summary</div>
            <div class="box-body">
                <div class="row form-group">
                    <label for="inputTotal" class="col-sm-2 control-label">Total Price</label>

                    <div class="col-sm-5">
                        <label id = "netTotal" class="form-control" >
                    </div>
                </div>
                <div class="row form-group">
                    <label for="inputPaid" class="col-sm-2 control-label">Paid</label>

                    <div class="row col-sm-5">
                        <input type="text" class="form-control" id = "inputPaid" value="{{$data[0]['paid'] ?? '0'}}">
                    </div>
                </div>
                <div class="row form-group">
                    <label for="due" class="col-sm-2 control-label">Due</label>

                    <div class="col-sm-5">
                        <label id="due" class="form-control" ></label>
                    </div>
                </div>
                <button class="pull-right" id ="createbt">Create</button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script_area')
<script>
    let netTotal = 0;
    $("#addRow").click(function(){
        let item_ref = $(".select2 :selected");
        let product_id = item_ref.attr("id");
        let name = item_ref.text();
        let price = $("#product_price").val();
        let qt = $("#product_qt").val();
        let total = $("#product_total").text();
  	    var row = '<tr>'+
                  '<td id="'+product_id+'">'+name+'</td>'+
                  '<td>'+price+'</td>'+
                  '<td>'+qt+'</td>'+
                  '<td>'+total+'</td>'+
                  '<td> <button class='+'"deletebt"'+'>delete</button></td></tr>';
        $("#sales_table tbody").append(row);   // add new item in table             
        updateSalesSummary();
    });

    //auto update due amount in each input in paid field
    document.getElementById("inputPaid").oninput = function(){
        let due = netTotal - Number($("#inputPaid").val() );
        $("#due").text(due);       
    };

    $("#createbt").click(function(){
        var jsonObject = new Object();
        jsonObject["customerName"] = $("#customerName").val();
        jsonObject["customerPhone"] = $("#customerPhone").val();
        jsonObject["customerAddress"] = $("#customerAddress").val();
        jsonObject["paid"] = Number($("#inputPaid").val());
        
        var sales_items = new Array();
        $("#sales_table tbody tr").each(function(){
            let row = $(this).find("td");            
            let product_id = Number(row.attr("id"));
            let price = Number(row[1].textContent);
            let qt = Number(row[2].textContent);
            sales_items.push({
                "product_id": product_id,
                "price": price,
                "qt": qt
            });            
        });        
        jsonObject['sales_items']= sales_items;
        $.post("{{ route('createSales') }}", {data: JSON.stringify(jsonObject) } , function(data){                 
                if(data.success)
                {
                    // console.log(data); 
                }                    
            });         
        // delete all rows        
        $("#sales_table > tbody").empty();
    });
    $("#sales_table tbody").on("click", ".deletebt", function() {
        //remove specific item(i.e row) from table
        $(this).closest("tr").remove();
        updateSalesSummary();
    });
    
    $("#clearbt").click(function(){
        // delete all rows
        $("#sales_table > tbody").empty();
        updateSalesSummary();
    });

    $("select option").click(function(){
        // add items in dropdown list
        let product_id = $(this).attr("id");
        $.ajax({
            url: "{{ route('product_details', [':product_id']) }}".replace(':product_id', product_id),
            method: 'GET',
            success: function(response) {
                let price = Number(response['price']);
                let qt = Number(response['qt']);
                let total = price*qt;
                $("#product_price").val(price);
                $("#product_qt").val(qt);
                $("#product_total").text(total);
            }
        });

        // $("select .select2").text("test");
    });

    function updateSalesSummary(){
        netTotal = 0;        
        $("#sales_table tbody tr").each(function(){            
            netTotal = netTotal + Number($(this).find("td")[3].textContent);
        });
        $("#netTotal").text(netTotal);
        $("#inputPaid").text(0);
        let due = netTotal - Number($("#inputDue").text());
        $("#due").text(due);
    }

    //auto update total amount before adding to table
    document.getElementById("product_price").oninput = function(){
        let price = $(this).val();
        let qt = $("#product_qt").val();
        $("#product_total").text(price*qt);
    };
    //auto update total amount before adding to table
    document.getElementById("product_qt").oninput = function(){
        let price = $("#product_price").val();
        let qt = $(this).val();
        $("#product_total").text(price*qt);
    };

</script>

@endsection