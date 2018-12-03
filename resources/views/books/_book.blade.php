<div class='book cf'>
    <img class='cover' src='{{ $book->cover_url }}' alt='Cover image of the book'>
    <ul>
        <li>by {{$book->author}}</li>
        <li>Added: {{$book->created_at->format('m/d/y g:ia')}}</li>
    </ul>
    <a href='/books/{{$book->id}}'>View</a>
    <a href='/books/{{$book->id}}/edit'>Edit</a>
    <a href='/books/{{$book->id}}/delete-do-it'>Delete</a>
</div>