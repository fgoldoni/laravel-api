@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if (Auth::user()->hasRole(\App\Flag::ROLE_ADMIN))
                    <ul class="list-group">
                        <li class="list-group-item">
                            <a href="{{ url('telescope/requests') }}">
                                Telescope
                            </a>
                        </li>
                        <li class="list-group-item">
                            <a href="{{ url('log-viewer') }}">
                                Logs
                            </a>
                        </li>
                        <li class="list-group-item">
                            <a href="{{ route('routes.list') }}">
                                Route List
                            </a>
                        </li>
                    </ul>
                   @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
