@extends('layouts.app')

@section('content')
    <div class="container">
        @if (session('status'))
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                </div>
            </div>
        @endif

        <div class="row justify-content-center my-2">
            <div class="col-md-8">
                <url-viewer user="@json($user)"></url-viewer>
            </div>
        </div>
    </div>
@endsection
