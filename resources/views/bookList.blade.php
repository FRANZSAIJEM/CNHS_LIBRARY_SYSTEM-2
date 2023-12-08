<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<?php

use App\Models\BorrowCount;
$borrowCount = BorrowCount::first();

?>

<x-app-layout>
    <x-slot name="header" >
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <h2 class="rounded-md shadow-md bg-white dark:bg-dark-eval-1 p-3 text-xl font-semibold leading-tight">
                <i class="fa-solid fa-book"></i> {{ __('Books') }}
            </h2>
        </div>
    </x-slot>

<div>

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
                    <form action="{{ route('bookList') }}" method="GET" class="search-bar" id="bookFilterForm">
                        <div class="overflow-hidden rounded mb-5 shadow-md dark:bg-dark-eval-1 flex">
                            <input style="width: 1000px;" class="overflow-hidden rounded-md border-none bg-slate-50 searchInpt bg-transparent" type="text" name="book_search" placeholder="🔍 Title, Author, Subject">
                            <button style="" type="submit" name="letter_filter" value="" class="hover:bg-slate-300 duration-100 p-1 ps-3 pe-3 rounded-md me-2 m-1 {{ empty(request()->input('letter_filter')) ? 'active' : '' }}">Clear</button>
                        </div>
                        <!-- Add the dropdown select for subjects -->

                        <div style="display: grid; place-content: center">
                            <select style="width: 275px; font-size: 15px;" name="subject_filter" class="p-3 me-2 m-1 rounded-lg" id="subjectFilter">
                                <option style="width: 100px;" value="" {{ empty(request()->input('subject_filter')) ? 'selected' : '' }}>Select Subject</option>
                                <option style="width: 100px;" value="Periodical subscription" {{ (request()->input('subject_filter') == 'Periodical subscription') ? 'selected' : '' }}>Periodical subscription</option>
                                <option style="width: 100px;" value="English" {{ (request()->input('subject_filter') == 'English') ? 'selected' : '' }}>English</option>
                                <option style="width: 100px;" value="Science" {{ (request()->input('subject_filter') == 'Science') ? 'selected' : '' }}>Science</option>
                                <option style="width: 100px;" value="Mathematics" {{ (request()->input('subject_filter') == 'Mathematics') ? 'selected' : '' }}>Mathematics</option>
                                <option style="width: 100px;" value="Senior High" {{ (request()->input('subject_filter') == 'Senior High') ? 'selected' : '' }}>Senior High</option>
                                <option style="width: 100px;" value="Additional Supplementary Readers" {{ (request()->input('subject_filter') == 'Additional Supplementary Readers') ? 'selected' : '' }}>Additional Supplementary Readers</option>
                                <option style="width: 100px;" value="Encyclopedia" {{ (request()->input('subject_filter') == 'Encyclopedia') ? 'selected' : '' }}>Encyclopedia</option>
                            </select>
                        </div>

                    </form>


                    <form action="{{ route('bookList') }}" method="GET" class="search-bar">
                        <div class="flex justify-center flex-wrap mb-3" style="font-size: 15px;">
                            <button type="submit" name="letter_filter" value="" class="bg-slate-200 hover:bg-slate-300 duration-100 p-1 ps-3 pe-3 rounded-lg me-2 m-1 {{ empty(request()->input('letter_filter')) ? 'active' : '' }}"><b>All</b></button>


                            @foreach(range('A', 'Z') as $letter)
                                <button style="margin: 3px;" type="submit" name="letter_filter" value="{{ $letter }}" class="bg-slate-200 hover:bg-slate-300 duration-100 p-1 ps-3 pe-3 rounded-lg {{ (request()->input('letter_filter') == $letter) ? 'active' : '' }}">{{ $letter }}</button>
                            @endforeach


                            <input type="hidden" name="book_search" value="{{ request()->input('book_search') }}">
                        </div>
                    </form>


                    <button class="mb-5">
                        @if (!Auth::user()->is_admin)
                        <h1><b>Borrow Limit: {{ $bookRequestCount ? $bookRequestCount->request_count : '0' }}/{{ $borrowCount ? $borrowCount->count : '' }}</b></h1>
                        @endif
                    </button>
                </div>

                <div id="defaultFineForm" style="z-index: 1; display: none; position: absolute; right: 0; transform: translateX(-45px) translateY(45px)">
                    <div class="p-5 rounded-lg shadow-md bg-slate-50">
                        <h1 class="text-center"><b>Set Borrowing Limit</b></h1><br>
                        <div class="text-end">
                            <form action="{{ route('borrowCounts.store') }}" method="post">
                                @csrf

                                🔢 <input style="border-bottom: 1px solid black" class="border-none bg-transparent text-right" placeholder="" value="{{ $borrowCount ? $borrowCount->count : '' }}" type="number" name="count" id="count" placeholder="Enter default fine amount" required><br>
                                <button style="margin-bottom: -10px;" class="mt-5 p-3 text-slate-600 hover:text-slate-900 duration-100" type="submit"><i class="fa-solid fa-pen"></i> Set Limit</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="flex justify-start">
                    <button style="margin-bottom: -40px;" class="bg-slate-500 hover:bg-slate-600 duration-100 pe-3 ps-3 p-2 me-2 ms-2 rounded-lg text-white" onclick="toggleStyle('defaultBookStyle')"><i class="fa-regular fa-hard-drive"></i></button>

                    <button style="margin-bottom: -40px;" class="bg-slate-500 hover:bg-slate-600 duration-100 pe-3 ps-3 p-2 me-2 ms-2 rounded-lg text-white" onclick="toggleStyle('cardBookStyle')"><i class="fa-solid fa-list"></i></button>
                </div>
                @if (Auth::user()->is_admin)
                <button type="button" class="text-green-600 hover:text-green-700 duration-100" style="width: 150px; border-radius: 5px; padding: 10px;" onclick="showAddConfirmationModal()"><i class="fa-solid fa-plus"></i> Add Book</button>
                <button class="text-slate-600 hover:text-slate-900 duration-100" id="showFormButton"><i class="fa-solid fa-gear"></i></button>
                </div>
                @endif

          </div>


       <div style="">

            <!-- Default Book Style Section -->
            <div class="bookCenter1 cardBookStyle" style="display: none; margin-top: 100px;">
                <div class="">
                    <div class="">
                        <!-- Loop through your books and apply the default style -->
                        <div style="" class="text-left">
                            @foreach ($bookListsDefault as $bookLists)
                              <div class="flex" style="font-size: 13px;">
                                <div style="flex: 1; margin: 10px;" class="bg-slate-100 p-2 rounded-lg shadow-sm">
                                    <a class="" href="{{ route('viewBook', ['id' => $bookLists->id]) }}" style="text-decoration: none;">
                                        <div class="flex">
                                            <b class=""><i class="fas fa-book"></i> Title: &nbsp;</b>{{$bookLists->title}} <br> <br>
                                        </div>
                                    </a>
                                </div>
                              <div>
                                @if (Auth::user()->is_admin)
                                <div class="flex justify-end float-right" style="margin-top: 55px; transform: translateX(-10px); width: 0px;">
                                    <form action="{{ route('editBook.edit', ['id' => $bookLists->id]) }}" method="GET" style="display: inline;">
                                        @csrf
                                        <button class="text-green-600 hover:text-green-700 duration-100" type="submit" style="!important; border: none; border-radius: 5px; padding: 10px; text-decoration: none; cursor: pointer;"><b><i class="fa-solid fa-edit"></i></b></button>
                                    </form>

                                    <!-- Button to trigger the modal -->
                                    <button class="text-red-600 hover:text-red-700 duration-100" type="button" style="cursor: pointer;  border-radius: 5px; padding: 10px; margin-top: -10.5px;" onclick="showConfirmationModal({{ $bookLists->id }})"><b><i class="fa-solid fa-trash"></i></b></button>
                                </div>
                                @endif
                              </div>
                              </div>

                            @endforeach
                        </div>

                    </div>
                </div>
                <div style="display: grid; place-content: center;">

                    <div class="pagination">
                        {{ $bookListsDefault->links() }}
                    </div>

                </div>
            </div>




            <div class="bookCenter defaultBookStyle mt-10" >
                <div class="bookDisplay flex flex-wrap">
                    @foreach ($bookListsCard as $bookLists)
                    <div class="m-16 shadow-lg dark:bg-dark-eval-1 bg-slate-100 hover:shadow-sm duration-200" style="border-radius: 5px; ">
                        <a href="{{ route('viewBook', ['id' => $bookLists->id]) }}" style="text-decoration: none;">
                            <div class="bookImage" style="background-position: center center; border-radius: 5px; background-size: cover; background-image: url('{{ asset('storage/' . $bookLists->image) }}');">
                                <div style="color: white; text-align: center; padding: 10px; text-shadow: 0px 0px 5px black">
                                    <div style="margin-top: 75px;">
                                        <b style="font-size: 25px;">Title</b> <br>
                                        {{$bookLists->title}} <br>
                                        <b style="font-size: 25px;">Author</b> <br>
                                        {{$bookLists->author}} <br>
                                        <b style="font-size: 25px;">Subject</b> <br>
                                        {{$bookLists->subject}} <br>
                                    </div>
                                </div>
                            </div>
                        </a>
                        @if (Auth::user()->is_admin)
                        <div style="text-align: center; margin-top: 4px;">
                            <form action="{{ route('editBook.edit', ['id' => $bookLists->id]) }}" method="GET" style="display: inline;">
                                @csrf
                                <button class="text-green-600 hover:text-green-700 duration-100" type="submit" style="width: 123px !important; border: none; border-radius: 5px; padding: 10px; text-decoration: none; cursor: pointer;"><b><i class="fa-solid fa-edit"></i> Edit</b></button>
                            </form>

                            <!-- Button to trigger the modal -->
                            <button class="text-red-600 hover:text-red-700 duration-100" type="button" style="width: 123px; border-radius: 5px; padding: 10px; " onclick="showConfirmationModal({{ $bookLists->id }})"><b><i class="fa-solid fa-trash"></i> Delete</b></button>
                        </div>
                        @endif
                    </div>
                @endforeach

                </div>

                <div style="display: grid; place-content: center;">

                    <div class="pagination">
                        {{ $bookListsCard->links() }}
                    </div>

                </div>
            </div>








       </div>
    </div>


    {{-- Add Modal --}}
    <div id="confirmAddModal" style="overflow-y: auto; display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); backdrop-filter: blur(5px); z-index: 1;">
        <div class="modalWidth" style="background-color: white; border-radius: 5px;  margin: 100px auto; padding: 20px; text-align: left;">

            <div class="flex justify-between">
                <h2><b><i class="fa-solid fa-address-book"></i> Add Book</b></h2>
                <button class="rounded-lg p-4 text-slate-400 hover:text-slate-500 duration-100" style="transform: translateY(-15px); width: 50px;" onclick="hideConfirmationModal()"><i class="fa-solid fa-xmark"></i></button>
            </div>


            <hr> <br>
            <div class="modalFlex">
                <form action="{{ route('book') }}" method="post" enctype="multipart/form-data">
                    @csrf
                        <div>
                            <label for="title"><b><i class="fa-solid fa-heading"></i> Title</b></label><br>
                            <input placeholder="Title" class="modalInput rounded-lg" type="text" id="title" name="title" required>
                        </div> <br>

                       <div>
                            <label for="author"><b><i class="fa-solid fa-user"></i> Author</b></label><br>
                            <input placeholder="Author" class="modalInput rounded-lg" type="text" id="author" name="author" required>
                       </div> <br>

                       <div>
                            <label for="subject"><b><i class="fa-solid fa-bars-staggered"></i> Subject</b></label><br>
                            <input placeholder="Subject" class="modalInput rounded-lg" type="text" id="subject" name="subject" required>
                        </div> <br>

                        <div>
                            <label for="availability"><b><i class="fa-solid fa-chart-line"></i> Availability</b></label> <br>
                            <input required type="radio" id="availability" name="availability" value="Available"> Available &nbsp;
                            <input required type="radio" id="availability" name="availability" value="Not Available"> Not Available
                        </div> <br>

                        <div>
                            <label for="status"><b><i class="fa-solid fa-chart-simple"></i> Book Status</b></label> <br>
                            <input required type="radio" id="status" name="status" value="Good"> Good &nbsp;
                            <input required type="radio" id="status" name="status" value="Damage"> Damage &nbsp;
                        </div> <br>


                        <div>
                            <label for="isbn"><b><i class="fa-solid fa-code-compare"></i> ISBN</b></label><br>
                            <input placeholder="ISBN" class="modalInput rounded-lg" type="text" id="isbn" name="isbn" required>
                        </div> <br>

                        <div>
                            <label for="publish"><b><i class="fa-solid fa-calendar-days"></i> Year Published</b></label><br>
                            <input placeholder="Publish" class="modalInput rounded-lg" type="text" id="publish" name="publish" required>
                        </div> <br>

                        <div class="overflow-hidden">
                            <label for="description"><b><i class="fa-solid fa-paragraph"></i> Description</b></label><br>
                            <textarea placeholder="Description" class="modalInput rounded-lg" placeholder="Type here!" cols="29" rows="5" id="description" name="description" required></textarea>
                        </div> <br>
                        <p id="charCount" style="visibility: hidden;">Characters remaining: 255</p>

                    <div style="">
                        <label for="description"><b><i class="fa-solid fa-image"></i> Choose cover photo</b></label><br>
                        <input class="shadow-md" type="file" id="image" name="image" accept="image/*" capture="camera" required style="background-color: rgb(230, 230, 230); color:transparent; cursor: pointer; text-align: right; border-radius: 5px; height: 350px; width: 255px;">
                        <img id="previewImage" src="#" style="height: 350px; width: 255px;">
                    </div> <br>
                    <hr>
                    <br>

                   <div class="flex justify-end">
                    <button type="button" class="rounded-lg p-4 text-slate-600 hover:text-slate-700 duration-100" style="width: 125px;"  onclick="hideConfirmationModal()"><i class="fa-solid fa-ban"></i> Cancel</button> &nbsp;
                    <button class="rounded-lg p-4 text-blue-600 hover:text-blue-700 duration-100" style="width: 125px;" type="submit"><i class="fa-solid fa-plus"></i>  Add Book</button>

                   </div>
                    </form>
            </div>
        </div>
    </div>



    {{-- Delete Modal --}}
    <div id="confirmDeleteModal" style="margin-top: 50px; overflow-y: auto; display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); backdrop-filter: blur(5px); z-index: 1;">
        <div class="modalWidth" style="background-color: white; border-radius: 5px;  margin: 100px auto; padding: 20px; text-align: left;">

            <div class="flex justify-between">
                <h2><b><i class="fa-solid fa-address-book"></i> Delete Book</b></h2>
                <button class="rounded-lg p-4 text-slate-400 hover:text-slate-500 duration-100" style="transform: translateY(-15px); width: 50px;" onclick="hideConfirmationModal()"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <hr> <br>
            <p>Are you sure you want to delete this book?</p>
            <br>
            <hr> <br>
            <div class="">
                   <div class="flex justify-end">
                    <button class="rounded-lg p-4  text-slate-600 hover:text-slate-700 duration-100" style="width: 125px;"  onclick="hideConfirmationModal()"><i class="fa-solid fa-ban"></i> Cancel</button> &nbsp;

                    <form action="{{ route('bookList.destroy', ['id' => '__BOOK_ID__']) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button class="rounded-lg p-4  text-red-600 hover:text-red-700 duration-100" style="width: 125px; margin-top: 17.5px;" type="submit"><i class="fa-solid fa-trash"></i>  Confirm</button>
                    </form>
                   </div>

            </div>
        </div>
    </div>





       {{-- Loading Screen --}}
       <div id="loading-bar" class="loading-bar"></div>
