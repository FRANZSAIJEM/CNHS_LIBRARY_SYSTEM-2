<?php
use App\Models\DefaultFine;
use App\Models\AcceptedRequest;


$defFine = DefaultFine::first();
$defDailyFine = DefaultFine::first();

?>
<x-app-layout>
    <x-slot name="header" >
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <h2 class="rounded-md shadow-md bg-white dark:bg-dark-eval-1 p-3 text-xl font-semibold leading-tight">
                @if (Auth::user()->is_admin)
                <i class="fa-solid fa-code-pull-request"></i> {{ __('Requests') }}
                @endif
                @if (!Auth::user()->is_admin)
                <i class="fa-solid fa-code-pull-request"></i> {{ __('Your Request') }}
                @endif
            </h2>
        </div>
    </x-slot>

    <div class="p-6 bg-white rounded-md shadow-md dark:bg-dark-eval-1">
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
                    @if (Auth::user()->is_admin)
                    <form action="{{ route('requests') }}" method="GET" class="search-bar">
                        <div class="overflow-hidden rounded mb-5 shadow-md dark:bg-dark-eval-1 flex">
                            <input style="width: 1000px;" class="overflow-hidden rounded-md border-none bg-slate-50 searchInpt bg-transparent" type="text" name="book_search" placeholder="🔍 ID Number">
                            <button style="" type="submit" name="letter_filter" value="" class=" hover:bg-slate-300 duration-100 p-1 ps-3 pe-3 rounded-md me-2 m-1 {{ empty(request()->input('letter_filter')) ? 'active' : '' }}">Clear</button>
                            {{-- <button type="submit" class="search-button text-slate-600 bg-slate-200 hover:text-slate-700 duration-100" style="width: 100px;">Search</button> --}}
                        </div>
                    </form>
                    @endif
                </div>
                <form action="{{ route('requests') }}" method="GET" class="search-bar">
                    <div class="flex justify-end">
                        <button class="text-white bg-orange-400 hover:bg-orange-500 duration-100" type="button" style="width: 143px; border-radius: 5px; padding: 10px; " onclick="showConfirmationModalDateFilter()"><b><i class="fa-regular fa-calendar-days"></i> Filter By Date</b></button>
                        {{-- <button type="submit" onclick="clearDateFilter()" class="hover:bg-slate-300 duration-100 p-1 ps-3 pe-3 rounded-md me-2 m-1">Clear Date Filter</button> --}}

                    </div>
                </form>

            @if (Auth::user()->is_admin)
            <div style="position: relative">
                {{-- <button id="showSearchButton" class="text-slate-600 hover:text-slate-900 duration-100" style="width: 50px; padding: 10px;"><i class="fa-solid fa-search"></i></button> --}}
                {{-- <button class="text-slate-600 hover:text-slate-900 duration-100" id="showFormButton"><i class="fa-solid fa-gear"></i></button>

                <div id="defaultFineForm" style="display: none; position: absolute; right: 0; top: 50; transform: translateX(-5px);">
                    <div class="p-5 rounded-lg shadow-md bg-slate-50">
                        <h1 class="text-center"><b>Set Starting Fines</b></h1><br>

                        <div class="text-end">
                            <form action="{{ route('setDefaultFine') }}" method="POST">
                                @csrf

                                ₱ <input style="border-bottom: 1px solid black" class="border-none bg-transparent text-right" placeholder="" value="{{ $defFine ? $defFine->amount : '' }}" type="number" name="amount" placeholder="Enter default fine amount" required><br>
                                <button style="margin-bottom: -10px;" class="mt-5 p-3 text-slate-600 hover:text-slate-900 duration-100" type="submit"><i class="fa-solid fa-pen"></i> Save Amount</button>
                            </form>
                        </div> <br>

                        <h1 class="text-center"><b>Set Daily Fines</b></h1><br>

                        <div class="text-end">
                            <form action="{{ route('setDailyFine') }}" method="POST">
                                @csrf

                                ₱ <input style="border-bottom: 1px solid black" class="border-none bg-transparent text-right" placeholder="" value="{{ $defDailyFine ? $defDailyFine->set_daily_fines : '' }}" type="number" name="set_daily_fines" placeholder="Enter default fine amount" required><br>
                                <button style="margin-bottom: -10px;" class="mt-5 p-3 text-slate-600 hover:text-slate-900 duration-100" type="submit"><i class="fa-solid fa-pen"></i> Save Amount</button>
                            </form>
                        </div>
                    </div>
                </div> --}}

            </div>

            @endif



            </div>
        </div>
        <div class="requestCenter">
            <div class="flex flex-wrap">
                @if ($totalRequests > 0)
                @foreach ($users as $user)
                @if (Auth::user()->is_admin)
                @foreach ($user->requestedBooks as $requestedBook)
                <div class="m-10 shadow-lg dark:bg-dark-eval-1hover:shadow-sm duration-200" style="border-radius: 5px; margin-top: 10px;">
                    @if (Auth::user()->is_admin)
                    <div style="width: 300px; height: 350px;">
                         <div class="p-5">
                             <h1><b><i class="fa-solid fa-user"></i> Borrower</b></h1>
                             {{ $user->name }} <br> <hr> <br>
                             <h1><b><i class="fa-solid fa-id-card"></i> ID Number</b></h1>
                             {{ $user->id_number }} <br> <hr> <br>
                             <h1><b><i class="fa-solid fa-book"></i> Book Title</b></h1>
                             {{ $requestedBook->title }} <br> <hr> <br>
                             <h1><b><i class="fa-solid fa-layer-group"></i> Grade Level</b></h1>
                             {{ $user->grade_level }} <br> <hr>
                         </div>
                     </div>
                    @endif
                    @if (!Auth::user()->is_admin)

                         <div class="p-5">
                             <h1><b><i class="fa-solid fa-book"></i> Book Title</b></h1>
                             {{ $requestedBook->title }} <br> <hr> <br>
                         </div>

                    @endif
                     @if (Auth::user()->is_admin)
                     <div class="flex" style="margin-top: 4px;">
                         <a class="text-center text-blue-600 hover:text-blue-700 duration-100" id="viewButton-{{ $requestedBook->id }}" href="{{ route('viewBook', ['id' => $requestedBook->id]) }}" style="margin: 5px; padding: 10px; border-radius: 5px;"><b> <i class="fa-solid fa-eye"></i> View</b></a>

                         <button type="button" class="open-modal text-green-600 hover:text-green-700 duration-100" onclick="showAcceptanceModal({{ $requestedBook->id }})" style="margin: 5px; padding: 10px; border-radius: 5px;"><b> <i class="fa-solid fa-check"></i> Accept</b></button>
                         <button class="text-red-600 hover:text-red-700 duration-100" type="button" style="width: 123px; border-radius: 5px; padding: 10px; " onclick="showConfirmationModal({{ $requestedBook->id }})"><b><i class="fa-solid fa-trash"></i> Remove</b></button>

                     </div>
                     @endif

                    <div class="flex justify-evenly">
                     @if (!Auth::user()->is_admin)
                     <a class="text-center text-blue-600 hover:text-blue-700 duration-100" id="viewButton-{{ $requestedBook->id }}" href="{{ route('viewBook', ['id' => $requestedBook->id]) }}" style="margin: 5px; padding: 10px; border-radius: 5px;"><b> <i class="fa-solid fa-eye"></i> View</b></a>

                     <form action="{{ route('removeRequest', ['user_id' => $user->id, 'book_id' => $requestedBook->id]) }}" method="POST">
                             @csrf
                             @method('DELETE')
                             <button class="text-red-600 hover:text-red-700 duration-100" type="submit" style="margin: 5px; padding: 10px; border-radius: 5px;"><b> <i class="fa-solid fa-remove"></i> Cancel Request</b></button>
                         </form>
                    @endif
                    </div>

                 </div>
                @endforeach
                @endif


                @if ($user->id == auth()->id())
                    @foreach ($user->requestedBooks as $requestedBook)
                    <div class="m-10 shadow-lg dark:bg-dark-eval-1hover:shadow-sm duration-200" style="border-radius: 5px; margin-top: -15px;">
                        @if (Auth::user()->is_admin)
                        <div style="width: 300px; height: 350px;">
                             <div class="p-5">
                                 <h1><b><i class="fa-solid fa-user"></i> Borrower</b></h1>
                                 {{ $user->name }} <br> <hr> <br>
                                 <h1><b><i class="fa-solid fa-id-card"></i> ID Number</b></h1>
                                 {{ $user->id_number }} <br> <hr> <br>
                                 <h1><b><i class="fa-solid fa-book"></i> Book Title</b></h1>
                                 {{ $requestedBook->title }} <br> <hr> <br>
                                 <h1><b><i class="fa-solid fa-layer-group"></i> Grade Level</b></h1>
                                 {{ $user->grade_level }} <br> <hr>
                             </div>
                         </div>
                        @endif
                        @if (!Auth::user()->is_admin)
                        <div style="width: 300px; height: 350px;">
                             <div class="p-5">
                                 <h1><b><i class="fa-solid fa-book"></i> Book Title</b></h1>
                                 {{ $requestedBook->title }} <br> <hr> <br>
                                 <h1><b><i class="fa-solid fa-user"></i> Author</b></h1>
                                 {{ $requestedBook->author }} <br> <hr> <br>
                                 <h1><b><i class="fa-solid fa-bars-staggered"></i> Subject</b></h1>
                                 {{ $requestedBook->subject }} <br> <hr> <br>
                                 <h1><b><i class="fa-solid fa-location-pin"></i> ISBN</b></h1>
                                 {{ $requestedBook->isbn }} <br> <hr> <br>
                             </div>
                            </div>
                        @endif
                         @if (Auth::user()->is_admin)
                         <div class="flex" style="margin-top: 4px;">
                             <a class="text-center text-blue-600 hover:text-blue-700 duration-100" id="viewButton-{{ $requestedBook->id }}" href="{{ route('viewBook', ['id' => $requestedBook->id]) }}" style="margin: 5px; padding: 10px; border-radius: 5px;"><b> <i class="fa-solid fa-eye"></i> View</b></a>

                             <button type="button" class="open-modal text-green-600 hover:text-green-700 duration-100" onclick="showAcceptanceModal({{ $requestedBook->id }})" style="margin: 5px; padding: 10px; border-radius: 5px;"><b> <i class="fa-solid fa-check"></i> Accept</b></button>

                             <form action="{{ route('removeRequest', ['user_id' => $user->id, 'book_id' => $requestedBook->id]) }}" method="POST">
                                 @csrf
                                 @method('DELETE')
                                 <button class="text-red-600 hover:text-red-700 duration-100" type="submit" style="margin: 5px; padding: 10px; border-radius: 5px;"><b> <i class="fa-solid fa-remove"></i> Remove</b></button>
                             </form>
                         </div>
                         @endif

                        <div class="flex justify-evenly">
                         @if (!Auth::user()->is_admin)
                         <a class="text-center text-blue-600 hover:text-blue-700 duration-100" id="viewButton-{{ $requestedBook->id }}" href="{{ route('viewBook', ['id' => $requestedBook->id]) }}" style="margin: 5px; padding: 10px; border-radius: 5px;"><b> <i class="fa-solid fa-eye"></i> View</b></a>
                            {{-- <button class="open-modal text-green-600 hover:text-green-700 duration-100"  style="margin: 5px; padding: 10px; border-radius: 5px; visibility: hidden">Accept</button> --}}








































                            <button class="text-red-600 hover:text-red-700 duration-100" type="button" style="width: 123px; border-radius: 5px; padding: 10px; " onclick="showConfirmationModal({{ $requestedBook->id }})"><b><i class="fa-solid fa-trash"></i> Cancel</b></button>


