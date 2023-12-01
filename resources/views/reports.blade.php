<x-app-layout>
    <x-slot name="header" >
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <h2 class="rounded-md shadow-md bg-white dark:bg-dark-eval-1 p-3 text-xl font-semibold leading-tight">
                <i class="fa-regular fa-file-lines"></i> {{ __('Reports') }}
            </h2>
        </div>
    </x-slot>

    <div class="p-6 overflow-hidden bg-white rounded-md shadow-md dark:bg-dark-eval-1">
        <div style="display: grid; place-items: center;">
            @if(session('success'))
               <div class="success-message-container">
                   <div class="success-message bg-white rounded-lg shadow-md text-green-700 p-4">
                    <span class="rounded-full p-1 ps-2 pe-2 bg-green-200 text-slate-500" ><i class="fa-solid fa-check"></i></span> {{ session('success') }}
                        <div class="loadingBar"></div>
                   </div>
               </div>
           @endif
       </div>

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
    </div>

</x-app-layout>
