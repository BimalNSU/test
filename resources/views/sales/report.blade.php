@extends('master')

@section('content_area')
<div class="row">
    <div class="col-xs-12">
        <div class="box box-default">
            <div class="box-header with-border">Sales List</div>
            <div class="box-body">
            <table id="sales_table" class="table table-bordered">
                    <thead>
                        <tr>
                            <th width="5%">SL</th>
                            <th width="15%">Customer</th>
                            <th width="10%">Phone</th>
                            <th width="25%">Address</th>
                            <th width="10%">Total</th>
                            <th width="10%">Paid</th>
                            <th width="10%">Due</th>
                            <th width="7%">action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $value)
                            <tr>                                
                                <td>1</td>
                                <td>{{$value['customer']}}</td>
                                <td>{{$value['phone']}}</td>
                                <td>{{$value['address']}}</td>
                                <td>{{$value['total']}}</td>
                                <td>{{$value['paid']}}</td>
                                <td>{{$value['total'] - $value['paid']}}</td>
                                <td>
                                    <a href="{{route('salesView',$value['sales_id'])}}">
                                        <span><i class="fa fa-fw fa-eye"></i><span>
                                    </a>
                                    <a href="{{route('salesEdit',$value['sales_id'])}}">
                                        <i class="fa fa-fw fa-edit"></i>
                                    </a>
                                    <a href="" id="deletebt" value="{{$value['sales_id']}}">
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
</div>
@endsection
@section('script_area')
<script>
    $("#deletebt").click(function(){
        // add items in dropdown list
        let sales_id = $(this).attr("value");        
        $.ajax({
            url: "{{ route('salesDelete', ['sales_id' => '']) }}/" + sales_id,
            // url: "{{ route('salesDelete', [':sales_id']) }}".replace(':sales_id', sales_id),
            method: 'DELETE',
            success: function(response) {
                alert(response);
            }
        });

    });
</script>
@endsection