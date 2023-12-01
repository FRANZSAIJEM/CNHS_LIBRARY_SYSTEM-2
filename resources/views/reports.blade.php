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
                <div class="flex justify-end">
                    {{-- Button to export PDF --}}
                    <form action="{{ route('generate-pdf') }}" method="post" target="_blank">
                        @csrf
                        <button type="submit" class="bg-blue-500 p-3 rounded-lg text-white hover:bg-blue-600 duration-100"><i class="fa-regular fa-file-pdf"></i> Export PDF</button>
                    </form>
                </div>
                <br>

                <div class="container mx-auto p-4">
                    <h1 class="text-sm mb-4"><b class="text-lg">Description: </b> All Students who borrowed books.</h1>

                    <table class="min-w-full border border-gray-300 text-center">
                        <thead>
                            <tr>
                                <th class="py-2 px-4 border-b">ID Number</th>
                                <th class="py-2 px-4 border-b">Student Name</th>
                                <th class="py-2 px-4 border-b">Grade Level</th>

                                <th class="py-2 px-4 border-b">Borrowed Count</th>
                                {{-- Add other table headers if needed --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($usersWithAcceptedRequests as $user)
                                <tr class="hover:bg-gray-100">
                                    <td class="py-2 px-4 border-b">{{ $user->id }}</td>
                                    <td class="py-2 px-4 border-b">{{ $user->name }}</td>
                                    <td class="py-2 px-4 border-b">{{ $user->grade_level }}</td>
                                    <td class="py-2 px-4 border-b">{{ $user->borrowed_count }}</td>
                                    {{-- Add other user information or customize as needed --}}
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>


                <div class="container mx-auto p-4">
                    <h1 class="text-sm mb-4"><b class="text-lg">Description: </b> Most Grade Level Borrowed Books.</h1>

                    <table class="min-w-full border border-gray-300 text-center">
                        <thead>
                            <tr>
                                <th class="py-2 px-4 border-b">Grade Level</th>
                                <th class="py-2 px-4 border-b">Total Borrowed</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach($gradeLevelCounts as $gradeLevel => $count)
                                <tr class="hover:bg-gray-100">
                                    <td class="py-2 px-4 border-b">{{ $gradeLevel }}</td>
                                    <td class="py-2 px-4 border-b">{{ $count }}</td>


                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>


                <div class="container mx-auto p-4">
                    <h1 class="text-sm mb-4"><b class="text-lg">Description: </b> Total books, and their condition, status, and availabilty.</h1>

                    <table class="min-w-full border border-gray-300 text-center">
                        <thead>
                            <tr>
                                <th class="py-2 px-4 border-b">Total Books</th>
                                <th class="py-2 px-4 border-b">Total Available Books</th>
                                <th class="py-2 px-4 border-b">Total Not Available Books</th>
                                <th class="py-2 px-4 border-b">Total Good Books</th>
                                <th class="py-2 px-4 border-b">Total Damage Books</th>
                                <th class="py-2 px-4 border-b">Total Lost Books</th>



                            </tr>
                        </thead>
                        <tbody>

                            <tr class="hover:bg-gray-100">
                                <td class="py-2 px-4 border-b">{{ $allBooksCount }}</td>
                                <td class="py-2 px-4 border-b">{{ $availableBooksCount }}</td>
                                <td class="py-2 px-4 border-b">{{ $notAvailableBooksCount }}</td>
                                <td class="py-2 px-4 border-b">{{ $goodBooksCount }}</td>
                                <td class="py-2 px-4 border-b">{{ $damageBooksCount }}</td>
                                <td class="py-2 px-4 border-b">{{ $lostBooksCount }}</td>


                            </tr>

                        </tbody>
                    </table>
                </div>


                <div class="container mx-auto p-4">
                    <h1 class="text-sm mb-4"><b class="text-lg">Description: </b> Most Borrowed Book</h1>

                    <table class="min-w-full border border-gray-300 text-center">
                        <thead>
                            <tr>
                                <th class="py-2 px-4 border-b">Book Title</th>
                                <th class="py-2 px-4 border-b">Book Author</th>
                                <th class="py-2 px-4 border-b">Book Borrowed Count</th>


                            </tr>
                        </thead>
                        <tbody>
                            @if ($mostBorrowedBooks->isNotEmpty())
                                @foreach ($mostBorrowedBooks as $book)
                                    @if ($book->count > 0)
                                        <tr class="hover:bg-gray-100">
                                            <td class="py-2 px-4 border-b">{{ $book->title }}</td>
                                            <td class="py-2 px-4 border-b">{{ $book->author }}</td>
                                            <td class="py-2 px-4 border-b">{{ $book->count  }}</td>


                                        </tr>
                                    @endif
                                @endforeach
                            @else
                            <p>No books available.</p>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
