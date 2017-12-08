@extends('layouts.app')
@include('layouts.sidebar')
@section('content')
<div class="container">
    <div class="row wrapper border-bottom white-bg page-heading right">
        <div class="col-md-8 col-md-offset-2">
            <h2>Edit Sale: </h2>
            <ol class="breadcrumb">
                <li><a href="{{ route('home') }}">Dashboard</a></li>
                <li><a href="{{ route('my-sales') }}">Sales</a></li>
                <li class="active"><strong>Edit Sales</strong></li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-body">@foreach($saleData as $data)
                    <form class="form-horizontal" method="POST" action="{{ route('sales.update', ['id' => $data->id])}}">
                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Customer Name</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{ $data->customer_name }}" required autofocus>

                                @if ($errors->has('name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="quantity" class="col-md-4 control-label">Ornament name</label>

                            <div class="col-md-6">
                                <input id="quantity" type="" class="form-control" name="ornament" value="{{ $data->ornament_name }}" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="date" class="col-md-4 control-label">Sold Date</label>

                            <div class="col-md-6">
                                <input  class="form-control" type="text" placeholder="click to show datepicker" name="sold_date" value="{{ $data->sold_date }}" id="example1" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="sold" class="col-md-4 control-label">Quantity Sold (in gms)</label>

                            <div class="col-md-6">
                                <input id="sold" type="" class="form-control" name="sold" value="{{ $data->quantity_sold }}" required >
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="cost" class="col-md-4 control-label">Gold Cost (INR)</label>

                            <div class="col-md-6">
                                <input id="email" type="cost" class="form-control" name="cost" value="{{ $data->gold_cost }}" readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="total" class="col-md-4 control-label">Total Cost</label>

                            <div class="col-md-6">
                                <input id="total" type="total" class="form-control" name="total_cost" value="{{ $data->total_cost }}" readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Submit
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
