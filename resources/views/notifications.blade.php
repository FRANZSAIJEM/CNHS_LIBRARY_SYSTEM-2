<x-app-layout>
    <x-slot name="header" >
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <h2 class="rounded-md shadow-md bg-white dark:bg-dark-eval-1 p-3 text-xl font-semibold leading-tight">
                <i class="fa-solid fa-bell"></i> {{ __('Notifications') }}
            </h2>
        </div>
    </x-slot>

    <div class="p-6 overflow-hidden bg-white rounded-md shadow-md dark:bg-dark-eval-1">
        <div class="">
            <div>
                <!-- Additional content when fines are present -->
                @php
                    $totalFines = 0; // Initialize a variable to store the total fines
                @endphp

                @foreach($acceptedRequests as $request)
                    @if ($request->fines !== null)
                        @php
                            $totalFines += $request->fines; // Add fines to the total
                        @endphp
                    @endif
                @endforeach
            </div>
            <div class="mb-5 p-5 rounded-md shadow-md dark:bg-dark-eval-1 ">
                @if ($totalFines > 0)
                    <h1><b>Hello</b> {{ $loggedInUser->name }}</h1> <br>
                    <p>
                        We hope this message finds you well. We would like to bring to your attention that the return date for the book(s) you borrowed,
                        @foreach ($acceptedRequests as $request)
                            @if ($request->book && $request->fines > 0)
                                "{{ $request->book->title }}"
                                @if (!$loop->last)
                                    ,
                                @endif
                            @endif
                        @endforeach
                        has passed. As per our policy, a late fee of

                        @foreach ($acceptedRequests as $request)
                            @if ($request->book && $request->fines > 0)
                                {{$request->fines}} pesos for the "{{ $request->book->title }}"
                                @if (!$loop->last)
                                    and
                                @endif
                            @endif
                        @endforeach
                        has been applied to your account for each book. Please note that an additional
                        @foreach ($acceptedRequests as $request)
                            @if ($request->book && $request->fines > 0)
                                a late fee of {{$request->fines}} pesos for the "{{ $request->book->title }}"
                                @if (!$loop->last)
                                    and
                                @endif
                            @endif
                        @endforeach

                        will be added for each subsequent day that the book(s) remain(s) overdue. We kindly request you to return the book(s) as soon as possible to avoid further charges.
                        <br> <br>
                        <hr>

                        <div class="text-red-600" style="font-size: 50px;">
                            Total Fines: ₱ {{$totalFines}}
                        </div>
                    </p>

                @endif
            </div>
            <div class="p-5 rounded-md shadow-md dark:bg-dark-eval-1">
                @foreach($acceptedRequests as $request)
                    <h1><b>Hello</b> {{ $loggedInUser->name }}</h1> <br>
                    <p>
                        We are pleased to inform you that your book request for "{{$request->book_title}}" has been confirmed. We have scheduled a pick-up
                    time and date for your convenience. <br> <br>
                    <hr>
                     <br>

                    <div>
                        <b>Date Borrowed</b> <br>
                        {{$request->date_borrow}}
                    </div> <br>
                    <div>
                        <b>Date Pick-up</b> <br>
                        {{$request->date_pickup}}
                    </div> <br>
                    <div>
                        <b>Date Return</b> <br>
                        {{$request->date_return}}
                    </div>
                    </p> <br> <br>
                @endforeach
            </div>

                        {{-- <div>


                            @if ($totalFines > 0)
                                <div style="margin: 13px; border-radius: 10px; box-shadow: 10px 10px 20px 5px rgba(0, 0, 0, 0.298); background-color: rgb(71, 4, 4); padding: 20px">
                                    <div>
                                        <div style="margin-bottom: 20px; width: 1175px;">
                                            <!-- Display the total fines -->
                                            <b>Hello  {{ $loggedInUser->name }},</b> <br> <br>
                                            <p>
                                                We hope this message finds you well. We would like to bring to your attention that the return date for the book(s) you borrowed,
                                                @foreach ($acceptedRequests as $request)
                                                    @if ($request->book && $request->fines > 0)
                                                        "{{ $request->book->title }}"
                                                        @if (!$loop->last)
                                                            ,
                                                        @endif
                                                    @endif
                                                @endforeach
                                                has passed. As per our policy, a late fee of

                                                @foreach ($acceptedRequests as $request)
                                                    @if ($request->book && $request->fines > 0)
                                                        {{$request->fines}} pesos for the "{{ $request->book->title }}"
                                                        @if (!$loop->last)
                                                            and
                                                        @endif
                                                    @endif
                                                @endforeach
                                                has been applied to your account for each book. Please note that an additional
                                                @foreach ($acceptedRequests as $request)
                                                    @if ($request->book && $request->fines > 0)
                                                        a late fee of {{$request->fines}} pesos for the "{{ $request->book->title }}"
                                                        @if (!$loop->last)
                                                            and
                                                        @endif
                                                    @endif
                                                @endforeach

                                                will be added for each subsequent day that the book(s) remain(s) overdue. We kindly request you to return the book(s) as soon as possible to avoid further charges.
                                                <div style="margin-top: 50px; font-size: 50px;">
                                                    Total Fines: {{$totalFines}}
                                                </div>
                                            </p>


                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        @foreach($acceptedRequests as $request)
                        <div>
                            <div style="margin: 13px; border-radius: 10px; box-shadow: 10px 10px 20px 5px rgba(0, 0, 0, 0.298); background-color: rgb(4, 51, 71); padding: 20px">
                                <div>
                                    <div style="margin-bottom: 20px; width: 1175px;">
                                        <!-- Display accepted request data here, e.g., $request->field_name -->
                                        <b>Hello  {{ $loggedInUser->name }},</b> <br> <br>

                                        <p>
                                            We are pleased to inform you that your book request for "{{$request->book_title}}" has been confirmed. We have scheduled a pick-up
                                        time and date for your convenience. <br> <br>
                                        <hr>
                                         <br>

                                        <div>
                                            <b>Date Borrowed</b> <br>
                                            {{$request->date_borrow}}
                                        </div> <br>
                                        <div>
                                            <b>Date Pick-up</b> <br>
                                            {{$request->date_pickup}}
                                        </div> <br>
                                        <div>
                                            <b>Date Return</b> <br>
                                            {{$request->date_return}}
                                        </div>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach --}}
        </div>
    </div>

   {{-- Loading Screen --}}
   <div id="loading-bar" class="loading-bar"></div>
  <style>
  .loading-bar {
  width: 0;
  height: 5px; /* You can adjust the height as needed */
  background-color: #5fadff; /* Loading bar color */
  position: fixed;
  top: 0;
  left: 0;
  z-index: 9999;
  transition: width 0.3s ease; /* Adjust the animation speed as needed */
}

    </style>
