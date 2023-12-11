<x-app-layout>
    <x-slot name="header" >
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <h2 class="rounded-md shadow-md bg-white dark:bg-dark-eval-1 p-3 text-xl font-semibold leading-tight">
                <a href="javascript:void(0);" onclick="goBack()" class="rounded-md bg-white dark:bg-dark-eval-1 p-3 text-xl font-semibold"><i class="fa-solid fa-chevron-left"></i> Back</a>/ <i class="fa-solid fa-edit"></i> {{ __('Edit Book') }}
            </h2>
        </div>
    </x-slot>

<div style="display: grid; place-content: center;">


    <div class="p-6 overflow-hidden bg-white rounded-md shadow-md dark:bg-dark-eval-1">
        <form action="{{ route('updateBook.update', ['id' => $book->id]) }}" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column;">
            @csrf
            @method('PUT')
            <div>
                <label for="title"><b><i class="fa-solid fa-heading"></i> Title</b></label><br>
                <input placeholder="Title" class="modalInput rounded-lg" type="text" id="title" name="title" required value="{{ $book->title }}">
            </div> <br>

           <div>
                <label for="author"><b><i class="fa-solid fa-user"></i> Author</b></label><br>
                <input placeholder="Author" class="modalInput rounded-lg" type="text" id="author" name="author" required value="{{ $book->author }}">
           </div> <br>

           <div>
                <label for="subject"><b><i class="fa-solid fa-bars-staggered"></i> Subject</b></label><br>
                <input placeholder="Subject" class="modalInput rounded-lg" type="text" id="subject" name="subject" required value="{{ $book->subject }}">
            </div> <br>

            <div>
                <label for="availability"><b><i class="fa-solid fa-chart-line"></i> Availability</b></label> <br>
                <input required {{ $book->availability == 'Available' ? 'checked' : '' }} type="radio" id="availabilityAvailable" name="availability" value="Available"> Available &nbsp;
                <input required {{ $book->availability == 'Not Available' ? 'checked' : '' }} type="radio" id="availabilityNotAvailable" name="availability" value="Not Available"> Not Available
            </div> <br>

            <div>
                <label for="status"><b><i class="fa-solid fa-chart-simple"></i> Book Status</b></label> <br>
                <input required {{ $book->status == 'Good' ? 'checked' : '' }} type="radio" id="statusGood" name="status" value="Good" onchange="handleStatusChange()"> Good &nbsp;
                <input required {{ $book->status == 'Damage' ? 'checked' : '' }} type="radio" id="statusDamage" name="status" value="Damage" onchange="handleStatusChange()"> Damage &nbsp;
                <input required {{ $book->status == 'Lost' ? 'checked' : '' }} type="radio" id="statusLost" name="status" value="Lost" onchange="handleLostStatus()"> Lost &nbsp;
            </div> <br>


            <div>
                <label for="condition"><b><i class="fa-solid fa-chart-simple"></i> Book Condition</b></label> <br>
                <input required {{ $book->condition == 'New Acquired' ? 'checked' : '' }} type="radio" id="condition_new" name="condition" value="New Acquired"> New Acquired &nbsp;
                <input required {{ $book->condition == 'Outdated' ? 'checked' : '' }} type="radio" id="condition_outdated" name="condition" value="Outdated">  &nbsp; Outdated
            </div> <br>


            <div>
                <label for="isbn"><b><i class="fa-solid fa-code-compare"></i> ISBN</b></label><br>
                <input placeholder="ISBN" class="modalInput rounded-lg" type="text" id="isbn" name="isbn" required value="{{ $book->isbn }}">
            </div> <br>

            <div>
                <label for="publish"><b><i class="fa-solid fa-calendar-days"></i> Year Published</b></label><br>
                <input placeholder="Publish" class="modalInput rounded-lg" type="text" id="publish" name="publish" required value="{{ $book->publish }}">
            </div> <br>

        <div  class="overflow-hidden">
            <label for="description"><b><i class="fa-solid fa-paragraph"></i> Description</b></label><br>
            <textarea placeholder="Description" class="modalInput rounded-lg" placeholder="Type here!" cols="29" rows="5" id="description" name="description" required>{{$book->description}}</textarea>
            <p id="charCount">Characters remaining: 255</p>
        </div> <br>
        <div style="">
            <label for="description"><b><i class="fa-solid fa-image"></i> Change cover photo</b></label><br>

            <input class="shadow-md" type="file" id="image" name="image" accept="image/*" capture="camera" style="background-color: rgb(230, 230, 230); color:transparent; cursor: pointer; text-align: right; border-radius: 5px; height: 350px; width: 255px;">
            @if ($book->image)
                <img src="{{ asset('storage/' . $book->image) }}" id="previewImage" src="#" style="height: 350px; width: 255px;">
            @else
                <img id="previewImage" src="#" style="height: 350px; width: 255px;">
            @endif

        </div>
        <br>
        <hr>
        <br>

        <div class="text-right">
            <button class="rounded-lg p-4  text-blue-600 hover:text-blue-700 duration-100" style="width: 175px;" type="submit"><b><i class="fa-solid fa-save"></i> Update Book</b></button>

        </div>
    </form>
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
    @media (max-width: 1000px) and (max-height: 1000px) {
        .modalWidth{
            width: 550px;
        }
        .modalInput{
            width: 500px;
        }
    }

    @media (max-width: 600px) and (max-height: 1000px) {
        .modalWidth{
            width: 300px;
        }
        .modalInput{
            width: 250px;
        }
    }
</style>

<script>
        function goBack() {
        window.history.back();
    }
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
// JavaScript to show and hide the loading bar
window.addEventListener('beforeunload', function () {
  document.getElementById('loading-bar').style.width = '100%';
});

window.addEventListener('load', function () {
  document.getElementById('loading-bar').style.width = '0';
});

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

  function handleLostStatus() {
        // Select the "Not Available" radio button
        document.getElementById("availabilityNotAvailable").checked = true;

        // Disable the "Available" radio button
        document.getElementById("availabilityAvailable").disabled = true;
    }

    function handleStatusChange() {
        // Enable the "Available" radio button only if the "Lost" radio button is not checked
        if (!document.getElementById("statusLost").checked) {
            document.getElementById("availabilityAvailable").disabled = false;
        }
    }
</script>
</x-app-layout>
