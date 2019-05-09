@extends('layouts.dashboard')

@section('admin-sidebar-left')

    <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
        <span>Admin</span>
    </h6>

    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link {{ Route::is('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                <i class="material-icons">dashboard</i>
                Dashboard
            </a>
        </li>
    </ul>

@endsection

@section('content')
    <div class="admin-content">
        @yield('admin-content')
    </div>
@endsection
