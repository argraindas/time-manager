@extends('layouts.dashboard')

@section('title', 'Add record')

@section('content')

    <div class="row">
        <div class="col-md-6">
            <form method="post" action="{{ route('records.store') }}">

                <div class="form-group">
                    <label for="category">Category</label>
                    <select class="form-control" name="category" id="category">
                        <option>aa</option>
                        <option>bb</option>
                        <option>cc</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <input type="text" class="form-control" name="description" id="description"
                           placeholder="Describe your activity">
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
