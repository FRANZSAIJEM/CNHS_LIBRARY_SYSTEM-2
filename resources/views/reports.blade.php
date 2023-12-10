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
                    <div class="container mx-auto p-4">
                        <label for="sectionFilter">Filter by Description:</label>
                        <select id="sectionFilter" onchange="filterSections()">
                            <option value="all">All Sections</option>
                            <option value="allStudents">All Students Who Borrowed Books</option>
                            <option value="gradeLevelMostBorrowed">Grade Level Most Borrowed</option>
                            <option value="bookCondition">Book Condition</option>
                            <option value="bookSubjectCounts">Total Count of Each Book Subject</option>
                            <option value="mostBorrowedBooks">Most Borrowed Books</option>
                            <option value="borrowedByDate">Borrowed By Date</option>
                        </select>
                    </div>
                    {{-- Button to export PDF --}}
                    <button class="text-orange-600 hover:text-orange-700 duration-100" type="button" style="width: 133px; border-radius: 5px; padding: 10px; " onclick="showConfirmationModal()"><b><i class="fa-regular fa-file-pdf"></i> Export PDF</b></button>
                </div>
                 <br>


                <br>

                <div id="allStudentsSection" class="container mx-auto p-4">
                    <h1 class="text-sm mb-4"><b class="text-lg">Description: </b> All Students Who Borrowed Books.</h1>

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
                            @foreach($usersWithBorrowedCount as $user)
                                <tr class="hover:bg-gray-100">
                                    <td class="py-2 px-4 border-b">{{ $user->id_number }}</td>
                                    <td class="py-2 px-4 border-b">{{ $user->name }}</td>
                                    <td class="py-2 px-4 border-b">{{ $user->grade_level }}</td>
                                    <td class="py-2 px-4 border-b">{{ $user->borrowed_count }}</td>
                                    {{-- Add other user information or customize as needed --}}
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>


                <div id="gradeLevelMostBorrowedSection" class="container mx-auto p-4">
                    <h1 class="text-sm mb-4"><b class="text-lg">Description: </b> Most Grade Level Borrowed Books.</h1>

                    <table class="min-w-full border border-gray-300 text-center">
                        <thead>
                            <tr>
                                <th class="py-2 px-4 border-b">Grade Level</th>
                                <th class="py-2 px-4 border-b">Total Borrowed From Grade Level</th>
                                <th class="py-2 px-4 border-b">Total Borrowed Books</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach($gradeLevelCounts as $gradeLevel => $count)
                                <tr class="hover:bg-gray-100">
                                    <td class="py-2 px-4 border-b">{{ $gradeLevel }}</td>
                                    <td class="py-2 px-4 border-b">{{ $count }}</td>
                                    <td class="py-2 px-4 border-b">{{ $usersWithBorrowedCount->where('grade_level', $gradeLevel)->sum('borrowed_count') }}</td>
                                </tr>

                            @endforeach
                        </tbody>
                    </table>
                </div>


                <div id="bookConditionSection" class="container mx-auto p-4">
                    <h1 class="text-sm mb-4"><b class="text-lg">Description: </b> Total books, And Their Condition, Status, And Availabilty.</h1>

                    <table class="min-w-full border border-gray-300 text-center">
                        <tbody>
                            <tr>
                                <th class="py-2 px-4 border-b">Attribute</th>
                                <th class="py-2 px-4 border-b">Count</th>
                            </tr>
                            <tr class="hover:bg-gray-100">
                                <td class="py-2 px-4 border-b">Total Books</td>
                                <td class="py-2 px-4 border-b">{{ $allBooksCount }}</td>
                            </tr>
                            <tr class="hover:bg-gray-100">
                                <td class="py-2 px-4 border-b">Total Available Books</td>
                                <td class="py-2 px-4 border-b">{{ $availableBooksCount }}</td>
                            </tr>
                            <tr class="hover:bg-gray-100">
                                <td class="py-2 px-4 border-b">Total Not Available Books</td>
                                <td class="py-2 px-4 border-b">{{ $notAvailableBooksCount }}</td>
                            </tr>
                            <tr class="hover:bg-gray-100">
                                <td class="py-2 px-4 border-b">Total Good Books</td>
                                <td class="py-2 px-4 border-b">{{ $goodBooksCount }}</td>
                            </tr>
                            <tr class="hover:bg-gray-100">
                                <td class="py-2 px-4 border-b">Total Damage Books</td>
                                <td class="py-2 px-4 border-b">{{ $damageBooksCount }}</td>
                            </tr>
                            <tr class="hover:bg-gray-100">
                                <td class="py-2 px-4 border-b">Total Lost Books</td>
                                <td class="py-2 px-4 border-b">{{ $lostBooksCount }}</td>
                            </tr>
                        </tbody>
                    </table>


                </div>


                <div id="bookSubjectCountsSection" class="container mx-auto p-4">
                    <h1 class="text-sm mb-4"><b class="text-lg">Description: </b> Total Count of Each Book Subject</h1>

                    <table class="min-w-full border border-gray-300 text-center">
                        <thead>
                            <tr>
                                <th class="py-2 px-4 border-b">Subject</th>
                                <th class="py-2 px-4 border-b">Total Books</th>
                                <th class="py-2 px-4 border-b">Available</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($subjectCounts as $subject => $count)
                                <tr class="hover:bg-gray-100">
                                    <td class="py-2 px-4 border-b">{{ $subject }}</td>
                                    <td class="py-2 px-4 border-b">{{ $count }}</td>
                                    <td class="py-2 px-4 border-b">{{ $availableSubjectCounts[$subject] ?? 0 }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div id="mostBorrowedBooksSection" class="container mx-auto p-4">
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






                <div id="borrowedByDateSection" class="container mx-auto p-4">
                    <h1 class="text-sm mb-4"><b class="text-lg">Description: </b> All Students Who Borrowed Books By Year And Month</h1>


                    <table class="min-w-full border border-gray-300 text-center">
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
                                $carbonDate = \Carbon\Carbon::createFromDate($year, $month, $day)->setTimezone('Asia/Manila');
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
                                            {{ \Carbon\Carbon::createFromDate(null, $month, null)->setTimezone('Asia/Manila')->format('F') }}
                                            @php $prevMonth = $month; @endphp
                                        @endif
                                    </td>

                                    <td>{{  \Carbon\Carbon::createFromDate($year, $month, $day)->setTimezone('Asia/Manila')->format('j') }}, {{ \Carbon\Carbon::createFromDate($year, $month, $day)->setTimezone('Asia/Manila')->format('l') }}</td>
                                    <td>{{ count($notifications) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>




                </div>




            </div>
        </div>
    </div>


 <div id="confirmDeleteModal" style="margin-top: 50px; overflow-y: auto; display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); backdrop-filter: blur(5px); z-index: 1;">
    <div class="modalWidth" style="background-color: white; border-radius: 5px;  margin: 100px auto; padding: 20px; text-align: left;">

        <div class="flex justify-between">
            <h2><b><i class="fa-regular fa-file-pdf"></i> Select Option</b></h2>
            <button class="rounded-lg p-4 text-slate-400 hover:text-slate-500 duration-100" style="transform: translateY(-15px); width: 50px;" onclick="hideConfirmationModal()"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <hr> <br>
        <div class="">
            <div style="display: grid; place-content: center;">
                <form action="{{ route('generate-pdf') }}" method="post" target="_blank">

                    @csrf
                    <div class="flex justify-center">
                        <button style="width: 180px;" type="submit" class="bg-orange-500 p-3 rounded-lg text-white hover:bg-orange-600 duration-100"><i class="fa-regular fa-file-pdf"></i> <b>Export all reports</b></button>
                    </div>
                </form> <br>
               <h1 class="flex justify-center">or</h1>
                <br>

                <form action="{{ route('generate-selected-pdf') }}" method="post" target="_blank">
                    @csrf

                            <h1 class="flex justify-center"><b>Select report you want to export PDF</b></h1> <br>
                            {{-- Add checkboxes for different report options --}}
                            <div class="flex mb-2">
                                <label style="width: 325px;" class="cursor-pointer bg-slate-200 p-2 rounded-lg hover:bg-slate-300 duration-100" for="allStudents">
                                <input id="allStudents" type="checkbox" name="reports[]" onchange="updateSubmitButton()" value="allStudents"> &nbsp;
                                All Students Who Borrowed Books</label>
                            </div>

                            <div class="flex mb-2">

                                <label style="width: 325px;" class="cursor-pointer bg-slate-200 p-2 rounded-lg hover:bg-slate-300 duration-100" for="gradeLevelMostBorrowed">
                                    <input id="gradeLevelMostBorrowed" type="checkbox" name="reports[]" onchange="updateSubmitButton()" value="gradeLevelMostBorrowed"> &nbsp;
                                    Grade Level Most Borrowed</label>
                            </div>

                            <div class="flex mb-2">

                                <label style="width: 325px;" class="cursor-pointer bg-slate-200 p-2 rounded-lg hover:bg-slate-300 duration-100" for="bookCondition">
                                    <input id="bookCondition" type="checkbox" name="reports[]" onchange="updateSubmitButton()" value="bookCondition"> &nbsp;
                                    Book Condition</label>
                            </div>

                            <div class="flex mb-2">

                                <label style="width: 325px;" class="cursor-pointer bg-slate-200 p-2 rounded-lg hover:bg-slate-300 duration-100" for="bookSubjectCounts">
                                    <input id="bookSubjectCounts" type="checkbox" name="reports[]" onchange="updateSubmitButton()" value="bookSubjectCounts"> &nbsp;
                                    Total Count of Each Book Subject</label>
                            </div>

                            <div class="flex mb-2">

                                <label style="width: 325px;" class="cursor-pointer bg-slate-200 p-2 rounded-lg hover:bg-slate-300 duration-100" for="mostBorrowedBooks">
                                    <input id="mostBorrowedBooks" type="checkbox" name="reports[]" onchange="updateSubmitButton()" value="mostBorrowedBooks"> &nbsp;
                                    Most Borrowed Books</label>
                            </div>

                            <div class="flex mb-2">

                                <label style="width: 325px;" class="cursor-pointer bg-slate-200 p-2 rounded-lg hover:bg-slate-300 duration-100" for="borrowedByDate">
                                    <input id="borrowedByDate" type="checkbox" name="reports[]" onchange="updateSubmitButton()" value="borrowedByDate"> &nbsp;
                                    Borrowed By Date</label>
                            </div>

                          <div class="flex justify-center mt-5">
                              {{-- Add a submit button to trigger the PDF generation --}}
                            <button style="width: 180px;" disabled id="generatePdfButton" class="bg-orange-500 hover:bg-orange-600 duration-100 p-3 rounded-lg text-white" type="submit"><i class="fa-regular fa-file-pdf"></i> <b>Export PDF</b></button>
                          </div>
                </form>
            </div>

        </div>
        <br>
        <hr> <br>

    </div>
</div>




    <script>
        function updateSubmitButton() {
            // Get all checkboxes
            var checkboxes = document.getElementsByName('reports[]');

            // Get the submit button
            var submitButton = document.getElementById('generatePdfButton');

            // Check if any checkbox is selected
            var anyCheckboxSelected = Array.from(checkboxes).some(checkbox => checkbox.checked);

            // Enable or disable the submit button based on checkbox selection
            submitButton.disabled = !anyCheckboxSelected;
        }
        function showConfirmationModal(bookId) {
            var modal = document.getElementById('confirmDeleteModal');
            modal.style.display = 'block';
        }


        function hideConfirmationModal() {

            var modal2 = document.getElementById('confirmDeleteModal');


            modal2.style.display = 'none';

        }




        function filterSections() {
        var selectedOption = document.getElementById("sectionFilter").value;

        // Show all sections if "All Sections" is selected
        if (selectedOption === "all") {
            var sections = document.querySelectorAll('[id$="Section"]');
            sections.forEach(function (section) {
                section.style.display = "block";
            });
        } else {
            // Hide all sections
            var sections = document.querySelectorAll('[id$="Section"]');
            sections.forEach(function (section) {
                section.style.display = "none";
            });

            // Show the selected section
            var selectedSection = document.getElementById(selectedOption + "Section");
            if (selectedSection) {
                selectedSection.style.display = "block";
            }
        }
    }

    </script>
    <style>
          .modalWidth{
        width: 350px;
    }
    </style>
</x-app-layout>

