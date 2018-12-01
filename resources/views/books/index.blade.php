@extends('layouts.master')

@section('title')
    All books
@endsection

@section('content')

    @if(session('alert'))
        <div class='alert'>{{ session('alert') }}</div>
    @endif

    <h1>All books</h1>

    @foreach($books as $book)
        {{ $book->title }}
        @include('books._book')
    @endforeach

@endsection