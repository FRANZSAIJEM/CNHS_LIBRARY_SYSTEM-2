
<!DOCTYPE html>
<html>
<head>



    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .description-title {
            font-size: 1.125rem; /* Equivalent to text-lg in Tailwind */
            margin-bottom: 0.5rem; /* Equivalent to mb-4 in Tailwind */
        }


        .pdf-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1rem;
        }

        .pdf-table th,
        .pdf-table td {
            border: 2px solid #c2cbd6;
            padding: 0.75rem;
            text-align: center;
        }

        .pdf-table tr:hover {
            background-color: #f7fafc;
        }
    </style>
</head>
<body style="font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;">
    <div class="">
        <div class="">

            <div class="container mx-auto p-4">



                <div style="text-align: center;">
                    <img width="100px" height="100px" src="logo.png" alt=""> <br> <br>
                    CNHS LIBRARY SYSTEM <br>
                    Sta. Cruz, Calape, Bohol
                </div> <br> <br> <br>
            </div>



            <div class="container mx-auto p-4">
                <h1 class="description-title"><b style="font-size: 1.5rem;">Description: </b> Total books, and their condition, status, and availabilty.</h1>

                <table class="pdf-table">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 border-b">Total Books</th>
                            <th class="py-2 px-4 border-b">Total Available Books</th>
                            <th class="py-2 px-4 border-b">Total Not Available Books</th>
                            <th class="py-2 px-4 border-b">Total Good Books</th>
                            <th class="py-2 px-4 border-b">Total Damage Books</th>
                            <th class="py-2 px-4 border-b">Total Lost Books</th>



                        </tr>
                    </thead>
                    <tbody>

                        <tr class="hover:bg-gray-100">
                            <td class="py-2 px-4 border-b">{{ $allBooksCount }}</td>
                            <td class="py-2 px-4 border-b">{{ $availableBooksCount }}</td>
                            <td class="py-2 px-4 border-b">{{ $notAvailableBooksCount }}</td>
                            <td class="py-2 px-4 border-b">{{ $goodBooksCount }}</td>
                            <td class="py-2 px-4 border-b">{{ $damageBooksCount }}</td>
                            <td class="py-2 px-4 border-b">{{ $lostBooksCount }}</td>


                        </tr>

                    </tbody>
                </table>
            </div>




        </div>
    </div>
</body>
</html>
