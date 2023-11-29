<x-app-layout>
    <x-slot name="header" >
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <h2 class="rounded-md shadow-md bg-white dark:bg-dark-eval-1 p-3 text-xl font-semibold leading-tight">

                @if (!Auth::user()->is_admin)
                    <i class="fa-brands fa-rocketchat"></i> {{ __('Librarian') }}
                @endif

                @if (Auth::user()->is_admin)
                    <i class="fa-brands fa-rocketchat"></i> {{ __() }} {{ $user->name }}
                @endif
            </h2>
        </div>
    </x-slot>


    @php
        session(['visited_history_page' => true]);
    @endphp



    <div class="p-6 overflow-hidden bg-white rounded-md shadow-md dark:bg-dark-eval-1">
        <div class="p-6 overflow-hidden bg-white rounded-md shadow-md dark:bg-dark-eval-1">
            @foreach($user->messages as $message)
                <div class="message">
                    @if(auth()->check() && $message->sender->id === auth()->user()->id)
                    <div style="display: grid;" class="justify-end">
                        <div class="flex">
                            @if($message->message_content !== 'Unsent a message')
                                <button class="toggleDeleteButton pe-4 ps-4 text-slate-400" style="transform: translateY(-17px);"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                            @endif
                            <form action="{{ route('delete_message', ['message' => $message->id]) }}" method="POST"
                                style="display: none; position: absolute; transform: translateY(-30px) translateX(-16px);"
                                class="deleteForm">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 ml-2 ps-5 pe-5 p-2 bg-slate-100 hover:bg-slate-300 duration-100 rounded-lg shadow-lg"><i class="fa-solid fa-trash"></i></button>
                            </form>

                            @if($message->message_content === 'Unsent a message')
                                <div class="bg-none border-0 border-slate-400 text-slate-400 p-3 rounded-2xl mb-10" style="display: inline-block; box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;">
                                <button style="visibility: hidden; " class="toggleDeleteButton"><i class="fa-solid fa-ellipsis-vertical"></i></button>

                            @else
                                <div class="bg-orange-400 text-white p-3 mb-10 rounded-2xl " style="display: inline-block; box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;">
                            @endif
                                <span>{{ $message->message_content }}</span>

                            </div>


                        </div>
                    </div>


                        @else
                            @if($message->message_content === 'Unsent a message')
                                <div class="bg-none border-0 border-slate-400 text-slate-400 p-3 rounded-2xl mb-10" style="display: inline-block; box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;">
                            @else
                                <div class="bg-gray-400 text-white p-3 mb-10 rounded-2xl" style="display: inline-block; box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;">
                            @endif
                            <span>{{ $message->message_content }}</span>
                        @endif


                </div>
            @endforeach


            <div style="">
                <form action="{{ route('sendMessage') }}" method="post">
                    @csrf
                    <input id="myInput" class="border-1 rounded-lg  p-5 mt-10 w-full" type="text" autofocus name="message_content" placeholder="Type your message..." required>
                    <input  type="hidden" name="receiver_id" value="{{ $user->id }}"> <!-- Add a hidden input for receiver_id -->
                    <button class="float-right bg-slate-400 p-3 ps-5 pe-5 mt-3 text-white hover:bg-slate-500 duration-100 rounded-lg" type="submit"><i class="fa-solid fa-paper-plane"></i></button>
                </form>
            </div>
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




const toggleButtons = document.querySelectorAll('.toggleDeleteButton');
    const deleteForms = document.querySelectorAll('.deleteForm');

    toggleButtons.forEach((button, index) => {
        button.addEventListener('click', () => {
            if (deleteForms[index].style.display === 'none') {
                deleteForms[index].style.display = 'inline';
            } else {
                deleteForms[index].style.display = 'none';
            }
        });
    });

    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById("myInput").focus();
    });

    </script>
</x-app-layout>
