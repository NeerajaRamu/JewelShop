@extends('layouts.app')
@include('layouts.sidebar')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">

                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    <table class="table table-striped">
  <thead>
    <tr>
      <th>Username</th>
      <th>Email</th>
      <th>Role</th>
      <th>Region</th>
      <th>Status</th>
    </tr>
  </thead>
  <tbody>
    @foreach($userData as $val)
        <tr>
          <td>{{$val['name']}}</td>
          <td>{{$val['email']}}</td>
          <td>{{$val['name']}}</td>
          <td>{{$val['name']}}</td>
          <td>{{$val['name']}}</td>
        </tr>
    @endforeach
  </tbody>
</table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

