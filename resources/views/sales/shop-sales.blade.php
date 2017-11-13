@extends('layouts.app')
@include('layouts.sidebar')
@section('content')
<div class="container">

    <div class="row wrapper border-bottom white-bg page-heading right">
        <div class="col-md-10 col-md-offset-2">
            <h2>My Sales: </h2>
            <ol class="breadcrumb">
                <li><a href="{{ route('home') }}">Dashboard</a></li>
                <li><a href="{{ route('sales/sales') }}">Sales</a></li>
                <li class="active"><strong>Shop Sales</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-md-10 col-md-offset-2">
            <div class="panel panel-default">

                <div class="panel-body">
                    <table class="table table-striped">
    <thead>
        <tr>{{ Form::select('region_names', $regions['regions'], 'asdasdasd', ['placeholder' => 'Select Region...'])}}</tr>
      <tr>
        <th>Date</th>
        <th>User</th>
        <th>Region</th>
        <th>Total sold</th>
        <th>Total cost</th>
        <th>Hours Spent</th>
        <th colspan="2" align="center">Actions</th>
      </tr>
    </thead>
    <tbody>
      @foreach($data as $val) 
        <tr>
            <td>{{ $val->date }}</td>
            <td>{{ $val->users->name }}</td>
            <td>{{ $val->users->region->name }}</td>
            <td>{{ $val->total_gold_sold }}</td>
            <td>{{ $val->total_amount}}</td>
            <td>{{ $val->total_hours_spent}}</td>
            <td>
              <a href="{{ route('sales.edit', ['id' => $val->id]) }}" class="btn btn-primary">Edit</a>
              <td>
                <form action="{{ route('sales.destroy', ['id' => $val->id]) }}" method="post">
                    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                    <input type="hidden" name="_method" value="DELETE" >
                    <input type="submit" value="delete ">
                </form>
        </tr>
      @endforeach
    </tbody>
</table>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $( document ).ready(function() {
        $("select[name='region_names']").change(function(){
            var region_id = $(this).val();alert("Region"+region_id);
            var token = $("input[name='_token']").val();
            $.ajax({
                url: "/shopSales/"+region_id,
                method: 'GET',

//                success: function(){
//            console.log('data sent');
//        }
//                success: function(response){ // What to do if we succeed
//
//        alert(response);
//    }


              success: function(data) {alert("dsfsdfdsf"+data);
                $("select[name='region_names'").html('data');
            }
          });
        });
    });
</script>

@endsection
