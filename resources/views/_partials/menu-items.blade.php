<ul class="nav flex-column">
    <li class="nav-item">
        <a class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
            <i class="material-icons">dashboard</i>
            Dashboard
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ Request::is('categories') ? 'active' : '' }}" href="{{ route('categories') }}">
            <i class="material-icons">storage</i>
            Categories
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ Request::is('records') ? 'active' : '' }}" href="{{ route('records') }}">
            <i class="material-icons">timer</i>
            Records
        </a>
    </li>
</ul>