{{--
                            <form action="{{ route('removeRequest', ['user_id' => $user->id, 'book_id' => $requestedBook->id]) }}" method="POST">
                                 @csrf
                                 @method('DELETE')
                                 <button class="text-red-600 hover:text-red-700 duration-100" type="submit" style="margin: 5px; padding: 10px; border-radius: 5px;"><b> <i class="fa-solid fa-remove"></i> Cancel Request</b></button>
                             </form> --}}
                        @endif
                        </div>

                     </div>
                    @endforeach
                @endif
                    @foreach ($user->requestedBooks as $requestedBook)

                    <div  id="confirmAcceptModal-{{ $requestedBook->id }}" style="overflow-y: auto; display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); backdrop-filter: blur(5px); z-index: 1;">
                        <div class="modalWidth" style="transform: translateY(35px); background-color: white; border-radius: 5px; margin: 100px auto; padding: 20px; text-align: left;">
                            <div class="flex justify-between">
                                <h2><b><i class="fa-solid fa-calendar-days"></i> Set Date</b></h2>
                                <button class="rounded-lg p-4 text-slate-400 hover:text-slate-500 duration-100" style="transform: translateY(-15px); width: 50px;" onclick="hideAcceptanceModal({{ $requestedBook->id }})"><i class="fa-solid fa-xmark"></i></button>
                            </div>
                            <hr> <br>

                            <p>
                                <form action="{{ route('acceptRequest', ['user' => $user, 'book' => '__REQUESTEDBOOK_ID__']) }}" method="POST">
                                    @csrf
                                    <div>
                                        <label for="date_pickup"><b><i class="fa-solid fa-boxes-packing"></i> Date Pickup</b></label> <br>
                                        <input class="border-none" type="datetime-local" id="date_pickup_{{ $requestedBook->id }}" name="date_pickup" required>
                                    </div>
                                    <br>
                                    <div>
                                        <label for="date_return"><b><i class="fa-solid fa-rotate-left"></i> Date Return</b></label> <br>
                                        <input class="border-none" type="datetime-local" id="date_return_{{ $requestedBook->id }}" name="date_return" required>
                                    </div>
                                    <br>
                                    <div>
                                        <h1><b>You can choose a return date a week from today.</b></h1> <br>
                                        <button class="bg-orange-400 hover:bg-orange-500 duration-100 p-3 rounded-lg text-white" type="button" onclick="setPickupReturnDate(1, {{ $requestedBook->id }})">1 week</button>
                                        <button class="bg-orange-400 hover:bg-orange-500 duration-100 p-3 rounded-lg text-white" type="button" onclick="setPickupReturnDate(2, {{ $requestedBook->id }})">2 weeks</button>
                                        <button class="bg-orange-400 hover:bg-orange-500 duration-100 p-3 rounded-lg text-white" type="button" onclick="setPickupReturnDate(3, {{ $requestedBook->id }})">3 weeks</button>

                                    </div>
                                    <br>
                                   <br>
                                    <hr>
                                        <br>
                                    <div class="flex justify-end">
                                        <button type="button" class="rounded-lg p-4 text-slate-600 hover:text-slate-700 duration-100" style="width: 125px;" onclick="hideAcceptanceModal({{ $requestedBook->id }})"><i class="fa-solid fa-ban"></i> Cancel</button> &nbsp;
                                        <button type="submit" class="rounded-lg p-4  text-green-600 hover:text-green-700 duration-100" style="width: 125px;"><i class="fa-solid fa-check"></i>  Accept</button>
                                    </div>
                                </form>

                            </p>

                        </div>

                    </div>





                {{-- Delete Modal --}}
                <div id="confirmDeleteModalRemove-{{ $requestedBook->id }}" style="margin-top: 50px; overflow-y: auto; display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); backdrop-filter: blur(5px); z-index: 1;">
                    <div class="modalWidthRemove" style="background-color: white; border-radius: 5px;  margin: 100px auto; padding: 20px; text-align: left;">

                        <div class="flex justify-between">
                            <h2><b><i class="fa-solid fa-address-book"></i> Remove Request</b></h2>
                            <button class="rounded-lg p-4 text-slate-400 hover:text-slate-500 duration-100" style="transform: translateY(-15px); width: 50px;" onclick="hideConfirmationModalRemove({{ $requestedBook->id }})"><i class="fa-solid fa-xmark"></i></button>
                        </div>
                        <hr> <br>
                        <p>Are you sure you want to remove this request?</p>
                        <br>
                        <hr> <br>
                        <div class="">
                            <div class="flex justify-end">
                                <button class="rounded-lg p-4  text-slate-600 hover:text-slate-700 duration-100" style="width: 125px;"  onclick="hideConfirmationModalRemove({{ $requestedBook->id }})"><i class="fa-solid fa-ban"></i> Cancel</button> &nbsp;

                                <form action="{{ route('removeRequest', ['user_id' => $user->id, 'book_id' => $requestedBook->id]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600 hover:text-red-700 duration-100" type="submit" style="margin: 5px; padding: 10px; border-radius: 5px;"><b> <i class="fa-solid fa-remove"></i> Remove</b></button>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>










                    @endforeach
                @endforeach
                    @else
                    <!-- Message for no requests -->
                    <p style="tra">You have no requests.</p>
                @endif
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
                <form action="{{ route('requests') }}" method="GET" class="search-bar">
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


    .modalWidthRemove{
        width: 600px;
    }
    @media (max-width: 1000px) and (max-height: 2000px) {
        .requestCenter{
            display: flex;
            place-content: center;
        }
        .modalWidth{
        width: 100px;
    }
    .modalWidthRemove{
        width: 550px;
    }

    }

    @media (max-width: 600px) and (max-height: 2000px) {
        .requestCenter{
            display: flex;
            place-content: center;
        }
        .modalWidth{
        width: auto;
    }
    .modalWidthRemove{
        width: 300px;
    }

    }

    .modalInput{
        width: 550px;
    }
    .modalWidth{
        width: 300px;
    }


    .modalFlex{
        display: inline-flex;
    }


    .requestCenter{
        display: flex;

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



 function showConfirmationModal(requestedBook) {

            var modal = document.getElementById(`confirmDeleteModalRemove-${requestedBook}`);
            modal.style.display = 'block';

            // Set the action of the form to include the specific book's ID
            var form = modal.querySelector('form');
            form.action = form.action.replace('__REQUESTEDBOOK_ID__', requestedBook);
        }

        function hideConfirmationModalRemove(requestedBook) {

            var modal = document.getElementById(`confirmDeleteModalRemove-${requestedBook}`);
            modal.style.display = 'none';

        }








function clearDateFilter() {
        document.querySelector('input[name="start_date"]').value = '';
        document.querySelector('input[name="end_date"]').value = '';
    }




function showConfirmationModalDateFilter() {
            var modalDate = document.getElementById('confirmFilterDateModal');
            modalDate.style.display = 'block';
        }


        function hideConfirmationDateFilterModal() {

            var modalDate2 = document.getElementById('confirmFilterDateModal');
            modalDate2.style.display = 'none';

        }





// JavaScript to show and hide the loading bar
window.addEventListener('beforeunload', function () {
  document.getElementById('loading-bar').style.width = '100%';
});

window.addEventListener('load', function () {
  document.getElementById('loading-bar').style.width = '0';
});

document.getElementById('showFormButton').addEventListener('click', function() {
        var form = document.getElementById('defaultFineForm');
        if (form.style.display === 'none' || form.style.display === '') {
            form.style.display = 'block';
        } else {
            form.style.display = 'none';
        }
    });



function showAcceptanceModal(requestedBook) {
            var modal = document.getElementById(`confirmAcceptModal-${requestedBook}`);
            modal.style.display = 'block';

            // Set the action of the form to include the specific book's ID
            var form = modal.querySelector('form');
            form.action = form.action.replace('__REQUESTEDBOOK_ID__', requestedBook);
        }


        function hideConfirmationModal() {
            var modal = document.getElementById('confirmAddModal');
            var modal2 = document.getElementById('confirmDeleteModal');

            modal.style.display = 'none';
            modal2.style.display = 'none';

        }

        function hideAcceptanceModal(requestedBook) {
            var modal = document.getElementById(`confirmAcceptModal-${requestedBook}`);
            modal.style.display = 'none';
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

    function setPickupReturnDate(weeksToAdd, bookId) {
    // Get current date and time in the local time zone
    var currentDate = new Date();

    // Set time to midnight (00:00:00)
    currentDate.setHours(0, 0, 0, 0);

    // Format the current date to be compatible with the input type datetime-local
    var formattedDate = formatDateForInput(currentDate);

    // Set the date_pickup to today's date
    document.getElementById('date_pickup_' + bookId).value = formattedDate;

    // Calculate the return date (pickup date + selected weeks)
    var returnDate = new Date(currentDate.getTime() + weeksToAdd * 7 * 24 * 60 * 60 * 1000);
    var formattedReturnDate = formatDateForInput(returnDate);

    // Set the date_return
    document.getElementById('date_return_' + bookId).value = formattedReturnDate;
}



// Helper function to format date for input type datetime-local
function formatDateForInput(date) {
    var year = date.getFullYear();
    var month = (date.getMonth() + 1).toString().padStart(2, '0');
    var day = date.getDate().toString().padStart(2, '0');
    var hours = date.getHours().toString().padStart(2, '0');
    var minutes = date.getMinutes().toString().padStart(2, '0');

    return `${year}-${month}-${day}T${hours}:${minutes}`;
}



</script>
</x-app-layout>
