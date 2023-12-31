
<x-app-layout>
    <x-slot name="header" >
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <h2 class="rounded-md shadow-md bg-white dark:bg-dark-eval-1 p-3 text-xl font-semibold leading-tight">
                <i class="fa-solid fa-code-pull-request"></i> {{ __('Requests') }}
            </h2>
        </div>
    </x-slot>

    <div class="p-6 overflow-hidden bg-white rounded-md shadow-md dark:bg-dark-eval-1">
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
        <div style="display: grid; place-content: center;">
            <div class="flex flex-wrap">
                @foreach ($users as $user)
                    @foreach ($user->requestedBooks as $requestedBook)
                    <div class="m-10 shadow-lg dark:bg-dark-eval-1 bg-slate-100 hover:shadow-sm duration-200" style="border-radius: 5px;">
                        <div style="width: 250px; height: 350px;">
                            <div class="p-5">
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
                        <div class="flex" style="margin-top: 4px;">
                            <a class="text-blue-600 hover:text-blue-700 duration-100" id="viewButton-{{ $requestedBook->id }}" href="{{ route('viewBook', ['id' => $requestedBook->id]) }}" style="margin: 5px; padding: 10px; border-radius: 5px;"><b> <i class="fa-solid fa-eye"></i> View</b></a>

                            <button type="button" class="open-modal text-green-600 hover:text-green-700 duration-100" onclick="showAcceptanceModal({{ $requestedBook->id }})" style="margin: 5px; padding: 10px; border-radius: 5px;"><b> <i class="fa-solid fa-check"></i> Accept</b></button>

                            <form action="{{ route('removeRequest', ['user_id' => $user->id, 'book_id' => $requestedBook->id]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600 hover:text-red-700 duration-100" type="submit" style="margin: 5px; padding: 10px; border-radius: 5px;"><b> <i class="fa-solid fa-remove"></i> Remove</b></button>
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
                @endforeach
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

</script>
</x-app-layout>
