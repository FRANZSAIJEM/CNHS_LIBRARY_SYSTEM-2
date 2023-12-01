
<!DOCTYPE html>
<html>
<head>

</head>
<body>
    <div class="">
        <div class="">
            <div class="flex flex-wrap">
                {{-- Button to export PDF --}}
                <form action="{{ route('generate-pdf') }}" method="post" target="_blank">
                    @csrf
                    <button type="submit" class="btn btn-primary">Export PDF</button>
                </form>
            </div>
            <br>
            <h1><b>All Students who borrowed books</b></h1>

            {{-- Display the list of students who borrowed books --}}
            <ul>
                @foreach($usersWithAcceptedRequests as $user)
                    <li>
                        {{ $user->name }} - Borrowed Count: {{ $user->borrowed_count }}
                        {{-- Add other user information or customize as needed --}}
                    </li>
                @endforeach
            </ul>
             <br>
             <b>Most Grade Level Borrowed Books:</b>
            @foreach($gradeLevelCounts as $gradeLevel => $count)
                <li> Grade {{ $gradeLevel }} - Borrowed Count: {{ $count }}</li>
            @endforeach
             <br>
            <h1><b>Total Books:</b> {{ $allBooksCount }}</h1> <br>
            <h1><b>Total Available Books:</b> {{ $availableBooksCount }}</h1>
            <h1><b>Total Not Available Books:</b> {{ $notAvailableBooksCount }}</h1>
            <br>
            <h1><b>Total Good Books:</b> {{ $goodBooksCount }}</h1>
            <h1><b>Total Damage Books:</b> {{ $damageBooksCount }}</h1>
            <h1><b>Total Lost Books:</b> {{ $lostBooksCount }}</h1>
            <br>

            <h1><b>Most Borrowed Book</b></h1>
            @if ($mostBorrowedBooks->isNotEmpty())
            <ul>
                @foreach ($mostBorrowedBooks as $book)
                    @if ($book->count > 0)
                        <li class="mb-3">
                            <p><b>Title:</b> {{ $book->title }}</p>
                            <p><b>Author:</b> {{ $book->author }}</p>
                            <p><b>Count:</b> {{ $book->count }}</p>
                        </li>
                    @endif
                @endforeach
            </ul>
            @else
            <p>No books available.</p>
            @endif


        </div>
    </div>
</body>
</html>
