@extends('layouts.dashboard')

@section('title', 'Cards')

@section('content')

    <cards :cards="{{ $cardsResource }}"></cards>

@endsection
