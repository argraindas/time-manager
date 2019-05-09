<nav class="col-md-2 d-none d-md-block bg-light sidebar">
    <div class="sidebar-sticky">

        @if (Auth::user()->isAdmin())

            @if (! Route::is('admin.dashboard'))
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                            <i class="material-icons">exit_to_app</i>
                            Go to Administration
                        </a>
                    </li>
                </ul>
            @endif

            @yield('admin-sidebar-left')
        @endif

        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
            <span>Management</span>
        </h6>

        @include('_partials.menu-items')

    </div>
</nav>