</div>
<style>
     .hover-effect {
        transition: transform 0.3s ease-in-out;
    }

    .hover-effect:hover {
        transform: scale(1.1);
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


    .bookImage{
        width: 250px; height: 350px;
        transition: 0.2s;

    }
    .bookImage:hover{
        height: 347px;
        box-shadow: none;
    }
        .bookCenter{
        display: grid;
    }
    .pagination{
        width: 350px;
    }
    /* Initially hide the search bar and set it offscreen */
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


    .modalInput{
        width: 550px;
    }
    .modalWidth{
        width: 600px;
    }
    .modalFlex{
        display: inline-flex;
    }
      #image::-webkit-file-upload-button {
        visibility: hidden;
    }

    #previewImage {
        border-radius: 5px;
        pointer-events: none;
        position: absolute;
        margin-top: -350px;
        object-fit: cover;
    }

    @media (max-width: 1000px) and (max-height: 2000px) {
        .bookCenter{
        display: flex;
        place-content: center;

    }
        .modalWidth{
            width: 550px;
        }
        .modalInput{
            width: 500px;
        }

        .pagination{
            width: 350px;
        }
    }

    @media (max-width: 600px) and (max-height: 2000px) {
        .bookCenter{
        display: flex;
        place-content: center;
        transform: translateX(-50px);
    }
        .modalWidth{
            width: 300px;
        }
        .modalInput{
            width: 250px;
        }
        .pagination{
            width: 250px;
        }
    }
