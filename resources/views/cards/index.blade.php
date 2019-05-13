@extends('layouts.dashboard')

@section('title', 'Cards')

@section('content')

    <div class="row">


        <cards></cards>

        <div class="col-md-3">
            <div class="card mb-4 shadow-sm">
                <div class="card-header">
                    <h4 class="mb-0">Title</h4>
                </div>
                <div class="card-body">
                    <div class="text-muted">Description</div>
                    <ul class="list-unstyled mt-3">
                        <li class="my-1">
                            <div class="custom-control custom-checkbox mr-sm-2">
                                <input type="checkbox" class="custom-control-input" id="1">
                                <label class="custom-control-label" for="1">Item 1</label>
                            </div>
                        </li>
                        <li class="my-1">
                            <div class="custom-control custom-checkbox mr-sm-2">
                                <input type="checkbox" class="custom-control-input" id="2">
                                <label class="custom-control-label" for="2">Item 2</label>
                            </div>
                        </li>
                        <li class="my-1">
                            <div class="custom-control custom-checkbox mr-sm-2">
                                <input type="checkbox" class="custom-control-input" id="3">
                                <label class="custom-control-label" for="3">Item 3</label>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card mb-4 shadow-sm">
                <div class="card-header">
                    <h4 class="mb-0">Title</h4>
                </div>
                <div class="card-body">
                    <div class="text-muted">Description</div>
                    <ul class="list-unstyled mt-3">
                        <li class="my-1">
                            <div class="custom-control custom-checkbox mr-sm-2">
                                <input type="checkbox" class="custom-control-input" id="4">
                                <label class="custom-control-label" for="4">Item 4</label>
                            </div>
                        </li>
                        <li class="my-1">
                            <div class="custom-control custom-checkbox mr-sm-2">
                                <input type="checkbox" class="custom-control-input" id="5">
                                <label class="custom-control-label" for="5">Item 5</label>
                            </div>
                        </li>
                        <li class="my-1">
                            <div class="custom-control custom-checkbox mr-sm-2">
                                <input type="checkbox" class="custom-control-input" id="6">
                                <label class="custom-control-label" for="6">Item 6</label>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

    </div>

@endsection
