
<!DOCTYPE html>
<html>
<head>



    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .description-title {
            font-size: 1.125rem; /* Equivalent to text-lg in Tailwind */
            margin-bottom: 0.5rem; /* Equivalent to mb-4 in Tailwind */
        }


        .pdf-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1rem;
        }

        .pdf-table th,
        .pdf-table td {
            border: 2px solid #c2cbd6;
            padding: 0.75rem;
            text-align: center;
        }

        .pdf-table tr:hover {
            background-color: #f7fafc;
        }
    </style>
</head>
<body style="font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;">
    <div class="">
        <div class="">

            <div class="container mx-auto p-4">
                <div style="text-align: center;">
                    <img width="100px" height="100px" src="logo.png" alt=""> <br> <br>
                    CNHS LIBRARY SYSTEM <br>
                    Sta. Cruz, Calape, Bohol
                </div> <br> <br> <br>
                <h1 class="description-title"><b style="font-size: 1.5rem;">Description: </b> All Students who borrowed books.</h1>

                <table class="pdf-table">
                    <thead>
                        <tr>
                            <th>ID Number</th>
                            <th>Student Name</th>
                            <th>Grade Level</th>
                            <th>Borrowed Count</th>
                            {{-- Add other table headers if needed --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($usersWithBorrowedCount as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->grade_level }}</td>
                                <td>{{ $user->borrowed_count }}</td>
                                {{-- Add other user information or customize as needed --}}
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>


            <div class="container mx-auto p-4">
                <h1 class="description-title"><b style="font-size: 1.5rem;">Description: </b> Most Grade Level Borrowed Books.</h1>

                <table class="pdf-table">
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
                <h1 class="description-title"><b style="font-size: 1.5rem;">Description: </b> Total books, and their condition, status, and availabilty.</h1>

                <table class="pdf-table">
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
                <h1 class="description-title"><b style="font-size: 1.5rem;">Description: </b> Most Borrowed Book</h1>

                <table class="pdf-table">
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


            <div class="container mx-auto p-4">
                <h1 class="description-title"><b style="font-size: 1.5rem;">Description: </b> All students who borrowed books by year and month</h1>

                <table class="pdf-table">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 border-b">Year</th>
                            <th class="py-2 px-4 border-b">Month</th>
                            <th class="py-2 px-4 border-b">Day</th>
                            <th class="py-2 px-4 border-b">Total Borrowed Books</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $prevYear = null;
                            $prevMonth = null;
                        @endphp

                        @foreach ($groupedNotifications as $date => $notifications)
                            @php
                                [$year, $month, $day] = explode('-', $date);
                            @endphp

                            <tr>
                                <td style="">
                                    @if ($year != $prevYear)
                                        {{ $year }}
                                        @php $prevYear = $year; @endphp
                                    @endif
                                </td>

                                <td>
                                    @if ($month != $prevMonth)
                                        {{ \Carbon\Carbon::createFromDate(null, $month, null)->format('F') }}
                                        @php $prevMonth = $month; @endphp
                                    @endif
                                </td>

                                <td>Day {{ $day }}, {{ \Carbon\Carbon::createFromDate($year, $month, $day)->format('l') }}</td>
                                <td>{{ count($notifications) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</body>
</html>
