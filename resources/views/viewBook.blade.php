
<x-app-layout>
    <x-slot name="header" >
        <div class="flex flex-col gap-4 md:flex-row md:items-center">
            <h2 class="rounded-md shadow-md bg-white dark:bg-dark-eval-1 p-3 text-xl font-semibold leading-tight">
                <i class="fa-solid fa-eye"></i> {{ __('View Book') }}
            </h2>

        </div>
    </x-slot>

<div style="display: grid; place-content: center;">
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

        <div class="viewFlex">
           <div class="marginTwo">
                @if (isset($book))
                    <div class="rounded-md shadow-md dark:bg-dark-eval-1" style="background-position: center center; border-radius: 5px; width: 250px; height: 352px; background-size: cover; background-image: url('{{ asset('storage/' . $book->image) }}');" ></div>
                @endif

           </div>

            <div class="marginTwo" style="width: 250px;">
                <h1><b><i class="fa-solid fa-book"></i> Title</b></h1>
                {{$book->title}} <br> <hr> <br>
                <h1><b><i class="fa-solid fa-user"></i> Author</b></h1>
                {{$book->author}} <br> <hr> <br>
                <h1><b><i class="fa-solid fa-bars-staggered"></i> Subject</b></h1>
                {{$book->subject}} <br> <hr> <br>
                <h1><b><i class="fa-solid fa-location-pin"></i> ISBN</b></h1>
                {{$book->isbn}} <br> <hr> <br>
                <h1><b><i class="fa-solid fa-chart-line"></i> Availability</b> </h1>
                <b style="color: {{ $book->availability === 'Not Available' ? 'red' : 'rgb(0, 255, 0)' }}">{{ $book->availability }}</b>
                <br> <hr>
            </div>

        </div>
        <h1><b><i class="fa-solid fa-paragraph"></i> Description</b></h1>
        <div style="display: grid; place-content: center">
            <textarea style="resize: none" class="justDescription p-6 overflow-hidden border-none bg-white rounded-md shadow-md dark:bg-dark-eval-1" name="" id="" rows="4">{{$book->description}}</textarea>
        </div>

    </div>
    <div style="display: grid; place-content: center;" class="mt-5">
        @if (!Auth::user()->is_admin)
        <div >
            <button class="your-button-class {{ $book->availability === 'Not Available' || $book->requestedByUsers->count() > 0 || $userHasAcceptedRequest || $userHasRequestedThisBook ? 'disabled' : '' }}"
                onclick="showConfirmationModal({{ $book->id }})"
                type="submit"
                {{ $book->availability === 'Not Available' || $book->requestedByUsers->count() > 0 || $userHasAcceptedRequest || $userHasRequestedThisBook ? 'disabled' : '' }}
        >
                <b>
                    @if ($book->requestedByUsers->count() > 0)
                        @if ($userHasAcceptedRequest)
                        <i class="fa-solid fa-code-pull-request"></i> Requested by {{ $book->requestedByUsers[0]->name }}
                        @elseif ($userHasRequestedThisBook)
                        <i class="fa-solid fa-code-pull-request"></i> Requested
                        @else
                        <i class="fa-solid fa-code-pull-request"></i> Requested by {{ $book->requestedByUsers[0]->name }}
                        @endif
                    @else
                    <i class="fa-solid fa-code-pull-request"></i> Request
                    @endif
                </b>
            </button>
        </div>
    @endif
    </div>
</div>
    <div id="confirmDeleteModal" style="overflow-y: auto; display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); backdrop-filter: blur(5px); z-index: 1;">
        <div class="acceptModal" style="background-color: white; border-radius: 5px; margin: 100px auto; padding: 20px; text-align: center;">
            <div class="flex justify-between">
                <h2><b><i class="fa-solid fa-address-book"></i> Request</b></h2>
                <button class="rounded-lg p-4 text-slate-400 hover:text-slate-500 duration-100" style="transform: translateY(-15px); width: 50px;" onclick="hideConfirmationModal()"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <hr> <br>
            <p>Once you click okay, We will notify
                you for the pick up time and date,
                Thank you!</p>
            <br>
            <hr> <br>

            <div style="display: inline-flex">
                <button class="text-slate-600 hover:text-slate-700 duration-100" style=" padding: 10px 20px; margin-right: 10px; border-radius: 5px; width: 120px;" onclick="hideConfirmationModal()"><i class="fa-solid fa-ban"></i> Cancel</button>

                <form method="POST" action="{{ route('requestBook', ['id' => $book->id]) }}">

                    @csrf

                    @if ($userHasRequestedThisBook || $book->availability === 'Not Available')
                        <!-- If the user has already requested this book or the availability is "Not Available", show the button as unclickable -->
                        <button type="submit" style="background-color: {{ $book->availability === 'Not Available' || $userHasRequestedThisBook ? 'rgb(83, 83, 83)' : 'white' }}; border-radius: 5px; padding: 10px; color: black; width: 2px;" {{ $book->availability === 'Not Available' || $userHasRequestedThisBook ? 'disabled' : '' }}>
                            <b>{{ $userHasRequestedThisBook ? 'Requested' : 'Request' }}</b>
                        </button>
                    @else

                        <!-- If the user has not requested this book and the availability is not "Not Available", show the button as clickable -->
                        <button class="text-green-600 hover:text-green-700 duration-100" type="submit" style="border-radius: 5px; padding: 10px 20px; width: 140px;">
                            <b><i class="fa-solid fa-check"></i> Request</b>
                        </button>
                    @endif
                    <input type="hidden" name="book_id" value="{{ $book->id }}">
                </form>

            </div>
        </div>
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

    .justDescription{
        width: 600px;
    }
    /* Define your CSS class */
    .acceptModal{
        width: 500px;
    }
.your-button-class {
    border-radius: 5px;
    padding: 10px;
    width: auto;
    color: green; /* Default background color */
    transition: 0.5s;
    /* Add conditional styles using the class */
}
.marginTwo{
    margin: 50px;
    margin-right: 0px;
}

.your-button-class:hover {
    border-radius: 5px;
    padding: 10px;
    width: auto;
    color: rgb(0, 98, 0); /* Default background color */

    /* Add conditional styles using the class */
}
.your-button-class.disabled {
    color: rgb(83, 83, 83); /* Change background color when disabled */
}
    .viewFlex{
        display: flex;
    }

    @media (max-width: 1000px) and (max-height: 640px) {
        .justDescription{
        width: 600px;
    }
        .viewFlex{
        display: flex;

    }
    .acceptModal{
        width: 500px;
    }
    .marginTwo{
    margin: 0px;
    margin-right: 20px;
}

    }

    @media (max-width: 600px) and (max-height: 640px) {
        .justDescription{
            width: 300px;
    }
        .viewFlex{
        display: block;
    }
    .acceptModal{
        width: 300px;
    }

    .marginTwo{
    margin: 20px;
    margin-right: 20px;
}
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
    function showConfirmationModal(bookId) {
            var modal = document.getElementById('confirmDeleteModal');
            modal.style.display = 'block';

            // Set the action of the form to include the specific book's ID
            var form = modal.querySelector('form');
            form.action = form.action.replace('__BOOK_ID__', bookId);
        }

        function hideConfirmationModal() {
            var modal = document.getElementById('confirmDeleteModal');
            modal.style.display = 'none';
        }
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

</script>
</x-app-layout>
