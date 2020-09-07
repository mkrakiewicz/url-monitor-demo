@extends('layouts.app')

@section('content')
    <div class="container">
        @if (session('status'))
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                </div>
            </div>
        @endif

        <div class="row justify-content-center my-2">
            <div class="col-md-10">
                <div class="row mb-5">
                    <div class="col-md">
                        <h1>Your sites</h1>
                    </div>
                    <div class="col-md-4 text-right">
                        <div class="alert alert-info">
                           <i class="fas fa-info"></i>&nbsp; Showing stats for last <span class=" ary">{{ config('url-monitor.index.last-stats-minutes') }} minutes </span>
                        </div>
                    </div>
                </div>
                <Dashboard data-user='@json($user)'></Dashboard>
            </div>
        </div>
    </div>
@endsection
