@extends('layouts.dashboard')

@section('title', 'Categories')

@section('content')

    <div class="row">
        <ul>
            @foreach ($categories as $category)
                <li>{{ $category->name }}</li>
            @endforeach
        </ul>
    </div>

@endsection