<script>
// JavaScript to show and hide the loading bar
window.addEventListener('beforeunload', function () {
  document.getElementById('loading-bar').style.width = '100%';
});

window.addEventListener('load', function () {
  document.getElementById('loading-bar').style.width = '0';
});

function clearSearchInput() {
        document.getElementById('id_number_search').value = '';
        document.getElementById('searchForm').submit();
    }
    const toggleButtons = document.querySelectorAll('.toggle-button');

    toggleButtons.forEach(button => {
        button.addEventListener('click', async () => {
            const form = button.closest('.toggle-form');
            const studentId = form.dataset.studentId;

            try {
                const response = await fetch(`{{ route('toggleAccountStatus', ['id' => '__STUDENT_ID__']) }}`.replace('__STUDENT_ID__', studentId), {
                    method: 'POST',
                    body: new FormData(form),
                });

                if (response.ok) {
                    // Toggle the button text and background color
                    const currentStatus = button.textContent.includes('Enabled') ? 'Enabled' : 'Disabled';
                    const newStatus = currentStatus === 'Enabled' ? 'Disabled' : 'Enabled';
                    const newColor = currentStatus === 'Enabled' ? 'red' : 'green';

                    button.textContent = `Account ${newStatus}`;
                    button.style.backgroundColor = newColor;
                }
            } catch (error) {
                console.error('Error toggling account status:', error);
            }
        });
    });

    </script>
</x-app-layout>
