@extends('layouts.app')
@include('layouts.sidebar')
@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.bundle.js"></script>
<div style="width:50%;position: absolute;right: 400px;">
    {!! $chartjs->render() !!}
</div>
@endsection