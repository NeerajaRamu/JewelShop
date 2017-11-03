@extends('layouts.app')
@include('layouts.sidebar')
@section('content')
<div class="container">
    <div class="row wrapper border-bottom white-bg page-heading right">
        <div class="col-lg-8 col-md-offset-2">
            <h2>Edit User: </h2>
            <ol class="breadcrumb">
                <li><a href="{{ route('home') }}">Dashboard</a></li>
                <li><a href="{{ route('users') }}">Users</a></li>
                <li class="active"><strong>Edit User</strong></li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">


                <div class="panel-body">
                    @foreach($user as $val)
<!--                    {{ route('users.edit', 'Supervisor') }}-->
                    <form class="form-horizontal" method="POST" action="{{ route('users.update', ['id' => $val->id]) }}">
                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">


                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Name</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{ $val->name }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="date" class="col-md-4 control-label">Email Id</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="email" value="{{ $val->email }}" required autofocus>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="quantity" class="col-md-4 control-label">Password</label>

                            <div class="col-md-6">
                                <input id="quantity" type="password" class="form-control" name="password" value="" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="quantity" class="col-md-4 control-label">Confirm Password</label>

                            <div class="col-md-6">
                                <input id="quantity" type="password" class="form-control" name="confirm_password" value="" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="sold" class="col-md-4 control-label">Role</label>

                            <div class="col-md-6">
                                <select class="form-control m-bot15" name="role_id">
                                    @if ($roles->count())

                                    @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ $val->role_id == $role->id ? 'selected="selected"' : '' }}>{{ $role->name }}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="cost" class="col-md-4 control-label">Region</label>

                            <div class="col-md-6">
                                <select class="form-control m-bot15" name="region_id">
                                    @if ($regions->count())

                                    @foreach($regions as $region)
                                    <option value="{{ $region->id }}" {{ $val->region_id == $region->id ? 'selected="selected"' : '' }}>{{ $region->name }}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                        @endforeach
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Update User
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