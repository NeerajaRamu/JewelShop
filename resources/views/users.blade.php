@extends('layouts.app')
@include('layouts.sidebar')
@section('content')
<div class="container">
    <div class="row wrapper border-bottom white-bg page-heading right">
        <div class="col-md-10 col-md-offset-2">
            <h2>All Users: </h2>
            <ol class="breadcrumb">
                <li><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="active"><strong>Users</li>
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
                                <th>Username</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Region</th>
                                <th>Status</th>
                                @if (\Auth::User()->name == "Supervisor")
                                <th colspan="2" align="center">Actions</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($userData as $val)
                            <tr>
                                <td>{{ $val->name }}</td>
                                <td>{{ $val->email }}</td>
                                <td>{{ $val->role->name }}</td>
                                <td>{{ $val->region->name }}</td>
                                <td>{{ ($val->status == '1')?'active':'inactive' }}</td>
                                @if (\Auth::User()->name == "Supervisor")
                                <td>
                                    <a href="{{ route('users.edit', ['id' => $val->id]) }}" class="btn btn-primary">Edit</a>
                                </td>
                                <td>
                                    <a href="{{ route('users.destroy', $val->id) }}" class="btn btn-info">Delete</a>
                                </td>
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {!! $userData->render() !!}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

