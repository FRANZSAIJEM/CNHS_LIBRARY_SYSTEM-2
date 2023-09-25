<x-guest-layout>
  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Aclonica&family=Lobster+Two&family=Poppins:wght@200;300&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

    <style>
        .imgAnime{
            position: relative;
        }
        .imgAnime2{
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 2s, transform 1s;
        }
        .buttonAnime{
            position: relative;
        }
        .buttonAnime2{
            opacity: 0;
            transform: translateX(-20px);
            transition: opacity 1s, transform 1s;
        }
        .success-message-container {
            position: relative;
        }

        .success-message {
            opacity: 0;
            transform: translateY(-20px);
            transition: opacity 1s, transform 1s;
        }
        .buttons{
            margin-top: 50px;
        }
        .img{
            width: 400px;
        }
        .description{
            width: 500px;
        }
        .cnhs{
            font-size: 50px;
        }
        .leftCenter{
            text-align: left;
            width: auto;
        }
        .content{
            margin-top: 250px;
        }
        .iamFlex{
            display: flex;
        }
        @media (max-width: 1000px) and (max-height: 640px) {
            .content{
            margin-top: 50px;
        }
            .iamFlex{
            display: block;
        }
        }

    @media (max-width: 600px) and (max-height: 640px) {
        .buttons{
            margin-top: 0px;
        }
        .img{
            
            width: 315px;
        }
        .description{
            width: 300px;
        }
        .cnhs{
            font-size: 30px;
        }
        .leftCenter{
            text-align: center;
            width: 300px;
        }
        .content{
            margin-top: 0px;
        }
        .iamFlex{
            display: block;
        }
        }

    </style>

<div class="p-6 overflow-hidden rounded-md dark:bg-dark-eval-1">
        <div style="display: grid; place-items: center;">

            <div style="width: 1200px;" class="content">
            <div class="success-message-container ">
                <div class="success-message iamFlex justify-between">
                    <div class="leftCenter">
                        <h1 style="font-size: 50px"><b>Welcome to </b><br> <h1 class="cnhs">CNHS Library System</h1></h1> <br>
                        <h5 class="description">Our library system offers the convenience of borrowing books for a specified period, making knowledge accessible to all.</h5>

                            <div class="buttonAnime">
                                <div class="buttonAnime2">
                                    @if (Route::has('login'))
                                    <div class="buttons p-6">
                                        @auth
                                            <a href="{{ url('/dashboard') }}" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"><i class="fa-solid fa-chart-line"></i> Dashboard</a>
                                        @else
                                            <a href="{{ route('login') }}" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"><i class="fa-solid fa-right-to-bracket"></i> Log in</a>
                                            &nbsp; &nbsp; |
                                            @if (Route::has('register'))
                                                <a href="{{ route('register') }}" class="ml-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"><i class="fa-solid fa-signature"></i> Sign up</a>
                                            @endif
                                        @endauth
                                    </div>
                                @endif
                                </div>
                            </div>
                    </div>
                    <div class="imgAnime">
                        <div class="imgAnime2">
                            <img class="img" src="logo.png" alt="">
                        </div>
                    </div>
                </div>
            </div>
            </div>

        </div>

</div>

    <style>
    .error-message-container {
        position: relative;
    }

    .error-message {
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
        background-color: #af0000ab;
        transition: width 3s linear;
    }

    </style>
    <script>
            window.addEventListener('DOMContentLoaded', (event) => {
        const errorMessage = document.querySelector('.error-message');
        if (errorMessage) {
            setTimeout(() => {
                errorMessage.style.opacity = '1';
                errorMessage.style.transform = 'translateY(0)';
            }, 100);
        }
    });


    window.addEventListener('DOMContentLoaded', (event) => {
        const errorMessageContainer = document.querySelector('.error-message-container');
        const errorMessage = document.querySelector('.error-message');
        const loadingBar = document.querySelector('.loadingBar');

        if (errorMessage) {
            setTimeout(() => {
                loadingBar.style.width = '100%';
            }, 100);

            setTimeout(() => {
                loadingBar.style.opacity = '0';
                errorMessage.style.opacity = '0';
                errorMessage.style.transform = 'translateY(-20px)';
                setTimeout(() => {
                    errorMessageContainer.remove();
                }, 300);
            }, 3000); // 3 seconds for the loading bar to animate, then 100 milliseconds for the success message to disappear
        }
    });

    
    window.addEventListener('DOMContentLoaded', (event) => {
        const successMessage = document.querySelector('.success-message');
        if (successMessage) {
            setTimeout(() => {
                successMessage.style.opacity = '1';
                successMessage.style.transform = 'translateY(0)';
            }, 500);
        }
    });

    window.addEventListener('DOMContentLoaded', (event) => {
        const successMessage = document.querySelector('.buttonAnime2');
        if (successMessage) {
            setTimeout(() => {
                successMessage.style.opacity = '1';
                successMessage.style.transform = 'translateY(0)';
            }, 1500);
        }
    });

    window.addEventListener('DOMContentLoaded', (event) => {
        const successMessage = document.querySelector('.imgAnime2');
        if (successMessage) {
            setTimeout(() => {
                successMessage.style.opacity = '1';
                successMessage.style.transform = 'translateX(0)';
            }, 2000);
        }
    });
    </script>
</x-guest-layout>



