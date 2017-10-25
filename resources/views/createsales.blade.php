@extends('layouts.app')
@include('layouts.sidebar')
@section('content')
<html>
<head>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<!--<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css" rel="stylesheet">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script>-->

</head>
<body>


        <script type="text/javascript">
            // When the document is ready
            $(document).ready(function () {

                $('#example1').datetimepicker({
                    format: "dd/mm/yyyy"
                });

            });
        </script>

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
                    <form class="form-horizontal" method="POST" action="">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Name</label>

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
                            <label for="date" class="col-md-4 control-label">Sold Dateeee</label><div class="col-md-6">
<input  class="form-control" type="text" placeholder="click to show datepicker"  id="example1">


                            </div>
                        </div>
                           <div class="form-group">
<!--                            {!! Form::label('soldDate', 'Email', ['class' => 'col-sm-2 control-label']) !!}-->
                            <label for="date" class="col-md-4 control-label">Sold Date</label>

                            <div class="col-md-6">
                                <input  class="form-control" type="text" placeholder="click to show datepicker"  id="example1">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="quantity" class="col-md-4 control-label">Ornament name</label>

                            <div class="col-md-6">
                                <input id="quantity" type="" class="form-control" name="quantity" value="" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="quantity" class="col-md-4 control-label">Comment's</label>

                            <div class="col-md-6">
                                <input id="quantity" type="" class="form-control" name="quantity" value="" required>
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
                                <input id="email" type="cost" class="form-control" name="cost" value="" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="total" class="col-md-4 control-label">Total Cost</label>

                            <div class="col-md-6">
                                <input id="total" type="total" class="form-control" name="total" value="" required>
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
 <script type="text/javascript">
            // When the document is ready
            $(document).ready(function () {

                $('#example1').datepicker({
                    format: "dd/mm/yyyy"
                });

            });
        </script>
         </body>
</html>
@endsection
