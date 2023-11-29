

<x-app-layout>
    <x-slot name="header" >
        <div class="flex flex-col gap-4 md:flex-row md:items-center">
            <h2 class="rounded-md shadow-md bg-white dark:bg-dark-eval-1 p-3 text-xl font-semibold leading-tight">
                <i class="fa-solid fa-eye"></i> {{ __('View Book') }}
            </h2>

        </div>
    </x-slot>

<div style="display: grid; place-content: center;" class="p-6 overflow-hidden bg-white rounded-md shadow-md dark:bg-dark-eval-1">
    <a href="{{ route('bookList') }}" class="rounded-md bg-white dark:bg-dark-eval-1 p-3 text-xl font-semibold"><i class="fa-solid fa-chevron-left"></i> Back</a>

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

    <div class="viewAndComment overflow-hidden">

        <div class="p-6 overflow-hidden ">


            <div class="viewFlex rounded-md mb-5">
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
                <textarea disabled style="resize: none" class="justDescription p-6 overflow-hidden border-none bg-slate-100 rounded-md shadow-md dark:bg-dark-eval-1" name="" id="" rows="11">{{$book->description}}</textarea>
            </div>

        </div>

        <div class="m-5" style="">
            <div style="">
                <div style="">
                    <h1 class="ms-5"><b><i class="fa-solid fa-comment"></i> Add a Comment</b></h1>
                    <form action="{{ route('comments.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="book_id" value="{{ $book->id }}">
                        <div style="display: grid; place-content: center">
                            <textarea required style="resize: none" class="p-6 overflow-hidden border-none bg-white rounded-md shadow-md dark:bg-dark-eval-1" name="comment" id="comment" rows="1" cols="75" placeholder="Enter your comment here"></textarea>
                        </div>
                        <div class="p-5 text-right">
                            <button type="submit" class="bg-slate-600 p-3 ps-5 pe-5 rounded-md hover:bg-slate-700 duration-100 text-white"><i class="fa-solid fa-paper-plane"></i></button>
                        </div>
                    </form>
                </div>
                <div class="p-5" style="display: grid; place-content: center;">
                    @if ($book->comments->count() > 0)
                        <ul>
                    <h1 style="margin-left: 20px;"><b><i class="fa-solid fa-comment"></i> Comments</b></h1>
                            @foreach ($book->comments as $comment)
                                <li>
                                    <div class="text-black p-2 rounded-md shadow-md bg-slate-200 m-5 forcomments" style="margin-bottom: 50px;">
                                        <div class="reply">

                                        <div class="flex justify-between">
                                            <div class="flex mb-2">
                                                <img width="40" height="40" src="{{ asset($comment->user->image) }}" alt="">
                                                <strong class="ms-2 mt-3" style="font-size: 13px;"> {{ $comment->user->name }}</strong>
                                            </div>

                                            <br>
                                            @if (auth()->check() && auth()->user()->id === $comment->user_id)
                                            <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="me-3 text-red-600 rounded-md hover:text-red-700 duration-100">
                                                    <i class="fa-solid fa-remove"></i> </button>
                                            </form>
                                            @endif
                                        </div>

                                        <div class="comment-content">
                                            <form method="POST" action="{{ route('comments.update', ['comment' => $comment->id])}}">
                                                @csrf
                                                @method('PUT')
                                                <div class="float-right" style="z-index: 2; margin-bottom: -50px; transform: translateY(75px);">
                                                    <button type="submit" class="hidden text-slate-600 p-3 ps-5 pe-5 rounded-md hover:text-slate-700 duration-100 " id="comment-button-{{ $comment->id }}"><b>Save</b>
                                                    </button>
                                                </div>

                                                <textarea style="resize: none; min-height: 50px; max-height: 500px; overflow-y: auto; border: 1px solid #ccc;" name="comment" id="edit-comment-{{ $comment->id }}" disabled class="p-3 comments overflow-hidden border-none bg-white rounded-md dark:bg-dark-eval-1">{{ $comment->comment }}</textarea>

                                            </form>
                                        </div>

                                            <div id="replies-section-{{ $comment->id }}" class="replies-section">
                                                <div class="mt-5">
                                                    <h1 style=""><b><i class="fa-solid fa-comment"></i> Replies</b></h1>

                                                    <div class="mt-5">
                                                        @isset($comment->replies)
                                                            @foreach ($comment->replies as $reply)
                                                                <div class="reply shadow-md mb-10 rounded-md">

                                                                    <div class="flex justify-between">
                                                                       <div class="mb-2 flex">
                                                                        <img width="40" height="40" src="{{ asset($reply->user->image) }}" alt="">
                                                                        <strong class="ms-2 mt-3" style="font-size: 13px;">{{ $reply->user->name }}</strong>
                                                                       </div>
                                                                        @if(auth()->id() === $reply->user->id)
                                                                        <form method="POST" action="{{ route('replies.destroy', ['reply' => $reply->id]) }}">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="submit" class="text-red-600 p-2 ps-5 pe-5 rounded-md hover:text-red-700 duration-100">
                                                                                <i class="fa-solid fa-remove"></i>
                                                                            </button>
                                                                        </form>
                                                                        @endif
                                                                    </div>

                                                                    {{-- <textarea name="" id="" disabled style="resize: none;" class="replies border-none shadow-md rounded-md">{{ $reply->reply }}</textarea> --}}
                                                                    <div class="reply-content">
                                                                        <form method="POST" action="{{ route('replies.update', ['reply' => $reply->id]) }}">
                                                                            @csrf
                                                                            @method('PUT')
                                                                            <div class="float-right" style="z-index: 2; margin-bottom: -50px; transform: translateY(75px) translateX(15px);">
                                                                                <button type="submit" class="hidden text-slate-600 p-3 ps-5 pe-5 rounded-md hover:text-slate-700 duration-100 " id="reply-button-{{ $reply->id }}"><b>Save</b>
                                                                                </button>
                                                                            </div>
                                                                            <textarea name="reply" id="edit-reply-{{ $reply->id }}" disabled style="resize: none;" class="p-3 ms-1 replies border-none rounded-md">{{ $reply->reply }}</textarea>

                                                                        </form>
                                                                    </div>

                                                                    {{-- Delete and Edit Button and Form --}}
                                                                    <div class="" style="">
                                                                        @if(auth()->id() === $reply->user->id)
                                                                        <div class="flex">
                                                                            <button class="edit-button text-green-600 p-2 ps-5 pe-5 rounded-md hover:text-green-700 duration-100" data-reply-id="{{ $reply->id }}">  <i class="fa-solid fa-edit"></i></button>
                                                                        </div>
                                                                        @endif
                                                                    </div>
                                                                    <h6 class="me-3 text-right" style="font-size: 13px;">{{ \Carbon\Carbon::parse($reply->created_at)->shortRelativeDiff() }}</h6>

                                                                </div>
                                                            @endforeach
                                                        @endisset

                                                            <form action="{{ route('replies.store') }}" method="POST" class="reply-form" class=" data-comment-id="{{ $comment->id }}">
                                                                @csrf
                                                                <div>
                                                                    <input type="hidden" name="comment_id" value="{{ $comment->id }}">
                                                                    <textarea required placeholder="Type your reply here!" name="reply" style="resize: none;" class="replies border-none shadow-md rounded-md reply-textarea"></textarea>
                                                                </div>
                                                                <div class="float-right mb-5">
                                                                    <button type="submit" class="text-slate-600 p-3 ps-5 pe-5 rounded-md hover:text-slate-700 duration-100 ">
                                                                        <i class="fa-solid fa-paper-plane"></i>
                                                                    </button>
                                                                </div>
                                                            </form>

                                                    </div>
                                                </div>

                                            </div>

                                        </div>

                                        <div>


                                            <div class="flex">
                                                  <!-- Like button with a form -->
                                           <!-- Like button with a form -->
                                       <!-- Like button with a form -->
                                                <form method="POST" action="{{ route('comments.like', ['comment' => $comment]) }}" class="like-form">
                                                    @csrf
                                                    <button type="submit" class="like-button p-2 ps-5 pe-5 rounded-md duration-100
                                                        @if (auth()->check() && auth()->user()->hasLikedComment($comment)) text-red-600 hover:text-red-700 @else text-gray-400 hover:text-gray-600 @endif">
                                                        <i class="fa-solid fa-heart"></i> {{ $comment->likes->count() }}
                                                    </button>
                                                </form>




                                                <button class="p-2 ps-5 pe-5 text-blue-600 rounded-md hover:text-blue-700 duration-100"
                                                        onclick="toggleReplies('{{ $comment->id }}')">
                                                        <i class="fa-solid fa-comment-dots"></i>
                                                    {{ $comment->replies->count() }} <!-- Display the reply count here -->
                                                </button>
                                                @if (auth()->check() && auth()->user()->id === $comment->user_id)

                                                    <button type="button" class="edit-button text-green-600 p-2 ps-5 pe-5 rounded-md hover:text-green-700 duration-100" data-comment-id="{{ $comment->id }}">
                                                                <i class="fa-solid fa-edit"></i>
                                                    </button>


                                                @endif

                                            </div>
                                            <h6 class="me-3 text-right" style="font-size: 13px;">{{ \Carbon\Carbon::parse($comment->created_at)->shortRelativeDiff() }}</h6>

                                        </div>


                                    </div>

                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p>No comments yet.</p>
                    @endif
                                    {{-- <!-- Modal -->
                    <div class="modal fade" id="editCommentModal{{$comment->id}}" tabindex="-1" role="dialog" aria-labelledby="editCommentModalLabel{{$comment->id}}" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editCommentModalLabel{{$comment->id}}">Edit Comment</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('comments.edit', $comment->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <div class="form-group">
                                            <label for="editedComment">Edit Your Comment:</label>
                                            <textarea class="form-control" id="editedComment" name="editedComment" rows="4">{{ $comment->comment }}</textarea>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                </div>

            </div>

        </div>

    </div>





















    <div style="display: grid; place-content: center;" class="mt-5">
        @if (!Auth::user()->is_admin)
            <div >
                <button class="your-button-class {{ $book->availability === 'Not Available' || $book->requestedByUsers->count() > 0 || $userHasAcceptedRequest || $userHasRequestedThisBook || ($userBookRequest && $userBookRequest->request_count == $borrowCount->count) || ($userBookRequest && $userBookRequest->request_count >= $borrowCount->count) ? 'disabled' : '' }}"
                    onclick="showConfirmationModal({{ $book->id }})"
                    type="submit"
                    {{ $book->availability === 'Not Available' || $book->requestedByUsers->count() > 0 || $userHasAcceptedRequest || $userHasRequestedThisBook || ($userBookRequest && $userBookRequest->request_count == $borrowCount->count) || ($userBookRequest && $userBookRequest->request_count >= $borrowCount->count) ? 'disabled' : '' }}
                >

                    <b>
                        @if ($book->requestedByUsers->count() > 0)
                            @if (auth()->user()->hasRequestedBook($book->id))
                                <i class="fa-solid fa-code-pull-request"></i> Requested
                            @else
                                <i class="fa-solid fa-code-pull-request"></i> Requested by {{ $book->requestedByUsers[0]->name }}
                            @endif

                            @elseif (!$userHasAcceptedRequestForReturnedBook && $user->hasAcceptedRequestForBook($book->id))
                                <button class="your-button-class"
                                    onclick="showConfirmationModal({{ $book->id }})"
                                    type="submit"


                                    @if (!$user->hasAcceptedRequestForBook($book->id) || $user->hasAcceptedBook() || !$bookReturnStatus)
                                        disabled style="color: black;"
                                    @endif
                                    >



                                    @if (!auth()->user()->hasAcceptedRequestForBook($book->id))
                                    <i class="fa-solid fa-person-walking-arrow-loop-left"></i> Reserved
                                    @else
                                        @if (!empty($book->requestedByUsers) && count($book->requestedByUsers) > 0)
                                        <i class="fa-solid fa-person-walking-arrow-loop-left"></i> Reserved by {{ $book->requestedByUsers[0]->name }}

                                        @else
                                        <i class="fa-solid fa-person-walking-arrow-loop-left"></i> Reserve
                                        @endif
                                    @endif
                                </button>
                        @else
                            <i class="fa-solid fa-code-pull-request"></i> Request
                        @endif
                    </b>
                </button>
                {{-- <p>Book Returned Status: {{ $bookReturnStatus ? 'Returned' : 'Not Returned' }}</p> --}}


            </div>
                {{-- put here --}}

                    {{-- @if (!$userHasAcceptedRequestForReturnedBook && $user->hasAcceptedRequestForBook($book->id))
                        <div>
                            <button class="your-button-class"
                                onclick="showConfirmationModal({{ $book->id }})"
                                type="submit"
                            >
                                <b>Reserve book</b>
                            </button>
                        </div>
                    @endif --}}


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
    .replies{
        width: 525px;
    }
    .comments{
        width: 525px;
    }
    .forcomments{
        width: 550px;
    }
    .viewAndComment{
        display: flex;
    }
    /* Add a transition for the 'max-height' property */
    .replies-section {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.5s ease-in-out; /* Adjust duration and easing as needed */
    }

    /* Define a class to show the replies */
    .replies-section.show {
        max-height: 1000px; /* Adjust to a suitable value that accommodates your content */
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

    @media (max-width: 1000px) and (max-height: 1000px) {
        .replies{
        width: 475px;
    }
        .comments{
        width: 480px;
    }
        .forcomments{
        width: 500px;
    }
        .viewAndComment{
        display: block;
    }
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

    @media (max-width: 600px) and (max-height: 1000px) {
        .replies{
        width: 245px;
    }
        .comments{
        width: 255px;
    }
        .forcomments{
            width: 270px;
    }
        .viewAndComment{
        display: block;
    }
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
        margin-top: 20px;

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
    function toggleReplies(commentId) {
        var repliesSection = document.getElementById(`replies-section-${commentId}`);
        repliesSection.classList.toggle('show');
    }

    document.addEventListener('DOMContentLoaded', function () {
        const editButtons = document.querySelectorAll('.edit-button');
        editButtons.forEach(function (button) {

            button.addEventListener('click', function () {
                const replyId = this.getAttribute('data-reply-id');
                const textarea = document.querySelector(`#edit-reply-${replyId}`);
                const updateButton = document.querySelector(`#reply-button-${replyId}`);
                textarea.disabled = !textarea.disabled; // Toggle disabled attribute
                textarea.focus(); // Focus the textarea
                updateButton.classList.toggle('hidden'); // Toggle hidden class


            });
        });
    });


        document.addEventListener('DOMContentLoaded', function () {
        const editButtons = document.querySelectorAll('.edit-button');
        editButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                const commentId = this.getAttribute('data-comment-id'); // Corrected variable name
                const textarea = document.querySelector(`#edit-comment-${commentId}`);
                const updateButton = document.querySelector(`#comment-button-${commentId}`);
                textarea.disabled = !textarea.disabled; // Toggle disabled attribute
                textarea.focus(); // Focus the textarea
                updateButton.classList.toggle('hidden'); // Toggle hidden class
            });
        });
    });
    document.querySelectorAll('.like-form').forEach(form => {
    form.addEventListener('submit', (event) => {
        event.preventDefault(); // Prevent the default form submission behavior

        axios.post(form.action, new FormData(form))
            .then(response => {
                if (response.data.success) {
                    const likeButton = form.querySelector('.like-button');
                    const likeCount = response.data.likes_count;

                    if (response.data.liked) {
                        // User has liked the comment, update button style and count
                        likeButton.classList.remove('text-gray-400', 'hover:text-gray-600');
                        likeButton.classList.add('text-red-600', 'hover:text-red-700');
                    } else {
                        // User has unliked the comment, update button style and count
                        likeButton.classList.remove('text-red-600', 'hover:text-red-700');
                        likeButton.classList.add('text-gray-400', 'hover:text-gray-600');
                    }

                    // Update the like count
                    likeButton.innerHTML = `<i class="fa-solid fa-heart"></i> ${likeCount}`;
                }
            })
            .catch(error => {
                console.error(error);
            });
    });
});



</script>

</x-app-layout>
