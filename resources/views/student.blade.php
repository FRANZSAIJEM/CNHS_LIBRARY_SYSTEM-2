<x-app-layout>
    <x-slot name="header" >
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <h2 class="rounded-md shadow-md bg-white dark:bg-dark-eval-1 p-3 text-xl font-semibold leading-tight">
                <i class="fa-solid fa-users"></i> {{ __('Students') }}
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
                  <form action="{{ route('student') }}" method="GET" class="search-bar">
                      <div class="overflow-hidden rounded mb-5 shadow-md dark:bg-dark-eval-1 flex">
                          <input style="width: 1000px;" class="overflow-hidden rounded-md border-none bg-slate-50 searchInpt bg-transparent" type="text" name="id_number_search" placeholder="🔍 ID Number, Name">
                          <button style="" type="submit" name="letter_filter" value="" class=" hover:bg-slate-300 duration-100 p-1 ps-3 pe-3 rounded-md me-2 m-1 {{ empty(request()->input('letter_filter')) ? 'active' : '' }}">Clear</button>

                      </div>
                  </form>
                  <div class="flex justify-end">
                    <form id="disable-all-form" action="{{ route('disableAllAccounts') }}" method="POST" style="display: inline;">
                        @csrf
                        @if ($isAnyStudentEnabled)
                            <button class="disable-all-button" type="submit" style="font-weight: 1000; padding: 10px; border-radius: 5px; color: red;" >Disable All</button>
                        @else
                            <button class="disable-all-button" type="submit" style="font-weight: 1000; padding: 10px; border-radius: 5px; color: rgb(255, 176, 176);" disabled>Disable All</button>
                        @endif
                    </form>

                    <form id="enable-all-form" action="{{ route('enableAllAccounts') }}" method="POST" style="display: inline;">
                        @csrf
                        @if ($isAnyStudentDisabled)
                            <button class="disable-all-button" type="submit" style="font-weight: 1000; padding: 10px; border-radius: 5px; color: green;" >Enable All</button>
                        @else
                            <button class="disable-all-button" type="submit" style="font-weight: 1000; padding: 10px; border-radius: 5px; color: rgb(151, 200, 151);" disabled>Enable All</button>
                        @endif
                    </form>



                  </div>
              </div>
              <button id="showSearchButton" class="text-slate-600 hover:text-slate-700 duration-100" style="width: 50px; padding: 10px; visibility: hidden;"><i class="fa-solid fa-search"></i></button>
            </div>
        </div>
        <div class="">
            <div class="studentCenter">
                <div class="flex flex-wrap">

                    @foreach ($students as $student)

                    <div class="m-10 shadow-lg dark:bg-dark-eval-1hover:shadow-sm duration-200" style="border-radius: 5px; margin-top: -15px;">
                        <div style="width: 300px; height: 600px;">

                            @php
                                $user = auth()->user();
                                $totalRequests = DB::table('chats')->count();
                                $latestDataTime = DB::table('chats')->max('created_at');

                                // Update the last checked time if it's null or there are new messages
                                if ($user->last_checked_chats === null || $latestDataTime > $user->last_checked_chats) {
                                    $user->update(['last_checked_chats' => now()]);
                                }

                                // Check if the badge should be displayed
                                $studentKey = 'visited_start_chat_' . $student->id;
                                $showBadge = $student->hasChatData() && !session($studentKey) && $totalRequests > 0;
                            @endphp

                            <a href="{{ route('startChat', ['userId' => $student->id]) }}" class="p-2 ps-3 pe-3 text-slate-500 bg-slate-300 hover:bg-slate-500 hover:text-slate-100 duration-100 btn btn-primary float-right start_Chat rounded-lg shadow-lg">
                                <i class="fa-brands fa-rocketchat @if ($student->hasChatData()) rotate-3d @endif"></i>

                                @if ($showBadge)
                                    <span style="margin-top: -10px;" class="bg-red-500 w-2.5 h-2.5 rounded-full absolute mb-5"></span>
                                @endif
                            </a>


                            <div class="p-5">
                                <h1><b><i class="fa-solid fa-file-signature"></i> Full Name</b></h1>
                                {{$student->name}} <br> <hr><br>
                                <h1><b><i class="fa-solid fa-id-card"></i> ID Number</b></h1>
                                {{$student->id_number}} <br> <hr> <br>
                                <h1><b><i class="fa-solid fa-envelope"></i> Email</b></h1>
                                {{$student->email}} <br> <hr> <br>
                                <h1><b><i class="fa-solid fa-phone"></i> Contact Number</b></h1>
                                {{$student->contact}} <br> <hr> <br>
                                <h1><b><i class="fa-solid fa-venus-mars"></i> Gender</b></h1>
                                {{$student->gender}} <br> <hr> <br>
                                <h1><b><i class="fa-solid fa-layer-group"></i> Grade Level</b></h1>
                                {{$student->grade_level}} <br> <hr> <br>
                                 <!-- Display fines -->

                                 <h1><b><i class="fa-solid fa-clock"></i> Instances of late returns.</b></h1>
                                 {{ number_format($student->totalFines, 0, '.', '') }} <br> <hr> <br>

                            </div>
                            <div class="">



                              <div class="flex justify-evenly" style="margin-top: -20px;">


                                <form class="toggle-form" data-student-id="{{ $student->id }}" style="display: inline;">
                                    @csrf

                                    <i id="i" class="fa-regular fa-address-card"></i>
                                    <button class="toggle-button" type="button" style="font-weight: 1000; padding: 10px; border-radius: 5px; color: {{ $student->is_disabled ? 'red' : 'green' }};">
                                        {{ $student->is_disabled ? 'Disabled' : 'Enabled' }}
                                    </button>

                                </form>



                                {{-- display here the total time --}}

                                <button class="text-red-600 hover:text-red-700 duration-100"
                                        type="button"
                                        style="cursor: pointer; border-radius: 5px; padding: 10px;"
                                        onclick="showConfirmationModal({{ $student->id }})"
                                        data-student-id="{{ $student->id }}"
                                        @if($student->is_suspended == 1) disabled @endif>
                                    <b><i class="fa-solid fa-lock"></i>
                                        @if($student->is_suspended == 1) Suspended @else Suspend @endif
                                    </b>
                                    <div id="countdown">
                                        {{ $student->getSuspensionDuration() }}
                                    </div>

                                </button>


                              </div>
                            </div>
                        </div>
                    </div>
                    @endforeach

                </div>
            </div>
        </div>

