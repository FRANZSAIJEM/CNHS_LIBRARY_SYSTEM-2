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
                      <div class="overflow-hidden rounded mb-5 shadow-md dark:bg-dark-eval-1">
                          <input style="width: 1000px;" class="overflow-hidden rounded-md border-none bg-slate-50 searchInpt bg-transparent" type="text" name="id_number_search" placeholder="ID Number, Name">
                      </div>
                  </form>
              </div>
              <button id="showSearchButton" class="text-slate-600 hover:text-slate-700 duration-100" style="width: 50px; padding: 10px;"><i class="fa-solid fa-search"></i></button>
            </div>
        </div>
        <div class="">
            <div class="studentCenter">
                <div class="flex flex-wrap">
                    @foreach ($students as $student)
                    <div class="m-10 shadow-lg dark:bg-dark-eval-1hover:shadow-sm duration-200" style="border-radius: 5px; margin-top: -15px;">
                        <div style="width: 300px; height: 550px;">

                            <a href="{{ route('startChat', ['userId' => $student->id]) }}" class="p-2 ps-3 pe-3 text-slate-500 bg-slate-300 hover:bg-slate-500 hover:text-slate-100 duration-100 btn btn-primary float-right start_Chat rounded-lg shadow-lg">
                                <i class="fa-brands fa-rocketchat @if ($student->hasChatData()) rotate-3d @endif"></i>
                                @if ($student->hasChatData())
                                    <span class="badge-dot badge-dot-red"></span>
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
                            </div>
                            <div class="text-center">
                                <form class="toggle-form" data-student-id="{{ $student->id }}" style="display: inline;">
                                    @csrf
                                    <i id="i" class="fa-regular fa-address-card"></i>
                                    <button class="toggle-button " type="button"
                                            style="font-weight: 1000; padding: 10px; border-radius: 5px; color: {{ $student->is_disabled ? 'red' : 'green' }};">
                                        {{ $student->is_disabled ? 'Account Disabled' : 'Account Enabled' }}
                                    </button>
                                </form>

                            </div>
                        </div>
                    </div>
                    @endforeach

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
            max-height: 0;
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
            @media (max-width: 1000px) and (max-height: 2000px) {
            .studentCenter{
        display: flex;
        place-content: center;
    }
    }

    @media (max-width: 600px) and (max-height: 2000px) {
        .studentCenter{
        display: flex;
        place-content: center;
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


                button.textContent = `Account ${newStatus}`;
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
    </script>
</x-app-layout>
