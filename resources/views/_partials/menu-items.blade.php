<ul class="nav flex-column">
    <li class="nav-item">
        <a class="nav-link {{ Route::is('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
            <i class="material-icons">dashboard</i>
            Dashboard
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ Route::is('cards') ? 'active' : '' }}" href="{{ route('cards') }}">
            <i class="material-icons">view_carousel</i>
            Cards
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ Route::is('categories') ? 'active' : '' }}" href="{{ route('categories') }}">
            <i class="material-icons">widgets</i>
            Categories
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ Route::is('records') ? 'active' : '' }}" href="{{ route('records') }}">
            <i class="material-icons">timer</i>
            Records
        </a>
    </li>
</ul>
