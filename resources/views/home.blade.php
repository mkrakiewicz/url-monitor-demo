@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        {{ __('You are logged in!') }}
                        {{--                    <div id="example"></div>--}}

                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center my-2">
            <div class="col-md-8">
                <example></example>
            </div>
        </div>
        <div class="row justify-content-center my-2">
            <div class="col-md-8">
                <url-viewer user="@json($user)"></url-viewer>
            </div>
        </div>
    </div>
@endsection
