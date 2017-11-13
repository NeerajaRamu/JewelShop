@extends('layouts.app')
@include('layouts.sidebar')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">

                <div class="panel-heading">My Profile </div>




                <div class="panel-body">
                    <table class="table table-striped table-inverse">
  <dl class="dl-horizontal">
    @foreach($userData as $val)
  <dt>Name</dt>
  <dd>{{$val['name']}}</dd>
  <dt>Email</dt>
  <dd>{{$val['email']}}</dd>
  <dt>Role</dt>
  <dd>{{$userRole->name}}</dd>
  <dt>Region</dt>
  <dd>{{$userRegion->name}}</dd>
  @endforeach
</dl>
                        <a href="{{ route('users.edit', ['id' => $val->id]) }}" class="btn btn-primary">Edit</a>

</table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

