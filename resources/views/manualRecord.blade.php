<x-app-layout>
    <x-slot name="header" >

        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <h2 class="rounded-md shadow-md bg-white dark:bg-dark-eval-1 p-3 text-xl font-semibold leading-tight">
                <a href="javascript:void(0);" onclick="goBack()" class="rounded-md bg-white dark:bg-dark-eval-1 p-3 text-xl font-semibold"><i class="fa-solid fa-chevron-left"></i> Back</a>/ <i class="fa-solid fa-newspaper"></i>  {{ __('Manual Record') }}
            </h2>
        </div>
    </x-slot>

    <div class="p-6 overflow-hidden bg-white rounded-md shadow-md dark:bg-dark-eval-1">
        <div>
            <form method="post" action="{{ route('duplicate-accept-request') }}">
                @csrf
                <div class="shadow-lg p-3 float-left mb-10 me-10" style="width: 480px; display: flex; flex-direction: column; height: 70.5vh; overflow-y: auto;">
                    <h1 class="flex justify-center">Select Student</h1> <br>
                    <div class="">
                        <input type="text" id="searchUser" name="searchUser" oninput="filterUsers()" placeholder="Type to search..."
                        class="mt-1 p-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:border-blue-300 block w-full">
                    </div>
                    @foreach($users as $user)
                        <div class="float-left mb-5 user-item">
                            <input style="opacity: 0;" type="radio" id="user_{{ $user->id }}" name="user_id" value="{{ $user->id }}" onchange="showUserInfo('{{ $user->id }}')" @if($user->is_suspended) disabled @endif>
                            <div>
                                <label class="cursor-pointer rounded-lg" for="user_{{ $user->id }}" @if($user->is_suspended) style="color: gray;" @endif>
                                    <b><i class="fa fa-user"></i></b> {{ $user->name }}
                                </label>
                                <div class="float-right">

                                    <b>
                                        @if($user->is_suspended)
                                        <span class="text-red-300">Suspended</span>
                                        @else
                                            <span class="text-green-500">Active</span>
                                        @endif
                                    </b>
                                </div>


                            </div>
                        </div>
                    @endforeach
                </div>


              <!-- Add a search input -->

              <div class="shadow-lg p-3 float-left mb-10 me-10" style="width: 480px; display: flex; flex-direction: column; height: 70.5vh; overflow-y: auto;">
                <h1 class="flex justify-center">Select Book</h1> <br>
                <div>
                    <input type="text" id="search" name="search" oninput="filterBooks()" placeholder="Type to search..."
                        class="mt-1 p-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:border-blue-300 block w-full">
                </div>

                @foreach($books as $book)

                <div class="float-left mb-5 book-item">
                    <input
                        style="opacity: 0;"
                        type="radio"
                        id="book_{{ $book->id }}"
                        name="book_id"
                        value="{{ $book->id }}"
                        onchange="showBookInfo('{{ $book->id }}')"
                        {{ $book->is_borrowed ? 'disabled' : '' }}

                    >
                    <div>
                        <label
                        style="{{ $book->is_borrowed ? 'color: gray;' : '' }}"
                        class="cursor-pointer rounded-lg" for="book_{{ $book->id }}">
                            <b><i class="fa-solid fa-book"></i></b> {{ $book->title }}
                        </label>
                        <div class="float-right">
                         <b>
                            @if($book->is_borrowed)
                            <span class="text-red-300">Borrowed</span>
                            @else
                                <span class="text-green-500">Available</span>
                            @endif
                         </b>
                            <a class="text-blue-500 hover:text-blue-700 duration-100 p-1 rounded-lg ps-3 pe-3" href="{{ route('viewBook', ['id' => $book->id]) }}" style="font-size: 20px; text-decoration: none;">
                                <i class="fa fa-eye"></i>
                            </a>
                        </div>
                    </div>
                </div>

                @endforeach
            </div>



                <div class="shadow-lg p-3 float-left" style="width: 480px; display: flex; flex-direction: column;">
                    <h1 class="flex justify-center">Details</h1> <br>
                    @foreach($users as $user)
                    <div id="user_info_{{ $user->id }}" class="user-info">
                        <p><b><i class="fa fa-user"></i> Borrower</b><br> {{ $user->name }}</p><hr> <br>
                        <p><b><i class="fa-solid fa-id-card"></i> ID Number</b><br> {{ $user->id_number }}</p><hr> <br>
                        <p><b><i class="fa-solid fa-layer-group"></i> Grade Level</b><br> {{ $user->grade_level }}</p><hr> <br>


                    </div>
                    @endforeach

                    @foreach($books as $book)
                    <div id="book_info_{{ $book->id }}" class="book-info">

                        <p><b><i class="fa-solid fa-book"></i> Book Title</b><br> {{ $book->title }}</p><hr> <br>
                        <p><b><i class="fa-solid fa-chart-line"></i> Availability</b><br> {{ $book->availability }}</p><hr> <br>


                    </div>
                    @endforeach
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

                    <div class="mt-6">
                        <button type="submit"
                            class="float-right bg-orange-500 text-white duration-100 px-4 py-2 rounded hover:bg-orange-700 focus:outline-none focus:shadow-outline-orange active:bg-orange-800">
                            <i class="fa fa-check"></i> Accept
                        </button>
                    </div>
                </div>



            </form>
        </div>
    </div>