</style>
<script>


$(document).ready(function () {
        // Listen for changes on the subject dropdown
        $('#subjectFilter').change(function () {
            // Get the selected subject
            var selectedSubject = $(this).val();

            // Set the selected subject as a query parameter in the form action
            $('#bookFilterForm').attr('action', '{{ route('bookList') }}' + '?subject_filter=' + encodeURIComponent(selectedSubject));

            // Submit the form
            $('#bookFilterForm').submit();
        });
    });


  // Check for the user's preferred style in local storage
  const userPreferredStyle = localStorage.getItem('userPreferredStyle');

// Set the initial style based on the user's preference or default to 'defaultBookStyle'
const initialStyle = userPreferredStyle || 'defaultBookStyle';

// Call the toggleStyle function with the initial style
toggleStyle(initialStyle);

function toggleStyle(style) {
    const defaultBookStyle = document.querySelector('.defaultBookStyle');
    const cardBookStyle = document.querySelector('.cardBookStyle');

    if (style === 'defaultBookStyle') {
        fadeIn(defaultBookStyle);
        fadeOut(cardBookStyle);
    } else {
        fadeOut(defaultBookStyle);
        fadeIn(cardBookStyle);
    }

    // Store the user's preferred style in local storage
    localStorage.setItem('userPreferredStyle', style);
}

