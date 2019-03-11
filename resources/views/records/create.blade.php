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
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <input type="text" class="form-control" name="description" id="description">
                </div>

                <div class="form-group">
                    <label for="time_start">Start time</label>
                    <input type="text" class="form-control" name="time_start" id="time_start">
                </div>

                <div class="form-group">
                    <label for="time_end">End time</label>
                    <input type="text" class="form-control" name="time_end" id="time_end">
                </div>

                <button type="submit" class="btn btn-primary">Add</button>

            </form>
        </div>
    </div>

@endsection
