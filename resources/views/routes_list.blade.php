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
                            @php
                                $routeCollection = Route::getRoutes();
                            @endphp
                            <table class="table table-striped">
                                <thead class="thead-dark">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Route</th>
                                        <th scope="col">Url</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($routeCollection as $key => $value)
                                    <tr>
                                        <th scope="row">{{ $key }}</th>
                                        <td>{{ $value->getName() }}</td>
                                        <td>{{ $value->uri() }}</td>
                                        <td>{{ $value->getActionName() }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
