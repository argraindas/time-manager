@extends('layouts.dashboard')

@section('title', 'Records')

@section('content')

    <div class="row">
        <div class="col-4">
            <div class="list-group" id="list-tab" role="tablist">
                @foreach ($records as $record)
                    <a class="list-group-item list-group-item-action" id="list-home-list" data-toggle="list" href="#list-home" role="tab" aria-controls="home">
                        {{ $record->description }}
                    </a>
                @endforeach

            </div>
        </div>
        {{--<div class="col-8">--}}
            {{--<div class="tab-content" id="nav-tabContent">--}}
                {{--<div class="tab-pane fade show active" id="list-home" role="tabpanel" aria-labelledby="list-home-list">aaa</div>--}}
                {{--<div class="tab-pane fade" id="list-profile" role="tabpanel" aria-labelledby="list-profile-list">bbb</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    </div>

@endsection