function fadeIn(element) {
    element.style.opacity = 0;
    element.style.display = 'block';

    // Triggering reflow to ensure transition is applied
    element.offsetHeight;

    element.style.opacity = 1;
    element.style.transition = 'opacity 0.5s ease-in';
}

function fadeOut(element) {
    element.style.opacity = 1;
    element.style.transition = 'opacity 0.5s ease-out';

    setTimeout(() => {
        element.style.opacity = 0;
    }, 10); // Set a small timeout for the transition to start

    setTimeout(() => {
        element.style.display = 'none';
    }, 500); // Adjust the timeout to match the transition duration
}

document.getElementById('showFormButton').addEventListener('click', function() {
        var form = document.getElementById('defaultFineForm');
        if (form.style.display === 'none' || form.style.display === '') {
            form.style.display = 'block';
        } else {
            form.style.display = 'none';
        }
    });


        const textarea = document.getElementById('description');
    const charCount = document.getElementById('charCount');
    const maxChars = 255;

    textarea.addEventListener('input', function () {
        const remainingChars = maxChars - textarea.value.length;
        charCount.textContent = `Characters remaining: ${remainingChars}`;
        if (remainingChars < 0) {
            textarea.value = textarea.value.slice(0, maxChars);
            charCount.textContent = 'Character limit reached';
        }
    });
       function showAddConfirmationModal(bookId) {
            var modal = document.getElementById('confirmAddModal');
            modal.style.display = 'block';

            // Set the action of the form to include the specific book's ID
            var form = modal.querySelector('form');
            form.action = form.action.replace('__BOOK_ID__', bookId);
        }









        function showConfirmationModal(bookId) {
            var modal = document.getElementById('confirmDeleteModal');
            modal.style.display = 'block';

            // Set the action of the form to include the specific book's ID
            var form = modal.querySelector('form');
            form.action = form.action.replace('__BOOK_ID__', bookId);
        }


        function hideConfirmationModal() {
            var modal = document.getElementById('confirmAddModal');
            var modal2 = document.getElementById('confirmDeleteModal');

            modal.style.display = 'none';
            modal2.style.display = 'none';

        }








        const imageInput = document.getElementById('image');
        const previewImage = document.getElementById('previewImage');



        imageInput.addEventListener('change', function(event) {
            const selectedFile = event.target.files[0];
            if (selectedFile) {
            const objectURL = URL.createObjectURL(selectedFile);
            previewImage.src = objectURL;
            previewImage.style.display = 'block';
            }
        });

// JavaScript to show and hide the loading bar
window.addEventListener('beforeunload', function () {
  document.getElementById('loading-bar').style.width = '100%';
});

window.addEventListener('load', function () {
  document.getElementById('loading-bar').style.width = '0';
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


    // window.addEventListener('DOMContentLoaded', (event) => {
    //     const bookDisplay = document.querySelector('.bookDisplay');
    //     if (bookDisplay) {
    //         setTimeout(() => {
    //             bookDisplay.style.opacity = '1';
    //             bookDisplay.style.transform = 'translateY(0)';


    //         }, 100);
    //     }
    // });


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