<!-- Delete Modal -->
<div id="confirmDeleteModal" style="margin-top: 50px; overflow-y: auto; display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); backdrop-filter: blur(5px); z-index: 1;">
    <div class="modalWidth" style="background-color: white; border-radius: 5px;  margin: 100px auto; padding: 20px; text-align: left;">
        <form id="suspendForm" action="{{ route('suspend-account', ['id' => '__STUDENT_ID__']) }}" method="post">

            <div class="flex justify-between">
                <h2><b><i class="fa-solid fa-lock"></i> Suspend Student</b></h2>
                <button class="rounded-lg p-4 text-slate-400 hover:text-slate-500 duration-100" style="transform: translateY(-15px); width: 50px;" onclick="hideConfirmationModal()"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <hr> <br>
            @csrf
            <div class="mb-4">
                <label for="start_date" class=""><b><i class="fa-solid fa-calendar-days"></i> Start Date:</b></label>
                <input type="datetime-local" id="start_date" name="start_date" required class="">
            </div>
            <div class="mb-6">
                <label for="end_date" class=""><b><i class="fa-solid fa-calendar-days"></i> End Date:</b></label>
                <input type="datetime-local" id="end_date" name="end_date" required class="">
            </div>
            <br>
            <hr> <br>
            <div class="">
                <div class="flex justify-end">
                    <button class="rounded-lg p-4  text-slate-600 hover:text-slate-700 duration-100" style="width: 125px;" onclick="hideConfirmationModal()"><i class="fa-solid fa-ban"></i> Cancel</button> &nbsp;
                    <button type="submit" class="rounded-lg p-4 text-red-500 hover:text-red-600 duration-100" style="width: 150px;"><i class="fa-solid fa-lock"></i> Suspend</button>
                </div>
            </div>
        </form>
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
        .success-message-container {
        position: fixed;
    }

    .success-message {
        text-align: right;
        margin-bottom: 150px;
        opacity: 0;
        transition: opacity 0.3s, transform 0.3s;
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
        .modalWidth{
        width: 600px;
    }
            @media (max-width: 1000px) and (max-height: 2000px) {
            .studentCenter{
        display: flex;
        place-content: center;
    }
    .modalWidth{
            width: 550px;
        }
    }

    @media (max-width: 600px) and (max-height: 2000px) {
        .studentCenter{
        display: flex;
        place-content: center;
    }
    .modalWidth{
            width: 300px;
        }
    }
    .studentCenter{
        display: flex;

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
// JavaScript to show and hide the loading bar
window.addEventListener('beforeunload', function () {
  document.getElementById('loading-bar').style.width = '100%';
});

window.addEventListener('load', function () {
  document.getElementById('loading-bar').style.width = '0';
});

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


                button.textContent = `${newStatus}`;
                button.style.color = newColor;
            }
        } catch (error) {
            console.error('Error toggling account status:', error);
        }
    });
});



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

      // Get the modal and button elements
      const modal = document.getElementById('suspendModal');
    const openModalButton = document.getElementById('openModalButton');
    const closeModalButton = document.getElementById('closeModalButton');




    function showConfirmationModal(studentId) {
    console.log("Suspend student with ID: " + studentId);

    // Show the modal
    var modal = document.getElementById('confirmDeleteModal');
    modal.style.display = 'block';

    // Set the action of the form to include the specific student's ID
    var form = modal.querySelector('form');
    form.action = form.action.replace(/\/suspend-account\/__STUDENT_ID__/, '/suspend-account/' + studentId);
}




function hideConfirmationModal() {
    // Hide the modal
    var modal = document.getElementById('confirmDeleteModal');
    modal.style.display = 'none';
}


function updateCountdown() {
        // Assuming $student is passed from the server as a JSON object with the necessary properties
        var startDate = new Date("{{ $student->suspend_start_date }}");
        var endDate = new Date("{{ $student->suspend_end_date }}");

        var now = new Date();
        var timeRemaining = endDate - now;

        if (timeRemaining > 0) {
            var hours = Math.floor(timeRemaining / (1000 * 60 * 60));
            var minutes = Math.floor((timeRemaining % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((timeRemaining % (1000 * 60)) / 1000);

            document.getElementById('countdown').innerHTML = hours + "h " + minutes + "m " + seconds + "s";
        } else {

            clearInterval(countdownInterval);
        }
    }

    // Update the countdown every second
    var countdownInterval = setInterval(updateCountdown, 1000);

    // Initial update
    updateCountdown();


    </script>
</x-app-layout>
