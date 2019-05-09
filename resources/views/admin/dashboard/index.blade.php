@extends('admin.layouts.dashboard')

@section('title', 'Administration area')

@section('admin-content')

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Administration area</div>

                <div class="card-body">

                    Welcome, {{ Auth::user()->name }}! You are in the Administration area.

                </div>
            </div>
        </div>
    </div>

@endsection
