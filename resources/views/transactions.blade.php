<x-app-layout>
    <x-slot name="header" >
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <h2 class="rounded-md shadow-md bg-white dark:bg-dark-eval-1 p-3 text-xl font-semibold leading-tight">
                <i class="fa-solid fa-box-archive"></i> {{ __('Transactions') }}
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
        <div class="text-right mb-5">
            <div>
                <div class="" style="display: grid; place-content: center;">
                    <form action="{{ route('transactions') }}" method="GET" class="search-bar">
                        <div class="overflow-hidden rounded mb-5 shadow-md dark:bg-dark-eval-1 flex">
                            <input style="width: 1000px;" class="overflow-hidden rounded-md border-none bg-slate-50 searchInpt bg-transparent" type="text" name="id_number_search" placeholder="🔍 ID Number, Name, Book">
                            <button style="" type="submit" name="letter_filter" value="" class=" hover:bg-slate-300 duration-100 p-1 ps-3 pe-3 rounded-md me-2 m-1 {{ empty(request()->input('letter_filter')) ? 'active' : '' }}">Clear</button>

                            {{-- <button type="submit" class="search-button text-slate-600 bg-slate-200 hover:text-slate-700 duration-100" style="width: 100px;">Search</button> --}}

                        </div>

                    </form>
                </div>
                <button id="showSearchButton" class="text-slate-600 hover:text-slate-700 duration-100" style="width: 50px; padding: 10px; visibility: hidden;"><i class="fa-solid fa-search"></i></button>

            </div>
        </div>


        <div class="">
            <div class="transactCenter">
                <div class="flex flex-wrap">
                    @if (count($acceptedRequests) > 0)
                    @foreach ($acceptedRequests as $index => $acceptedRequest)
                    @php
                        $carbonDate1 = \Carbon\Carbon::parse($acceptedRequest->date_borrow);
                        $carbonDate2 = \Carbon\Carbon::parse($acceptedRequest->date_pickup);
                        $carbonDate3 = \Carbon\Carbon::parse($acceptedRequest->date_return);

                        $formattedDate1 = $carbonDate1->format('l, F jS, Y');
                        $formattedDate2 = $carbonDate2->format('l, F jS, Y');
                        $formattedDate3 = $carbonDate3->format('l, F jS, Y');
                    @endphp
                        <div class="m-10 shadow-lg dark:bg-dark-eval-1hover:shadow-sm duration-200" style="border-radius: 5px; margin-top: -15px;">
                            <div style="width: 300px; height: 550px;">
                                <a href="{{ route('startChat', $acceptedRequest->user->id) }}" class="p-2 ps-3 pe-3 text-slate-500 bg-slate-300 hover:bg-slate-500 hover:text-slate-100 duration-100 btn btn-primary float-right start_Chat rounded-lg shadow-lg">
                                    <i class="fa-brands fa-rocketchat @if ($acceptedRequest->user->hasChatData()) rotate-3d @endif"></i>
                                    @if ($acceptedRequest->user->hasChatData())
                                        <span class="badge-dot badge-dot-red"></span>
                                    @endif
                                </a>
                                <div class="p-5">
                                    <h1><b><i class="fa-solid fa-user"></i> Borrower</b></h1>
                                    {{ $acceptedRequest->user->name }} <br> <hr> <br>
                                    <h1><b><i class="fa-solid fa-id-card"></i> ID Number</b></h1>
                                    {{ $acceptedRequest->user->id_number }} <br> <hr> <br>
                                    <h1><b><i class="fa-solid fa-layer-group"></i> Grade Level</b></h1>
                                    {{ $acceptedRequest->user->grade_level }} <br> <hr> <br>
                                    <h1><b><i class="fa-solid fa-book"></i> Book Title</b></h1>
                                    {{ $acceptedRequest->book->title }} <br> <hr> <br>
                                    <h1><b><i class="fa-solid fa-calendar-days"></i> Borrowed On</b></h1>
                                    {{ $formattedDate1}} <br> <hr> <br>


                                    <h1><b><i class="fa-solid fa-calendar-days"></i> Pickup Date</b></h1>
                                    {{ $formattedDate2 }} <br> <hr> <br>


                                    <h1><b><i class="fa-solid fa-calendar-days"></i> Return Date</b></h1>


                                    <div >
                                        {{-- <h1><b><i class="fa-solid fa-hourglass-start"></i> Time Remaining</b></h1> --}}
                                        <div style="display: none;" class="countdown-timer" data-target="{{ $acceptedRequest->timeDuration->date_return_seconds }}">
                                            <!-- Countdown timer will be updated here using JavaScript -->
                                        </div>
                                    </div>



                                        {{ $formattedDate3 }} <br> <hr> <br>


                                        <h1><b><i class="fa-solid fa-chart-simple"></i> Status</b></h1>
                                        <div class="flex">
                                            <div id="fines-container-{{ $index }}" style="display: none;">{{ $acceptedRequest->late_return }}</div>
                                        </div>
                                        <hr>




                                </div>
                            </div>
                          <div class="text-center mt-20">
                            <form action="{{ route('acceptedRequests.destroy', $acceptedRequest->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="text-green-600 hover:text-green-700 duration-100"
                                    style="width: 150px; border-radius: 5px; padding: 10px;"
                                    type="submit"
                                >
                                    <b><i class="fa-solid fa-check"></i> End Record</b>
                                </button>
                            </form>

                            {{-- <form action="{{ route('returnBook', $acceptedRequest->id) }}" method="POST">
                                @csrf
                                @if (!$acceptedRequest->book_returned)
                                    <button class="text-green-600 hover:text-green-700 duration-100"
                                        style="width: 150px; border-radius: 5px; padding: 10px;"
                                        type="submit"
                                    >
                                        <b><i class="fa-solid fa-rotate-left"></i> Return Book Only</b>
                                    </button>
                                @else
                                    <button class="text-gray-400"
                                        style="width: 150px; border-radius: 5px; padding: 10px;"
                                        type="button"
                                        disabled
                                    >
                                        <b><i class="fa-solid fa-rotate-left"></i> Book Already Returned</b>
                                    </button>
                                @endif
                            </form> --}}



                          </div>
                        </div>
                    @endforeach
                    @else
                        <p>There is no transactions.</p>
                    @endif





                </div>
            </div>
            {{-- <div>
                @foreach ($acceptedRequests as $acceptedRequest)
                <div>
                    <div style="margin: 13px; border-radius: 10px; box-shadow: 10px 10px 20px 5px rgba(0, 0, 0, 0.298);
                                background-color: {{ (!is_null($acceptedRequest->fines) && $acceptedRequest->fines > 0.00) ? 'rgb(71, 50, 20)' : 'rgb(4, 51, 71)' }};
                                padding: 20px">
                        <div>
                            <div style="margin-bottom: 20px; width: 240px;">
                                <b>Borrower</b> <br> {{ $acceptedRequest->user->name }}<br> <br>
                                <b>ID Number</b> <br> {{ $acceptedRequest->user->id_number }}<br> <br>

                                <b>Book Title</b> <br> {{ $acceptedRequest->book_title }} <br> <br>
                                <b>Borrowed on</b> <br> {{ $acceptedRequest->date_borrow->format('Y-m-d H:i A') }} <br> <br>
                                <b>Pickup Date</b> <br> {{ $acceptedRequest->date_pickup->format('Y-m-d H:i A') }} <br> <br>
                                <b>Return Date</b> <br> {{ $acceptedRequest->date_return->format('Y-m-d H:i A') }} <br> <br>
                                <b>Fines</b> <br>
                                @if (!is_null($acceptedRequest->fines) && $acceptedRequest->fines > 0.00)
                                    ₱ {{ $acceptedRequest->fines }} <b style="font-size: 10px;">Additional {{$acceptedRequest->fines}} for another day passes</b>
                                @else
                                    <b style="font-size: 10px;">No fines before return date expires</b>
                                @endif

                                <hr style="margin-top: 20px;">

                                <div style="display: grid; place-items: center; margin-top: 10px; margin-bottom: -30px">
                                    <form action="{{ route('acceptedRequests.destroy', $acceptedRequest->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            style="width: 150px; border-radius: 5px; padding: 10px; background-color: rgb(51, 130, 58)"
                                            type="submit"
                                        >
                                            Return Book
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            </div> --}}
        </div>
    </div>

   {{-- Loading Screen --}}
   <div id="loading-bar" class="loading-bar"></div>
  <style>

