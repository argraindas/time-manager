@extends('layouts.dashboard')

@section('title', 'Add category')

@section('content')

    <div class="row">
        <div class="col-md-6">
            <form method="post" action="{{ route('categories.store') }}">

                @csrf

                <div class="form-group">
                    <label for="name">Category name</label>
                    <input type="text" class="form-control" name="name" id="name" value="{{ old('name') }}">
                </div>

                <button type="submit" class="btn btn-primary">Create</button>

                @if (count($errors))
                    <ul class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif

            </form>
        </div>
    </div>

@endsection
