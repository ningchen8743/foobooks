@extends('layouts.master')

@section('title')
    All books
@endsection

@section('content')

    <h1>All books</h1>

    @foreach($books as $book)
        {{ $book->title }}
        @include('books._book')
    @endforeach

@endsection