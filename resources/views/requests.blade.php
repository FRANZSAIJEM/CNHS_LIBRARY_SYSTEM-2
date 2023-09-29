<x-app-layout>
    <x-slot name="header" >
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <h2 class="rounded-md shadow-md bg-white dark:bg-dark-eval-1 p-3 text-xl font-semibold leading-tight">
                <i class="fa-solid fa-code-pull-request"></i> {{ __('Requests') }}
            </h2>
        </div>
    </x-slot>

    <div class="p-6 overflow-hidden bg-white rounded-md shadow-md dark:bg-dark-eval-1">
        <!-- Success Message Container -->
        @if(session('success'))
            <div class="success-message-container">
                <div class="success-message bg-green-100  text-green-700 p-4 mb-4">
                    {{ session('success') }}
                </div>
                <div class="loadingBar"></div>
            </div>
        @endif

        <div class="text-right mb-5">
            <div>
                <div class="" style="display: grid; place-content: center;">
                    <form action="{{ route('requests') }}" method="GET" class="search-bar">
                        <div class="overflow-hidden rounded mb-5 shadow-md dark:bg-dark-eval-1">
                            <input style="width: 1000px;" class="overflow-hidden rounded-md border-none bg-slate-50 searchInpt bg-transparent" type="text" name="book_search" placeholder="ID Number">
                            {{-- <button type="submit" class="search-button text-slate-600 bg-slate-200 hover:text-slate-700 duration-100" style="width: 100px;">Search</button> --}}

                        </div>

                    </form>
                </div>
                <button id="showSearchButton" class="text-slate-600 hover:text-slate-700 duration-100" style="width: 50px; padding: 10px;"><i class="fa-solid fa-search"></i></button>

            </div>
        </div>
        <div class="requestCenter">
            <div class="flex flex-wrap">
                @if (count($users) > 0)
                @foreach ($users as $user)
                    @foreach ($user->requestedBooks as $requestedBook)
                    <div class="m-10 shadow-lg dark:bg-dark-eval-1hover:shadow-sm duration-200" style="border-radius: 5px;">
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
                        <div class="flex" style="margin-top: 4px;">
                            <a class="text-center text-blue-600 hover:text-blue-700 duration-100" id="viewButton-{{ $requestedBook->id }}" href="{{ route('viewBook', ['id' => $requestedBook->id]) }}" style="margin: 5px; padding: 10px; border-radius: 5px;"><b> <i class="fa-solid fa-eye"></i> View</b></a>

                            <button type="button" class="open-modal text-green-600 hover:text-green-700 duration-100" onclick="showAcceptanceModal({{ $requestedBook->id }})" style="margin: 5px; padding: 10px; border-radius: 5px;"><b> <i class="fa-solid fa-check"></i> Accept</b></button>

                            <form action="{{ route('removeRequest', ['user_id' => $user->id, 'book_id' => $requestedBook->id]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600 hover:text-red-700 duration-100" type="submit" style="margin: 5px; padding: 10px; border-radius: 5px;"><b> <i class="fa-solid fa-remove"></i> Remove</b></button>
                            </form>
                        </div>
                    </div>
                    <div  id="confirmAcceptModal-{{ $requestedBook->id }}" style="overflow-y: auto; display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); backdrop-filter: blur(5px); z-index: 1;">
                        <div class="modalWidth" style="background-color: white; border-radius: 5px;  margin: 100px auto; padding: 20px; text-align: left;">
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
                                        <input class="border-none" type="datetime-local" id="date_pickup" name="date_pickup" required>
                                    </div>
                                    <br>
                                    <div>
                                        <label for="date_return"><b><i class="fa-solid fa-rotate-left"></i> Date Return</b></label> <br>
                                        <input class="border-none"  type="datetime-local" id="date_return" name="date_return" required>
                                    </div>
                                    <br>
                                    <div>
                                        <label for="fines"><b><i class="fa-solid fa-money-check-dollar"></i> Fines (optional)</b></label> <br>
                                        <input class="border-none"  type="number" step="0.01" id="fines" name="fines" placeholder="Enter fine amount">
                                    </div> <br>
                                        <hr>
                                        <br>
                                    <div class="flex justify-end">
                                        <button class="rounded-lg p-4 text-slate-600 hover:text-slate-700 duration-100" style="width: 125px;" onclick="hideAcceptanceModal({{ $requestedBook->id }})"><i class="fa-solid fa-ban"></i> Cancel</button> &nbsp;
                                        <button type="submit" class="rounded-lg p-4  text-green-600 hover:text-green-700 duration-100" style="width: 125px;"><i class="fa-solid fa-check"></i>  Accept</button>
                                    </div>
                                </form>
                            </p>

                        </div>
                    </div>
                    @endforeach
                @endforeach
                    @else
                    <!-- Message for no requests -->
                    <p>You have no requests.</p>
                @endif
            </div>
        </div>
            {{-- @foreach ($users as $user)
                        @foreach ($user->requestedBooks as $requestedBook)
                            <div style="background-color: rgb(27, 66, 81); margin: 30px; border-radius: 5px; box-shadow: 10px 10px 20px 5px rgba(0, 0, 0, 0.298);">
                                <div style="background-position: center center; border-radius: 5px; width: 250px; height: 350px; background-size: cover;">
                                    <div style="color: white; padding: 20px; text-shadow: 0px 0px 5px black;">
                                        <div>
                                            <h1><b>Borrower</b></h1>
                                            {{ $user->name }} <br> <br>
                                            <h1><b>ID Number</b></h1>
                                            {{ $user->id_number }} <br> <br>
                                            <h1><b>Book Title</b></h1>
                                            {{ $requestedBook->title }} <br> <br>
                                            <h1><b>Grade Level</b></h1>
                                            {{ $user->grade_level }}
                                        </div>
                                    </div>
                                </div>
                                <div style="display: flex; place-content: center; margin-bottom: 20px;">
                                    <a id="viewButton-{{ $requestedBook->id }}" href="{{ route('viewBook', ['id' => $requestedBook->id]) }}" style="margin: 5px; background-color: rgb(56, 108, 128); color: white; padding: 10px; border-radius: 5px;">View</a>

                                    <button type="button" class="open-modal" onclick="showAcceptanceModal({{ $requestedBook->id }})" style="margin: 5px; background-color: rgb(56, 128, 63); color: white; padding: 10px; border-radius: 5px;">Accept</button>

                                    <form action="{{ route('removeRequest', ['user_id' => $user->id, 'book_id' => $requestedBook->id]) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="margin: 5px; background-color: rgb(128, 56, 56); color: white; padding: 10px; border-radius: 5px;">Remove</button>
                                    </form>

                                </div>
                            </div>
                            <div id="confirmAcceptModal-{{ $requestedBook->id }}" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); z-index: 1;">
                                <div style="background-color: white; border-radius: 5px; width: 300px; margin: 100px auto; padding: 20px; text-align: center;">
                                    <div style="display: inline-flex">
                                        <!-- Form to submit the delete request -->
                                        <form action="{{ route('acceptRequest', ['user' => $user, 'book' => '__REQUESTEDBOOK_ID__']) }}" method="POST">
                                            @csrf
                                            <div class="form-group">
                                                <label for="date_pickup">Date Pickup:</label>
                                                <input type="datetime-local" id="date_pickup" name="date_pickup" required>
                                            </div>

                                            <div class="form-group">
                                                <label for="date_return">Date Return:</label>
                                                <input type="datetime-local" id="date_return" name="date_return" required>
                                            </div>

                                            <div class="form-group">
                                                <label for="fines">Fines (optional):</label>
                                                <input type="number" step="0.01" id="fines" name="fines" placeholder="Enter fine amount">
                                            </div>

                                            <button style="background-color: rgb(146, 146, 146); padding: 10px 20px; margin-right: 10px; border-radius: 5px; color: white;" onclick="hideAcceptanceModal({{ $requestedBook->id }})">Cancel</button>
                                            <button type="submit" style="margin: 5px; background-color: rgb(60, 163, 60);  color: white; padding: 10px; border-radius: 5px; width: 100px;">Accept</button>
                                        </form>
                                   </div>
                                </div>
                            </div>
                        @endforeach
                    @endforeach --}}

    </div>

    {{-- Loading Screen --}}
    <div id="loading-bar" class="loading-bar"></div>
<style>
    .success-message-container {
        position: relative;
    }

    .success-message {
        opacity: 0;
        transform: translateY(-20px);
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
        .requestCenter{
            display: flex;
            place-content: center;
        }
        .modalWidth{
        width: 100px;
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
</script>
</x-app-layout>
