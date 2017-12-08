@extends('layouts.app')
@include('layouts.sidebar')
@section('content')

<div class="container">
    <div class="row wrapper border-bottom white-bg page-heading right">
        <div class="col-md-8 col-md-offset-2">
            <h2>Create Sale: </h2>
            <ol class="breadcrumb">
                <li><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="active"><strong>Create Sale</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Sale Form</div>

                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('sales.store') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Customer Name</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

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
                                <input id="quantity" type="" class="form-control" name="ornament" value="" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="date" class="col-md-4 control-label">Sold Date</label>
                            <div class="col-md-6">
                                <input  class="form-control" type="text" placeholder="click to show datepicker" name="sold_date"  id="example1" value="{{$todaysDate}}" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="sold" class="col-md-4 control-label">Quantity Sold(in gms)</label>

                            <div class="col-md-6">
                                <input id="sold" type="" class="form-control" name="sold" value="" required >
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="cost" class="col-md-4 control-label">Gold Cost</label>

                            <div class="col-md-6">
                                <input id="cost" type="cost" class="form-control" name="cost" value="{{ $goldPrice }}" >
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="total" class="col-md-4 control-label">Total Cost</label>

                            <div class="col-md-6">
                                <input id="total" type="total" class="form-control" name="total_cost" value="" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Submit
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    $( document ).ready(function() {
           $('#sold').change(function() {
        $('[name="total_cost"]').val(parseInt($("#cost").val())*(parseInt($("#sold").val())));
     });
    });



</script>

<script>
      $(function() {
        $("#datepicker").datepicker();
      });
</script>


@endsection

