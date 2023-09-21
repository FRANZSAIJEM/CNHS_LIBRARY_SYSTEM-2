<main class="flex flex-col items-center flex-1 px-4 pt-6 sm:justify-center">
    <div>
        <a href="/">
            <h1 style="font-size: 30px;"><b class="flex"><img width="100" src="logo.png" alt=""> &nbsp; CNHS Library <br> &nbsp; System</b></h1>
        </a>
    </div>

    <div class="w-full px-6 py-4 my-6 overflow-hidden bg-white rounded-md shadow-md sm:max-w-md dark:bg-dark-eval-1">
        {{ $slot }}
    </div>
</main>
