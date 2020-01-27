@extends('layouts.default')
@section('title')
    <title> {{ __('Paypal') }} - {{ config('app.name') }} </title>
@stop
@section('description')
@stop
@section('seo')
    {!! SEO::generate() !!}
@stop
@section('css')
    <link href="{{ asset('css/modules/user/templates/template.css') }}" rel="stylesheet" type="text/css">
@stop
@section('vue-js')
@stop

@section('page-header-content')
    <div class="page-header-content header-elements-md-inline">
        <div class="page-title">
            <h5><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">{{ config('app.name', 'Theme4dev') }}</span> -  {{ __('Paypal') }}</h5>
        </div>
        <div class="header-elements text-center">
            <a href="{{ route('user.templates') . '#/edit/0' }}" class="btn bg-purple-400 btn-labeled btn-labeled-left shadow" role="button">
                <b><i class="icon-upload7"></i></b>
                UPLOAD YOUR ITEM
            </a>
        </div>
    </div>
@stop

@section('content')
    <div class="content" id="paypalComponent">
        <!-- Top alignment -->
        <div class="card text-center" style="min-height: 350px;">
            <div class="card-body">
                <i class="icon-checkmark3 icon-2x text-success border-success border-3 rounded-round p-3 mb-3"></i>
                <h5 class="card-title">Success</h5>
                <p class="mb-3">Payment perform successfully</p>

                <a href="#" class="btn bg-success">Download <i class="icon-download7 ml-2"></i></a>
            </div>
        </div>
        <!-- /top alignment -->
    </div>
@stop
