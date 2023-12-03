<!-- resources/views/pdf/sample.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF Sample</title>
    <!-- Add any additional styles or metadata needed for your PDF -->
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
<body>

<!-- Add your content based on the selected reports -->
<div style="text-align: center;">
    <img width="100px" height="100px" src="logo.png" alt=""> <br> <br>
    CNHS LIBRARY SYSTEM <br>
    Sta. Cruz, Calape, Bohol
</div> <br> <br> <br>
@if(isset($usersWithBorrowedCount))
<div class="container mx-auto p-4">

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
                    <td>{{ $user->id_number }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->grade_level }}</td>
                    <td>{{ $user->borrowed_count }}</td>
                    {{-- Add other user information or customize as needed --}}
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif




@if(isset($gradeLevelCounts))
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

@endif




@if(isset($bookCondition))
<div class="container mx-auto p-4">
    <h1 class="description-title"><b style="font-size: 1.5rem;">Description: </b> Total books, and their condition, status, and availability.</h1>

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
                <td class="py-2 px-4 border-b">{{ $bookCondition['allBooksCount'] }}</td>
                <td class="py-2 px-4 border-b">{{ $bookCondition['availableBooksCount'] }}</td>
                <td class="py-2 px-4 border-b">{{ $bookCondition['notAvailableBooksCount'] }}</td>
                <td class="py-2 px-4 border-b">{{ $bookCondition['goodBooksCount'] }}</td>
                <td class="py-2 px-4 border-b">{{ $bookCondition['damageBooksCount'] }}</td>
                <td class="py-2 px-4 border-b">{{ $bookCondition['lostBooksCount'] }}</td>
            </tr>
        </tbody>
    </table>
</div>
@endif






@if(isset($mostBorrowedBooks))
<div class="">
    <div class="container mx-auto p-4">
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




</div>
</div>
@endif

@if(isset($groupedNotifications))
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
@endif





</body>
</html>
