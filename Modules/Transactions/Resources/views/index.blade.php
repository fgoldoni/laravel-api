@extends('layouts.default')
@section('title')
    <title> {{ __('menus.list_of_transactions') }} - {{ config('app.name') }} </title>
@stop
@section('description')
    <meta name="description" content="{{ __('menus.list_of_transactions') }} - {{ config('app.name') }}">
@stop
@section('page-header-content')
    <div class="page-header-content header-elements-md-inline">
        <div class="page-title">
            <h5><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">{{ config('app.name', 'Theme4dev') }}</span> - @lang('menus.list_of_transactions')</h5>
        </div>
        <div class="header-elements text-center">
            <a href="{{ route('user.templates') . '#/edit/0' }}" class="btn bg-purple-800 btn-labeled btn-labeled-left" role="button">
                <b><i class="icon-upload7"></i></b>
                @lang('messages.upload')
            </a>
        </div>
    </div>
@stop
@section('vue-js')
    <script src="{{ asset('js/modules/admin/transactions/transactions.js') }}"></script>
@stop
@section('content')
    <!-- Basic card -->
    <div class="content" id="transactionsComponent">
        <router-view></router-view>
    </div>
@stop
