<x-app-layout>
    <x-slot name="header" >
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <h2 class="rounded-md shadow-md bg-white dark:bg-dark-eval-1 p-3 text-xl font-semibold leading-tight">
                <i  class="fas fa-exchange-alt"></i> {{ __('Borrowed Books') }}
            </h2>
            <h2 class="rounded-md shadow-md bg-white hover:bg-slate-300 duration-100 dark:bg-dark-eval-1 p-3 text-xl font-semibold leading-tight">
                <a href="archivebook"><i class="fa-solid fa-newspaper fa-bounce"></i> Manual Record ></a>
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
        <div class="text-right">
            <div>
                <div class="" style="display: grid; place-content: center;">
                    <form action="{{ route('transactions') }}" method="GET" class="search-bar">
                        <div class="overflow-hidden rounded mb-5 shadow-md dark:bg-dark-eval-1 flex">
                            <input style="width: 1000px;" class="overflow-hidden rounded-md border-none bg-slate-50 searchInpt bg-transparent" type="text" name="id_number_search" placeholder="🔍 ID Number, Name, Book">
                            <button style="" type="submit" name="letter_filter" value="" class=" hover:bg-slate-300 duration-100 p-1 ps-3 pe-3 rounded-md me-2 m-1 {{ empty(request()->input('letter_filter')) ? 'active' : '' }}">Clear</button>
                        </div>

                    </form>

                </div>
                <form action="{{ route('transactions') }}" method="GET" class="search-bar">
                    <div class="flex justify-end">
                        <button class="text-white bg-orange-400 hover:bg-orange-500 duration-100" type="button" style="width: 143px; border-radius: 5px; padding: 10px; " onclick="showConfirmationModalDateFilter()"><b><i class="fa-regular fa-calendar-days"></i> Filter By Date</b></button>
                        {{-- <button type="submit" onclick="clearDateFilter()" class="hover:bg-slate-300 duration-100 p-1 ps-3 pe-3 rounded-md me-2 m-1">Clear Date Filter</button> --}}
                    </div>
                </form>


                <button id="showSearchButton" class="text-slate-600 hover:text-slate-700 duration-100" style="width: 50px; padding: 10px; visibility: hidden;"><i class="fa-solid fa-search"></i></button>

            </div>
        </div>


        <div class="">
            <div class="transactCenter">





                <div class="max-w-md mx-auto bg-white p-8 rounded shadow-md">

                    <form method="post" action="{{ route('duplicate-accept-request') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="user_id" class="block text-gray-700 text-sm font-bold mb-2">Select User:</label>
                            <select id="user_id" name="user_id" required
                                class="w-full border border-gray-300 rounded p-2 focus:outline-none focus:border-indigo-500">
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="book_id" class="block text-gray-700 text-sm font-bold mb-2">Select Book:</label>
                            <select id="book_id" name="book_id" required
                                class="w-full border border-gray-300 rounded p-2 focus:outline-none focus:border-indigo-500">
                                @foreach($books as $book)
                                    <option value="{{ $book->id }}">{{ $book->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="date_pickup" class="block text-gray-700 text-sm font-bold mb-2">Date Pickup:</label>
                            <input type="datetime-local" id="date_pickup" name="date_pickup" required
                                class="w-full border border-gray-300 rounded p-2 focus:outline-none focus:border-indigo-500">
                        </div>

                        <div class="mb-4">
                            <label for="date_return" class="block text-gray-700 text-sm font-bold mb-2">Date Return:</label>
                            <input type="datetime-local" id="date_return" name="date_return" required
                                class="w-full border border-gray-300 rounded p-2 focus:outline-none focus:border-indigo-500">
                        </div>

                        <!-- Add any other form fields as needed -->

                        <div class="mt-6">
                            <button type="submit"
                                class="bg-indigo-500 text-white px-4 py-2 rounded hover:bg-indigo-700 focus:outline-none focus:shadow-outline-indigo active:bg-indigo-800">
                                Submit Duplicate Accept Request
                            </button>
                        </div>
                    </form>
                </div>







                <div class="flex flex-wrap">
                    @if (count($acceptedRequests) > 0)
                    @foreach ($acceptedRequests as $index => $acceptedRequest)
                    @php
                        $carbonDate1 = \Carbon\Carbon::parse($acceptedRequest->date_borrow);

                        // Adjust timezone if needed
                        $carbonDate1->setTimezone('Asia/Manila'); // Replace 'YourTimeZone' with the desired timezone
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

{{--
                                    <h1><b><i class="fa-solid fa-calendar-days"></i> Pickup Date</b></h1>
                                    {{ $formattedDate2 }} <br> <hr> <br> --}}


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
                          <div class="text-center" style="margin-top: -10px;">
                            <button class="text-green-600 hover:text-green-700 duration-100" type="button" style="width: 150px; border-radius: 5px; padding: 10px;" onclick="showConfirmationModal({{ $acceptedRequest->id }})"><b><i class="fa-solid fa-check"></i> End Record</b></button>

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


              {{-- Delete Modal --}}
            <div id="confirmDeleteModal" style="overflow-y: auto; display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); backdrop-filter: blur(5px); z-index: 1;">
                <div class="modalWidth" style="background-color: white; border-radius: 5px;  margin: 100px auto; padding: 20px; text-align: left;">

                    <div class="flex justify-between">
                        <h2><b><i class="fa-solid fa-clipboard"></i> End Record</b></h2>
                        <button class="rounded-lg p-4 text-slate-400 hover:text-slate-500 duration-100" style="transform: translateY(-15px); width: 50px;" onclick="hideConfirmationModal()"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                    <hr> <br>
                    <p>Are you sure you want to End this record?</p>
                    <br>
                    <hr> <br>
                    <div class="">
                        <div class="flex justify-end">
                            <button class="rounded-lg p-4  text-slate-600 hover:text-slate-700 duration-100" style="width: 125px;"  onclick="hideConfirmationModal()"><i class="fa-solid fa-ban"></i> Cancel</button> &nbsp;

                            <form action="{{ route('acceptedRequests.destroy', ['id' => '__BOOK_ID__']) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="rounded-lg p-4  text-green-600 hover:text-green-700 duration-100"
                                    style="width: 150px;"
                                    type="submit"
                                >
                                    <b><i class="fa-solid fa-check"></i> End Record</b>
                                </button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>




              {{-- Filter by date --}}
              <div id="confirmFilterDateModal" style="overflow-y: auto; display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); backdrop-filter: blur(5px); z-index: 1;">
                <div class="modalWidthDate" style="transform: translateY(35px); background-color: white; border-radius: 5px; margin: 100px auto; padding: 20px; text-align: left;">
                    <div class="flex justify-between">
                        <h2><b><i class="fa-solid fa-calendar-days"></i> Set Date</b></h2>
                        <button class="rounded-lg p-4 text-slate-400 hover:text-slate-500 duration-100" style="transform: translateY(-15px); width: 50px;" onclick="hideConfirmationDateFilterModal()"><i class="fa-solid fa-xmark"></i></button>

                    </div>
                    <hr> <br>

                    <p>
                        <form action="{{ route('transactions') }}" method="GET" class="search-bar">
                            @csrf
                            <div>
                                <label for="start_date"><b><i class="fa-solid fa-boxes-packing"></i> Start Date:</b></label> <br>
                                <input style="background-color: transparent;" class="text-left border-none" type="date" name="start_date" value="{{ request('start_date') }}">
                            </div>
                            <br>
                            <h1><b>Between</b></h1>
                            <br>
                            <div>
                                <label for="end_date"><b><i class="fa-solid fa-boxes-packing"></i> End Date:</b></label> <br>
                                <input style="background-color: transparent;"  class="text-left border-none" type="date" name="end_date" value="{{ request('end_date') }}">
                            </div>
                            <br>
                           <br>
                                <hr>
                                <br>
                            <div class="flex justify-end">
                                <button type="button" class="rounded-lg p-4 text-slate-600 hover:text-slate-700 duration-100" style="width: 125px;" onclick="hideConfirmationDateFilterModal()"><i class="fa-solid fa-ban"></i> Cancel</button> &nbsp;
                                <button type="submit" class="hover:text-green-700 text-green-600 duration-100 p-1 ps-3 pe-3 rounded-md me-2 m-1"><i class="fa fa-check"></i> Filter</button>
                            </div>
                        </form>
                    </p>

                </div>
            </div>




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

    .modalWidthDate{
        width: 300px;
    }

            @media (max-width: 1000px) and (max-height: 2000px) {
            .transactCenter{
            display: grid;

        }
        .modalWidth{
            width: 550px;
        }



    }

    @media (max-width: 600px) and (max-height: 2000px) {
        .transactCenter{
        display: grid;
        place-content: center;
    }
    .modalWidth{
            width: 300px;
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
.modalWidth{
        width: 600px;
    }
    </style>
<script>
    function clearDateFilter() {
        document.querySelector('input[name="start_date"]').value = '';
        document.querySelector('input[name="end_date"]').value = '';
    }




        function showConfirmationModal(bookId) {
            var modal = document.getElementById('confirmDeleteModal');
            modal.style.display = 'block';

            // Set the action of the form to include the specific book's ID
            var form = modal.querySelector('form');
            form.action = form.action.replace('__BOOK_ID__', bookId);
        }

        function hideConfirmationModal() {

            var modal2 = document.getElementById('confirmDeleteModal');
            modal2.style.display = 'none';

        }









        function showConfirmationModalDateFilter() {
            var modalDate = document.getElementById('confirmFilterDateModal');
            modalDate.style.display = 'block';
        }


        function hideConfirmationDateFilterModal() {

            var modalDate2 = document.getElementById('confirmFilterDateModal');
            modalDate2.style.display = 'none';

        }



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
