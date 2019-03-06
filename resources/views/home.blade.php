@extends('layouts.main')

@section('title', 'Dashboard')

@section('content')

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">

                    @guest
                        Please login or register!
                    @else
                        Hello {{ Auth::user()->name }}!
                    @endguest

                </div>
            </div>
        </div>
    </div>

@endsection
