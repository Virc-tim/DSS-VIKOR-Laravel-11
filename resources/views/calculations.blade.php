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

        input[type="number"]::-webkit-outer-spin-button,
        input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
    </style>

    <script src="{{ asset('js/app.js') }}" defer></script>

</head>

<body class="bg-cover bg-center my-32" style="background-image: url('{{ asset('images/AngelaReadingBlur2.png') }}');">

    {{-- Box input Nama dan Tipe Kriteria --}}
    <div class="flex items-center justify-center h-auto flex-col w-auto">

        <form action="{{ route('calculation.store') }}" method="POST">
            @csrf

            <input type="hidden" name="CriCount" value="{{ $CriCount }}">
            <input type="hidden" name="AltCount" value="{{ $AltCount }}">

            <div class="bg-[#323232] bg-opacity-80 p-10 rounded-[30px] shadow-lg text-center text-white border-4 border-white w-auto min-w-[50rem]">

                {{--            BAGIAN KRITERIA              --}}

                <label for="criteria" class="block text-5xl mb-10">
                    Tentukan Nama, Beban, dan Tipe Kriteria</label>

                <div class="flex flex-row space-x-10">

                    {{-- NAMA KRITERIA --}}
                    <div class="flex flex-col">
                        <label for="criteria" class="block text-4xl mb-2">Nama</label>
                        <?php for ($i = 1; $i <= $CriCount; $i++): ?>
                            <div class="bg-white bg-opacity-20 p-3 rounded-[20px] mb-5 drop-shadow-lg border-4 border-white w-[30rem] h-[4rem] flex items-center">
                                <span class="text-2xl text-white mr-2">C<?php echo $i; ?></span>
                                <input id="CriteriaName" name="CriName<?php echo $i; ?>" type="text" maxlength="25" class="bg-gray-100 bg-opacity-0 text-center text-white mb-0 text-2xl w-full">
                            </div>
                        <?php endfor; ?>
                    </div>

                    {{-- BEBAN KRITERIA --}}
                    <div class="flex flex-col">
                        <label for="criteria" class="block text-4xl mb-2">Beban</label>
                        <?php for ($i = 1; $i <= $CriCount; $i++): ?>
                            <div class="bg-white bg-opacity-20 p-3 rounded-[20px] mb-5 drop-shadow-lg border-4 border-white w-[10rem] h-[4rem]">
                                <input id="CriteriaWeight" name="CriWeight<?php echo $i; ?>" type="number" class="bg-gray-100 bg-opacity-0 text-center text-white mb-0 text-2xl w-[6rem]" min="0" max="100" step="0.01" value="0">
                            </div>
                        <?php endfor; ?>
                    </div>

                    {{-- TIPE KRITERIA --}}
                    <div class="flex flex-col">
                        <label for="criteria" class="block text-4xl mb-2">Tipe</label>
                        <?php for ($i = 1; $i <= $CriCount; $i++): ?>
                            <div class="bg-white bg-opacity-20 p-3 rounded-[20px] mb-5 drop-shadow-lg border-4 border-white w-[10rem] h-[4rem]">
                                <select id="CriteriaType" name="CriType<?php echo $i; ?>" class="bg-gray-100 bg-opacity-0 text-center text-white mb-0 text-2xl w-full">
                                    <option value="Cost">Cost</option>
                                    <option value="Benefit">Benefit</option>
                                </select>
                            </div>
                        <?php endfor; ?>
                    </div>

                </div>

                {{--            BAGIAN ALTERNATIF              --}}

                <label for="alternative" class="block text-5xl mb-10 my-20">
                    Tentukan Nama Alternatif dan Nilai Keputusannya</label>

                <div class="flex flex-row space-x-4">

                    {{-- NAMA ALTERNATIF --}}
                    <div class="flex flex-col">
                        <label for="alternative" class="block text-4xl mb-2">Nama</label>
                        <?php for ($i = 1; $i <= $AltCount; $i++): ?>
                            <div class="bg-white bg-opacity-20 p-3 rounded-[20px] mb-5 drop-shadow-lg border-4 border-white w-[30rem] h-[4rem] flex items-center">
                                <span class="text-2xl text-white mr-2">A<?php echo $i; ?></span>
                                <input id="AlternativeName" name="AltName<?php echo $i; ?>" type="text" maxlength="25" class="bg-gray-100 bg-opacity-0 text-center text-white mb-0 text-2xl w-full">
                            </div>
                        <?php endfor; ?>
                    </div>

                    {{-- NILAI ALTERNATIF --}}
                    <?php for ($j = 1; $j <= $CriCount; $j++): ?>
                        <div class="flex flex-col">
                            <label for="alternative" class="block text-4xl mb-2">C<?php echo $j; ?></label>
                            <?php for ($i = 1; $i <= $AltCount; $i++): ?>
                                <div class="bg-white bg-opacity-20 p-3 rounded-[20px] mb-5 drop-shadow-lg border-4 border-white w-[8rem] h-[4rem]">
                                    <input id="Alternative Value" name="AltValue<?php echo $i . '_' . $j; ?>" type="number" class="bg-gray-100 bg-opacity-0 text-center text-white mb-0 text-2xl w-[5rem]" min="0" max="99999" step="0.01"
                                    value="<?php echo $i . $j; ?>">
                                </div>
                            <?php endfor; ?>
                        </div>
                    <?php endfor; ?>

                </div>

                {{-- Tombol Next --}}
                <button type="submit" class="bg-black bg-opacity-10 text-white w-[8rem] text-xl p-3 rounded-lg mt-5 border-4 border-white
                 hover:bg-black hover:bg-opacity-30 focus:outline-none">
                    Next
                </button>
            </div>

        </form>

    </div>

</body>

</html>