@keyframes rotate3d {
    0% {
        transform: rotateY(0deg);
    }

    100% {
        transform: rotateY(360deg);
    }
}

.rotate-3d {
    animation: rotate3d 2s infinite; /* 4 seconds for one full rotation (2 sec rotation + 2 sec pause) */
}



        .start_Chat{

transform: translateY(-5px);


}
            .search-bar {
            display: block;

            overflow: hidden;
            transition: 1s;
        }

        /* Style for the search bar */
        .searchInpt {
            color: black;
        }

        /* Style for the submit button */
        .search-button {
            padding: 10px;
        }
        .success-message-container {
        position: fixed;
    }

    .success-message {
        text-align: right;
        margin-bottom: 150px;
        opacity: 0;
        transition: opacity 0.3s, transform 0.3s;
    }

    .loadingBar{
        position: absolute;
        bottom: 0;
        left: 0;
        width: 0%;
        height: 3px;
        background-color: #00af2cab;
        transition: width 3s linear;
    }

            @media (max-width: 1000px) and (max-height: 2000px) {
            .transactCenter{
        display: grid;

    }
    }

    @media (max-width: 600px) and (max-height: 2000px) {
        .transactCenter{
        display: grid;
        place-content: center;
    }
    }



    .transactCenter{
        display: grid;
    }
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





               // JavaScript to toggle the search bar visibility with sliding effect
   const showSearchButton = document.getElementById('showSearchButton');
    const searchForm = document.querySelector('.search-bar');

    showSearchButton.addEventListener('click', () => {
        if (searchForm.style.maxHeight === '0px' || searchForm.style.maxHeight === '') {
            searchForm.style.maxHeight = '200px'; // Adjust the value as needed
        } else {
            searchForm.style.maxHeight = '0';
        }
    });
