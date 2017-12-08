@extends('layouts.app')
@include('layouts.sidebar')
@section('content')
<div class="container">
    <div class="row wrapper border-bottom white-bg page-heading right">
        <div class="col-md-8 col-md-offset-2">
            <h2>Clock-Out Details: </h2>
            <ol class="breadcrumb">
                <li><a href="{{ route('clock-out') }}">Clock-Out</a></li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('profile.updateLogs') }}">
                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="timeIn" class="col-md-4 control-label">Time In</label>
                            <div class="col-md-6">
                                <input id="timeIn" type="" class="form-control" name="timeIn" value= "{{ $timeIn }}" required READONLY>
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="timeIn" class="col-md-4 control-label">Time Out</label>
                            <div class="col-md-6">
                                <input id="timeOut" type="" class="form-control" name="timeOut" value="{{ $timeOut }}" required READONLY>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="totalHours" class="col-md-4 control-label">Total Hours</label>

                            <div class="col-md-6">
                                <input id="totalHours" type="" class="form-control" name="totalHours" value="{{ $timeSpent }}" required>
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="totalAmount" class="col-md-4 control-label">Total Amount (in INR)</label>
                            <div class="col-md-6">
                                <input id="totalAmount" type="" class="form-control" name="totalAmount" value="{{ $totalAmount }}" required>
                            </div>
                        </div>

                         <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="totalGold" class="col-md-4 control-label">Total Gold ( in gms)</label>
                            <div class="col-md-6">
                                <input id="totalGold" type="" class="form-control" name="totalGold" value="{{ $totalGold }}" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Save
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
