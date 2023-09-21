<x-app-layout>
    <x-slot name="header" >
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <h2 class="rounded-md shadow-md bg-white dark:bg-dark-eval-1 p-3 text-xl font-semibold leading-tight">
                <i class="fa-solid fa-box-archive"></i> {{ __('Transactions') }}
            </h2>
        </div>
    </x-slot>

    <div class="p-6 overflow-hidden bg-white rounded-md shadow-md dark:bg-dark-eval-1">
        <div class="text-center ">
            <div style="display: grid; place-content: center;">
                <div class="flex flex-wrap">
                    @foreach ($acceptedRequests as $acceptedRequest)
                        <div class="m-10 shadow-lg dark:bg-dark-eval-1 bg-slate-100 hover:shadow-sm duration-200" style="border-radius: 5px;">
                            <div style="width: 300px; height: 550px;">
                                <div class="p-5">
                                    <h1><b>Borrower</b></h1>
                                    {{ $acceptedRequest->user->name }} <br> <br>
                                    <h1><b>ID Number</b></h1>
                                    {{ $acceptedRequest->user->id_number }} <br> <br>
                                    <h1><b>Book Title</b></h1>
                                    {{ $acceptedRequest->book_title }} <br> <br>
                                    <h1><b>Borrowed On</b></h1>
                                    {{ $acceptedRequest->date_borrow->format('Y-m-d H:i A') }} <br> <br>
                                    <h1><b>Pickup Date</b></h1>
                                    {{ $acceptedRequest->date_pickup->format('Y-m-d H:i A') }} <br> <br>
                                    <h1><b>Return Date</b></h1>
                                    {{ $acceptedRequest->date_return->format('Y-m-d H:i A') }} <br> <br>
                                    <h1><b>Fines</b></h1> <br>
                                    @if (!is_null($acceptedRequest->fines) && $acceptedRequest->fines > 0.00)
                                        ₱ {{ $acceptedRequest->fines }} <b style="font-size: 10px;">Additional {{$acceptedRequest->fines}} for another day passes</b>
                                    @else
                                        <b style="font-size: 10px;">No fines before return date expires</b>
                                    @endif

                                </div>

                            </div>
                            <form action="{{ route('acceptedRequests.destroy', $acceptedRequest->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="text-green-600 hover:text-green-700 duration-100"
                                    style="width: 150px; border-radius: 5px; padding: 10px;"
                                    type="submit"
                                >
                                    <b><i class="fa-solid fa-rotate-left"></i> Return Book</b>
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
            {{-- <div>
                @foreach ($acceptedRequests as $acceptedRequest)
                <div>
                    <div style="margin: 13px; border-radius: 10px; box-shadow: 10px 10px 20px 5px rgba(0, 0, 0, 0.298);
                                background-color: {{ (!is_null($acceptedRequest->fines) && $acceptedRequest->fines > 0.00) ? 'rgb(71, 50, 20)' : 'rgb(4, 51, 71)' }};
                                padding: 20px">
                        <div>
                            <div style="margin-bottom: 20px; width: 240px;">
                                <b>Borrower</b> <br> {{ $acceptedRequest->user->name }}<br> <br>
                                <b>ID Number</b> <br> {{ $acceptedRequest->user->id_number }}<br> <br>

                                <b>Book Title</b> <br> {{ $acceptedRequest->book_title }} <br> <br>
                                <b>Borrowed on</b> <br> {{ $acceptedRequest->date_borrow->format('Y-m-d H:i A') }} <br> <br>
                                <b>Pickup Date</b> <br> {{ $acceptedRequest->date_pickup->format('Y-m-d H:i A') }} <br> <br>
                                <b>Return Date</b> <br> {{ $acceptedRequest->date_return->format('Y-m-d H:i A') }} <br> <br>
                                <b>Fines</b> <br>
                                @if (!is_null($acceptedRequest->fines) && $acceptedRequest->fines > 0.00)
                                    ₱ {{ $acceptedRequest->fines }} <b style="font-size: 10px;">Additional {{$acceptedRequest->fines}} for another day passes</b>
                                @else
                                    <b style="font-size: 10px;">No fines before return date expires</b>
                                @endif

                                <hr style="margin-top: 20px;">

                                <div style="display: grid; place-items: center; margin-top: 10px; margin-bottom: -30px">
                                    <form action="{{ route('acceptedRequests.destroy', $acceptedRequest->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            style="width: 150px; border-radius: 5px; padding: 10px; background-color: rgb(51, 130, 58)"
                                            type="submit"
                                        >
                                            Return Book
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            </div> --}}
        </div>
    </div>

   {{-- Loading Screen --}}
   <div id="loading-bar" class="loading-bar"></div>
  <style>
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


    </script>
</x-app-layout>
