<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ env('APP_NAME')}}</title>
    @vite('resources/css/app.css')

    <style>
        body {
            background-image: url('{{ asset('images/AngelaReadingBlur2.png') }}');
            background-size: contain;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-position: center;
        }
    </style>

</head>

<body class="bg-cover bg-center" style="background-image: url('{{ asset('images/AngelaReadingBlur2.png') }}');">

    {{-- Box input jumlah Alternatif dan Kriteria --}}
    <div class="flex items-center justify-center h-screen flex-col">

        <h1 class="text-6xl font-bold mb-10 drop-shadow-[0_6px_8px_rgba(0,0,0,1)] text-white">
            VIKOR Decision Support System</h1>

        <form action="{{ route('vikor.store') }}" method="POST">
            @csrf
            <div class="bg-[#323232] bg-opacity-80 p-10 rounded-[30px] shadow-lg text-center text-white border-4 border-white w-[30rem]">

                <label for="criteria" class="block text-4xl mb-2">
                    Jumlah Kriteria</label>
                <div class="bg-white bg-opacity-20 p-3 rounded-[20px] mb-5 drop-shadow-lg border-4 border-white">
                    <input id="criteria" name="criteria" type="number" class="bg-gray-100 bg-opacity-0 text-center text-white mb-0 text-2xl" min="1" max="7" value="1">
                </div>

                <label for="alternatives" class="block text-4xl mb-2">
                    Jumlah Alternatif</label>
                <div class="bg-white bg-opacity-20 p-3 rounded-[20px] mb-5 drop-shadow-lg border-4 border-white">
                    <input id="alternatives" name="alternatives" type="number" class="bg-gray-100 bg-opacity-0 text-center text-white mb-0 text-2xl" min="1" max="10" value="1">
                </div>

                {{-- Tombol Next --}}
                <button type="submit" class="bg-black bg-opacity-10 text-white w-[8rem] text-xl p-3 rounded-lg mt-5 border-4 border-white
                 hover:bg-black hover:bg-opacity-30 focus:outline-none
                ">
                    Next</button>
            </div>
        </form>

    </div>

</body>

</html>
