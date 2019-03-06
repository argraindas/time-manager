@extends('layouts.dashboard')

@section('title', 'Dashboard')

@section('content')

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">

                    Hello {{ Auth::user()->name }}! This is your dashboard.

                </div>
            </div>
        </div>
    </div>

@endsection