// JavaScript to show and hide the loading bar
window.addEventListener('beforeunload', function () {
  document.getElementById('loading-bar').style.width = '100%';
});

window.addEventListener('load', function () {
  document.getElementById('loading-bar').style.width = '0';
});
window.addEventListener('DOMContentLoaded', (event) => {
        const successMessage = document.querySelector('.success-message');
        if (successMessage) {
            setTimeout(() => {
                successMessage.style.opacity = '1';
                successMessage.style.transform = 'translateY(0)';
            }, 100);
        }
    });

    window.addEventListener('DOMContentLoaded', (event) => {
        const successMessageContainer = document.querySelector('.success-message-container');
        const successMessage = document.querySelector('.success-message');
        const loadingBar = document.querySelector('.loadingBar');

        if (successMessage) {
            setTimeout(() => {
                loadingBar.style.width = '100%';
            }, 100);

            setTimeout(() => {
                loadingBar.style.opacity = '0';
                successMessage.style.opacity = '0';
                successMessage.style.transform = 'translateY(-20px)';
                setTimeout(() => {
                    successMessageContainer.remove();
                }, 300);
            }, 3000); // 3 seconds for the loading bar to animate, then 100 milliseconds for the success message to disappear
        }
    });


    function initializeCountdown() {
    const countdownElements = document.querySelectorAll('.countdown-timer');
    countdownElements.forEach((element, index) => {
        const targetTimestamp = parseInt(element.getAttribute('data-target'), 10);
        updateCountdown(element, targetTimestamp, index);
    });
}

function updateCountdown(element, targetTimestamp, index) {
    const currentTimestamp = Math.floor(Date.now() / 1000);
    const remainingTime = targetTimestamp - currentTimestamp;

    if (remainingTime > 0) {
        const hours = Math.floor(remainingTime / 3600);
        const minutes = Math.floor((remainingTime % 3600) / 60);
        const seconds = remainingTime % 60;
        element.innerHTML = hours + "h " + minutes + "m " + seconds + "s";

        // Get the unique fines container for this transaction
        const finesContainer = document.getElementById(`fines-container-${index}`);
        finesContainer.style.display = 'block';
        finesContainer.innerHTML = 'Ongoing borrowing';

        setTimeout(() => updateCountdown(element, targetTimestamp, index), 1000);
    } else {
        element.innerHTML = "Expired";

        // Show the fines container when the countdown expires
        const finesContainer = document.getElementById(`fines-container-${index}`);
        finesContainer.style.display = 'block';
    }
}

// Initialize the countdown timers when the page loads
window.addEventListener('DOMContentLoaded', initializeCountdown);


    </script>
</x-app-layout>
