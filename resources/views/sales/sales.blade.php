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
                <li class="active"><strong>My Sales</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-md-10 col-md-offset-2">
            <div class="panel panel-default">

                <div class="panel-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Item name</th>
                                <th>Quantity</th>
                                <th>Gold Cost</th>
                                <th>Total cost</th>
                                <th>Date</th>
                                <th colspan="2" align="center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if  (count($sales) > 0)
                                @foreach ($sales as $val)
                                <tr>
                                    <td>{{ $val->customer_name }}</td>
                                    <td>{{ $val->ornament_name }}</td>
                                    <td>{{ $val->quantity_sold }}</td>
                                    <td>{{ $val->gold_cost }}</td>
                                    <td>{{ $val->total_cost}}</td>
                                    <td>{{ $val->sold_date }}</td>
                                    <td>
                                        <a href="{{ route('sales.edit', ['id' => $val->id]) }}" class="btn btn-primary">Edit</a>
                                    <td>
                                        <form action="{{ route('sales.destroy', ['id' => $val->id])}}" method="post">
                                            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                                            <input type="hidden" name="_method" value="DELETE" >
                                            <input type="submit" value="delete " >
                                        </form>
                                </tr>
                                @endforeach
                            @else
                                <td colspan="8" align="center">{{ "No Sales" }}</td>
                            @endif
                        </tbody>
                    </table>
                    {!! $sales->render() !!}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
