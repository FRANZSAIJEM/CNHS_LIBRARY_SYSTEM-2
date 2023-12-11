<x-app-layout>
    <x-slot name="header" >

        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <h2 class="rounded-md shadow-md bg-white dark:bg-dark-eval-1 p-3 text-xl font-semibold leading-tight">
                <a href="javascript:void(0);" onclick="goBack()" class="rounded-md bg-white dark:bg-dark-eval-1 p-3 text-xl font-semibold"><i class="fa-solid fa-chevron-left"></i> Back</a>/ <i class="fa-solid fa-history"></i> {{ __('History') }}
            </h2>
        </div>
    </x-slot>


    <div>
    <div class="p-6 overflow-hidden bg-white rounded-md shadow-md dark:bg-dark-eval-1">
        <div class="text-right mb-5">
            <div>
                <div class="" style="display: grid; place-content: center;">
        <form action="{{ route('archivebook') }}" method="GET" class="search-bar" id="bookFilterForm">
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
                </div>
            </div>
        </div>


        <div class="bookCenter defaultBookStyle mt-10" >
            <div class="bookDisplay flex flex-wrap">
        @foreach ($archivedBooks as $archivedBook)
            @if ($archivedBook->book)
            <div class="m-16 shadow-lg dark:bg-dark-eval-1 bg-slate-100 hover:shadow-sm duration-200" style="border-radius: 5px; ">
                <a href="{{ route('viewBook', ['id' => $archivedBook->book->id]) }}" style="text-decoration: none;">
                    <div class="bookImage" style="background-position: center center; border-radius: 5px; background-size: cover; background-image: url('{{ asset('storage/' . $archivedBook->book->image) }}');">
                        <span class="float-right rounded-bl-lg
                            @if($archivedBook->book->condition == 'New Acquired') bg-blue-500 @elseif($archivedBook->book->condition == 'Outdated') bg-red-500 @endif
                            text-white p-1" style="font-size: 10px;">
                            <b>{{ $archivedBook->book->condition }}</b>
                        </span>
                        {{-- <br>
                        <span class="float-right bg-green-500 text-white p-1 rounded-bl-lg" style="font-size: 10px;">
                            <b>6 Copies</b>
                        </span>
--}}

                        <div style="color: white; text-align: center; padding: 10px; text-shadow: 0px 0px 5px black">
                            <div style="margin-top: 55px;">
                                <b style="font-size: 25px;">Title</b> <br>
                                {{$archivedBook->book->title}} <br>
                                <b style="font-size: 25px;">Author</b> <br>
                                {{$archivedBook->book->author}} <br>
                                <b style="font-size: 25px;">Subject</b> <br>
                                {{$archivedBook->book->subject}} <br>
                            </div>
                        </div>
                    </div>
                </a>
                @if (Auth::user()->is_admin || Auth::user()->is_assistant)
                <div style="text-align: center; margin-top: 4px;">

                    @if (!Auth::user()->is_assistant)
                    <form action="{{ route('deleteArchivedBook', ['id' => $archivedBook->book->id]) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button class="text-red-600 hover:text-red-700 duration-100" type="submit" style="border: none; border-radius: 5px; padding: 10px; margin-left: 10px; cursor: pointer;">
                            <b><i class="fa-solid fa-trash"></i> Restore</b>
                        </button>
                    </form>
                    @endif
                </div>
                @endif
            </div>
                {{-- <a class="" href="{{ route('viewBook', ['id' => $archivedBook->book->id]) }}" style="text-decoration: none;">
                    <div class="flex">
                        <b class=""><i class="fas fa-book"></i> Title: &nbsp;</b>{{ $archivedBook->book->title }} <br> <br>
                    </div>
                </a>

                <form action="{{ route('deleteArchivedBook', ['id' => $archivedBook->book->id]) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button class="text-red-600 hover:text-red-700 duration-100" type="submit" style="border: none; border-radius: 5px; padding: 10px; margin-left: 10px; cursor: pointer;">
                        <b><i class="fa-solid fa-trash"></i> Restore</b>
                    </button>
                </form> --}}

            @endif
        @endforeach
            </div>
        </div>
    </div>
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
     function goBack() {
        window.history.back();
    }
document.getElementById('subjectFilter').addEventListener('change', function() {
        document.getElementById('bookFilterForm').submit();
    });

    </script>
</x-app-layout>
