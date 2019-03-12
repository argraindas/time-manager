@extends('layouts.dashboard')

@section('title', 'Logged times')

@section('content')

    <div class="row">
        <ul>
            @foreach ($categories as $category)
                <li>{{ $category->name }}</li>
            @endforeach
        </ul>
    </div>

@endsection
