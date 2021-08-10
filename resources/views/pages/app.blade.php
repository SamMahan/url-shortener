@extends('layouts.primary')

@section('content')

<div class="row">

    <div class="col-md-6 col-xs-12">
        @include('components.react.url_form')
    </div>

    <div class="col-md-6 col-xs-12">
        @include('components.react.url_list')
    </div>

</div>

@endsection