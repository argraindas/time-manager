@extends('layouts.dashboard')

@section('title', 'Add record')

@section('content')

    <div class="row">
        <div class="col-md-6">
            <form method="post" action="{{ route('records.store') }}">

                @csrf

                <div class="form-group">
                    <label for="category">Category</label>
                    <select class="form-control" name="category_id" id="category_id">
                        <option value="">-- Please select --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <input type="text" class="form-control" name="description" id="description" value="{{ old('description') }}">
                </div>

                <div class="form-group">
                    <label for="time_start">Start time</label>
                    <input type="text" class="form-control" name="time_start" id="time_start" value="{{ old('time_start') }}">
                </div>

                <div class="form-group">
                    <label for="time_end">End time</label>
                    <input type="text" class="form-control" name="time_end" id="time_end" value="{{ old('time_end') }}">
                </div>

                <button type="submit" class="btn btn-primary">Add</button>

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
