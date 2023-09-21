<x-app-layout>
    <x-slot name="header" >
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <h2 class="text-xl font-semibold leading-tight">
                {{ __('Add Book') }}
            </h2>
        </div>
    </x-slot>
<style>
    #image::-webkit-file-upload-button {
        visibility: hidden;
    }

    #previewImage {
        border-radius: 5px;
        pointer-events: none;
        position: absolute;
        margin-top: -635px;
        object-fit: cover;
    }

</style>
    <div class="p-6 overflow-hidden bg-white rounded-md shadow-md dark:bg-dark-eval-1"">
        <div style="text-center">
            <form action="{{ route('book') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="w-auto">
                    <label for="title"><b>Title</b></label><br>
                    <input  class="rounded-lg" type="text" id="title" name="title" required>
                </div> <br>

               <div>
                    <label for="author"><b>Author</b></label><br>
                    <input class="rounded-lg" type="text" id="author" name="author" required>
               </div> <br>

               <div>
                    <label for="subject"><b>Subject</b></label><br>
                    <input class="rounded-lg" type="text" id="subject" name="subject" required>
                </div> <br>

                <div>
                    <label for="availability"><b>Availability</b></label> <br>
                    <input required type="radio" id="availability" name="availability" value="Available"> Available &nbsp;
                    <input required type="radio" id="availability" name="availability" value="Not Available"> Not Available
                </div> <br>

                <div>
                    <label for="isbn"><b>ISBN</b></label><br>
                    <input class="rounded-lg" type="text" id="isbn" name="isbn" required>
                </div> <br>

                <div  class="overflow-hidden">
                    <label for="description"><b>Description</b></label><br>
                    <textarea class="rounded-lg" placeholder="Type here!" cols="43" rows="5" id="description" name="description" required></textarea>
                </div> <br>
                <div style="">
                    <input class="shadow-md" type="file" id="image" name="image" accept="image/*" required style="background-color: rgb(230, 230, 230); color:transparent; cursor: pointer; text-align: right; border-radius: 5px; height: 635px; width: 350px;">
                    <img id="previewImage" src="#" style="height: 635px; width: 350px;">
                </div> <br>
                <button class="rounded-lg p-4 text-white" style="background-color: blue" type="submit"><b>Add Book</b></button>
                </form>
        </div>

    </div>

<script>
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


</script>
</x-app-layout>
