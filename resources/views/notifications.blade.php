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
            <div id="fines-container" style="display: none;">
            @foreach($acceptedRequests as $request)
            <a href="{{ route('viewBook', ['id' => $request->book_id]) }}">
                <div class="p-5 rounded-md shadow-md dark:bg-dark-eval-1 hover:bg-slate-300 duration-100">
                    <h1><b>Hello</b> {{ $loggedInUser->name }},</h1>
                    <p>
                        We hope this message finds you well. We would like to bring to your attention that the return date for the book(s) you borrowed,
                        "{{$request->book_title}}"
                        has passed. As per our policy, a late fee of {{$defaultFine->amount}} has been applied to your account.<br> <br>
                        <hr>
                        <br>

                        <div class="text-red-600" style="font-size: 50px;">
                            Total Fines: ₱ {{$defaultFine->amount}}
                        </div>
                    </p>
                </div>
            </a>
            <div style="display: none;">
                <h1><b><i class="fa-solid fa-hourglass-start"></i> Time Remaining</b></h1>
                @if ($request)
                    <div class="countdown-timer" data-target="{{ $request->timeDuration->date_return_seconds }}">
                        <!-- Countdown timer will be updated here using JavaScript -->
                    </div>

                @else
                    <p>No accepted request found.</p>
                @endif
            </div>
            @endforeach
            </div>
            <br>
            {{-- <div>

                @php
                    $totalFines = 0; // Initialize a variable to store the total fines
                    $hasNotifications = false;
                @endphp

                @foreach($replies as $reply)
                    @php
                        $hasNotifications = true;
                    @endphp

                @endforeach

                @foreach($likes as $like)
                @php
                    $hasNotifications = true;
                @endphp


                @endforeach
                    @foreach($acceptedRequests as $request)
                        {{-- @if ($request->fines !== null)
                            @php
                                $totalFines += $request->defaultFine->amount; // Add fines to the total
                                $hasNotifications = true;
                            @endphp
                        @endif
                        <div style="display: none;">
                            <h1><b><i class="fa-solid fa-hourglass-start"></i> Time Remaining</b></h1>
                            @if ($request)
                                <div class="countdown-timer" data-target="{{ $request->timeDuration->date_return_seconds }}">
                                    <!-- Countdown timer will be updated here using JavaScript -->
                                </div>
                            @else
                                <p>No accepted request found.</p>
                            @endif
                        </div>

                @endforeach
            </div> --}}


{{--
            <div id="fines-container" style="display: none;">
                @if ($hasNotifications)
                @if ($totalFines > 0)
                <a href="{{ route('viewBook', ['id' => $request->book->id]) }}">
                <div class="mb-5 p-5 rounded-md shadow-md dark:bg-dark-eval-1 hover:bg-slate-300 duration-100">
                    <h1><b>Hello</b> {{ $loggedInUser->name }},</h1>
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
                        has been applied to your account for each book.

                        {{-- Please note that an additional
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
                            Total Fines: ₱ {{$defaultFine->amount}}
                        </div>
                    </p>
                </div>
            </a>
                @endif

            @endif



            </div>
            @if (!$hasNotifications)
                {{-- <!-- Message for no notifications -->
                <p>You have no notifications.</p>
            @endif --}}








            @foreach ($replies as $reply)
                <a href="{{ route('viewBook', ['id' => $reply->comment->book->id]) }}" class="block">
                    <div class="p-5 mb-5 rounded-md shadow-md dark:bg-dark-eval-1 hover:bg-slate-300 duration-100">
                        <div class="reply">
                            <div>
                            <p>{{ $reply->user->name }} replied to your comment "{{$reply->reply}}" about the book "{{ $reply->comment->book ? $reply->comment->book->title : 'Unknown Book' }}"

                            </div> <br>
                            <div class="me-5">
                                <h6 class="me-3" style="font-size: 13px;"></h6>
                                {{ \Carbon\Carbon::parse($reply->created_at)->shortRelativeDiff() }}
                            </div>
                            </p>
                        </div>
                    </div>
                </a>
            @endforeach




            @foreach ($likes as $like)
            <a href="{{ route('viewBook', ['id' => $like->comment->book->id]) }}" class="block">
                <div class="p-5 mb-5 rounded-md shadow-md dark:bg-dark-eval-1 hover:bg-slate-300 duration-100">
                    <div class="reply">
                        <div>
                            <p>{{ $like->user->name }} reacted <i class="fa-solid fa-heart text-red-600"></i>  to your comment "{{$like->comment->comment}}" about the book "{{ $like->comment->book ? $like->comment->book->title : 'Unknown Book' }}"  </p>
                        </div> <br>
                        <div class="me-5">
                            <h6 class="me-3" style="font-size: 13px;"></h6>
                            {{ \Carbon\Carbon::parse($like->created_at)->shortRelativeDiff() }}

                        </div>
                    </div>

                </div>
            </a>
        @endforeach





        @foreach($acceptedRequests as $request)
        <a href="{{ route('viewBook', ['id' => $request->book_id]) }}">
            <div class="p-5 rounded-md shadow-md dark:bg-dark-eval-1 hover:bg-slate-300 duration-100">
                <h1><b>Hello</b> {{ $loggedInUser->name }},</h1>
                <p>
                    We are pleased to inform you that your book request for
                    "{{$request->book->title}}"
                    has been confirmed. We have scheduled a pick-up time and date for your convenience. <br> <br>
                    <hr>
                    <br>

                    @php
                        $carbonDate1 = \Carbon\Carbon::parse($request->date_borrow);
                        $carbonDate2 = \Carbon\Carbon::parse($request->date_pickup);
                        $carbonDate3 = \Carbon\Carbon::parse($request->date_return);

                        $formattedDate1 = $carbonDate1->format('l, F jS, Y');
                        $formattedDate2 = $carbonDate2->format('l, F jS, Y');
                        $formattedDate3 = $carbonDate3->format('l, F jS, Y');

                    @endphp

                    <div>
                        <b><i class="fa-solid fa-calendar-days"></i> Date Borrowed</b> <br>
                        {{ $formattedDate1 }}
                    </div> <br>

                    <div>
                        <b><i class="fa-solid fa-calendar-days"></i> Date Pick-up</b> <br>
                        {{ $formattedDate2 }}
                    </div> <br>
                    <div>
                        <b><i class="fa-solid fa-calendar-days"></i> Date Return</b> <br>
                        {{ $formattedDate3 }}
                    </div>
                    <div class="text-right mt-5">
                        {{ \Carbon\Carbon::parse($request->created_at)->shortRelativeDiff() }}
                    </div>
                </p>
            </div>
        </a>
        @endforeach


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


    function initializeCountdown() {
    const countdownElements = document.querySelectorAll('.countdown-timer');
    countdownElements.forEach(element => {
        const targetTimestamp = parseInt(element.getAttribute('data-target'), 10);
        updateCountdown(element, targetTimestamp);
    });
}

function updateCountdown(element, targetTimestamp) {
    const currentTimestamp = Math.floor(Date.now() / 1000);
    const remainingTime = targetTimestamp - currentTimestamp;

    if (remainingTime > 0) {
        const hours = Math.floor(remainingTime / 3600);
        const minutes = Math.floor((remainingTime % 3600) / 60);
        const seconds = remainingTime % 60;
        element.innerHTML = hours + "h " + minutes + "m " + seconds + "s";

        // Show "0.00" in the fines container
        const finesContainer = document.getElementById('fines-container');
        finesContainer.style.display = 'block';
        finesContainer.innerHTML = '';

        setTimeout(() => updateCountdown(element, targetTimestamp), 1000);
    } else {
        element.innerHTML = "Expired";

        // Show the fines container when the countdown expires
        const finesContainer = document.getElementById('fines-container');
        finesContainer.style.display = 'block';
    }
}

// Initialize the countdown timers when the page loads
window.addEventListener('DOMContentLoaded', initializeCountdown);

    </script>
</x-app-layout>
