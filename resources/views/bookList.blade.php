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
        <div class="text-right mb-5">
              <div>
                <div class="" style="display: grid; place-content: center;">
                    <form action="{{ route('bookList') }}" method="GET" class="search-bar">
                        <div class="overflow-hidden rounded mb-5 shadow-md dark:bg-dark-eval-1">
                            <input style="width: 1000px;" class="overflow-hidden rounded-md border-none bg-slate-50 searchInpt bg-transparent" type="text" name="book_search" placeholder="Title, Author, Subject">
                            {{-- <button type="submit" class="search-button text-slate-600 bg-slate-200 hover:text-slate-700 duration-100" style="width: 100px;">Search</button> --}}

                        </div>

                    </form>
                </div>

                <button id="showSearchButton" class="text-slate-600 hover:text-slate-700 duration-100" style="width: 50px; padding: 10px;"><i class="fa-solid fa-search"></i></button>
                @if (Auth::user()->is_admin)
              <button type="button" class="bg-green-600 hover:bg-green-700 duration-100" style="width: 150px; border-radius: 5px; padding: 10px; color: white;" onclick="showAddConfirmationModal()"><i class="fa-solid fa-address-book"></i> Add Book</button>
              </div>
            @endif
          </div>
       <div style="display: grid; place-content: center;">
        <div class="flex flex-wrap">
            @foreach ($bookList as $bookLists)
            <div class="m-16 shadow-lg dark:bg-dark-eval-1 bg-slate-100 hover:shadow-sm duration-200" style="border-radius: 5px;">
                <a href="{{ route('viewBook', ['id' => $bookLists->id]) }}" style="text-decoration: none;">
                    <div style="background-position: center center; border-radius: 5px; width: 250px; height: 350px; background-size: cover; background-image: url('{{ asset('storage/' . $bookLists->image) }}');">
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
            {{ $bookList->links() }}
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
                            <label for="isbn"><b><i class="fa-solid fa-code-compare"></i> ISBN</b></label><br>
                            <input placeholder="ISBN" class="modalInput rounded-lg" type="text" id="isbn" name="isbn" required>
                        </div> <br>

                    <div  class="overflow-hidden">
                        <label for="description"><b><i class="fa-solid fa-font"></i> Description</b></label><br>
                        <textarea placeholder="Description" class="modalInput rounded-lg" placeholder="Type here!" cols="29" rows="5" id="description" name="description" required></textarea>
                    </div> <br>
                    <div style="">

                        <input class="shadow-md" type="file" id="image" name="image" accept="image/*" required style="background-color: rgb(230, 230, 230); color:transparent; cursor: pointer; text-align: right; border-radius: 5px; height: 350px; width: 255px;">
                        <img id="previewImage" src="#" style="height: 350px; width: 255px;">
                    </div> <br>
                    <hr>
                    <br>

                   <div class="flex justify-end">
                    <button class="rounded-lg p-4 text-white bg-slate-600 hover:bg-slate-700 duration-100" style="width: 125px;"  onclick="hideConfirmationModal()"><i class="fa-solid fa-ban"></i> Cancel</button> &nbsp;
                    <button class="rounded-lg p-4 text-white bg-blue-600 hover:bg-blue-700 duration-100" style="width: 125px;" type="submit"><i class="fa-solid fa-plus"></i>  Add Book</button>
                   </div>
                    </form>
            </div>
        </div>
    </div>

    {{-- Delete Modal --}}
    <div id="confirmDeleteModal" style="overflow-y: auto; display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); backdrop-filter: blur(5px); z-index: 1;">
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
                    <button class="rounded-lg p-4 text-white bg-slate-600 hover:bg-slate-700 duration-100" style="width: 125px;"  onclick="hideConfirmationModal()"><i class="fa-solid fa-ban"></i> Cancel</button> &nbsp;

                    <form action="{{ route('bookList.destroy', ['id' => '__BOOK_ID__']) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button class="rounded-lg p-4 text-white bg-blue-600 hover:bg-blue-700 duration-100" style="width: 125px;" type="submit"><i class="fa-solid fa-trash"></i>  Confirm</button>
                    </form>
                   </div>

            </div>
        </div>
    </div>
       {{-- Loading Screen --}}
       <div id="loading-bar" class="loading-bar"></div>
</div>
<style>
    .pagination{
        width: 350px;
    }
    /* Initially hide the search bar and set it offscreen */
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
    @media (max-width: 1000px) and (max-height: 640px) {
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

    @media (max-width: 600px) and (max-height: 640px) {
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
</script>
</x-app-layout>