{{--
    <div class="p-6 overflow-hidden bg-white rounded-md shadow-md dark:bg-dark-eval-1">
        <div class="max-w-md mx-auto bg-white p-8 rounded shadow-md">
            <form method="post" action="{{ route('duplicate-accept-request') }}">
                @csrf
                @foreach($users as $user)
                <input type="radio" id="user_{{ $user->id }}" name="user_id" value="{{ $user->id }}" onchange="showUserInfo('{{ $user->id }}')">
                <label for="user_{{ $user->id }}">{{ $user->name }}</label>

                @endforeach
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Select User:</label>
                    @foreach($users as $user)
                        <div>
                            <input type="radio" id="user_{{ $user->id }}" name="user_id" value="{{ $user->id }}" onchange="showUserInfo('{{ $user->id }}')">
                            <label for="user_{{ $user->id }}">{{ $user->name }}</label>

                            <div id="user_info_{{ $user->id }}" class="user-info">
                                <!-- User information goes here -->
                                <p>ID Number: {{ $user->id_number }}</p>
                                <p>Name: {{ $user->name }}</p>
                                <p>Grade Level: {{ $user->grade_level }}</p>
                                <p>Instances of late returns: {{ number_format($user->totalFines, 0, '.', '') }}</p>


                            </div>
                        </div>
                    @endforeach
                </div>



                <div class="mb-4">
                    <label for="book_id" class="block text-gray-700 text-sm font-bold mb-2">Select Book:</label>
                    <select id="book_id" name="book_id" required
                        class="w-full border border-gray-300 rounded p-2 focus:outline-none focus:border-indigo-500">
                        @foreach($books as $book)
                            <option value="{{ $book->id }}">{{ $book->title }}, {{$book->author}}</option>
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
                        Accept
                    </button>
                </div>
            </form>
        </div>

    </div> --}}





    <style>
        .user-info {
            display: none;
        }

        .user-info.active {
            display: block;
        }


        .book-info {
            display: none;
        }

        .book-info.active {
            display: block;
        }
    </style>




    <script>
     function goBack() {
        window.history.back();
    }

    function showUserInfo(userId) {
        var userInfos = document.querySelectorAll('.user-info');

        userInfos.forEach(function(userInfo) {
            userInfo.classList.remove('active');
        });

        var selectedUserInfo = document.getElementById("user_info_" + userId);
        if (selectedUserInfo) {
            selectedUserInfo.classList.add('active');
        }
    }


    function filterUsers() {
        var input, filter, users, name, i;
        input = document.getElementById("searchUser");
        filter = input.value.toUpperCase();
        users = document.getElementsByClassName("user-item");

        for (i = 0; i < users.length; i++) {
            name = users[i].getElementsByTagName("label")[0].innerText;
            if (name.toUpperCase().indexOf(filter) > -1) {
                users[i].style.display = "";
            } else {
                users[i].style.display = "none";
            }
        }
    }


    function filterBooks() {
        var input, filter, books, item, title, i;
        input = document.getElementById("search");
        filter = input.value.toUpperCase();
        books = document.getElementsByClassName("book-item");

        for (i = 0; i < books.length; i++) {
            title = books[i].getElementsByTagName("label")[0].innerText;
            if (title.toUpperCase().indexOf(filter) > -1) {
                books[i].style.display = "";
            } else {
                books[i].style.display = "none";
            }
        }
    }





    function showBookInfo(bookId) {
        var bookInfos = document.querySelectorAll('.book-info');

        bookInfos.forEach(function(bookInfo) {
            bookInfo.classList.remove('active');
        });

        var selectedbookInfo = document.getElementById("book_info_" + bookId);
        if (selectedbookInfo) {
            selectedbookInfo.classList.add('active');
        }
    }
    </script>
</x-app-layout>
