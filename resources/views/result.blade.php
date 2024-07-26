<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ env('APP_NAME') }}</title>
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

</head>

<body class="bg-cover bg-center my-32" style="background-image: url('{{ asset('images/AngelaReadingBlur2.png') }}');">

    <div class="flex items-center justify-center h-auto flex-col w-auto">

        <div class="bg-[#323232] bg-opacity-80 p-10 rounded-[30px] shadow-lg text-center text-white border-4 border-white w-auto min-w-[50rem]">

            <h1 class="text-6xl mb-10">Results</h1>

            {{-- CRITERIA DATA TABLE --}}
            <h2 class="text-4xl mb-5">Criteria</h2>
            <table class="table-auto w-full mb-10">
                <thead>
                    <tr>
                        <th class="px-4 py-2">Criteria</th>
                        <th class="px-4 py-2">Name</th>
                        <th class="px-4 py-2">Weight</th>
                        <th class="px-4 py-2">Type</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($criteria as $key => $criterion)
                    <tr>
                        <td class="border px-4 py-2">{{ $key }}</td>
                        <td class="border px-4 py-2">{{ $criterion['name'] }}</td>
                        <td class="border px-4 py-2">{{ $criterion['weight'] }}</td>
                        <td class="border px-4 py-2">{{ $criterion['type'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- ALTERNATIVE DATA TABLE --}}
            <h2 class="text-4xl mb-5">Alternatives</h2>
            <table class="table-auto w-full">
                <thead>
                    <tr>
                        <th class="px-4 py-2">Alternative</th>
                        @foreach($criteria as $key => $criterion)
                        <th class="px-4 py-2">{{ $key }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($alternatives as $key => $alternative)
                    <tr>
                        <td class="border px-4 py-2">{{ $key }}</td>
                        @foreach($alternative as $value)
                        <td class="border px-4 py-2">{{ $value }}</td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>

            {{--                PERHITUNGAN VIKOR                --}}

            <?php
                // NORMALISASI BOBOT
                $weight = 0;

                for($i = 1; $i <= $CriCount ; $i++) {
                    $weight += $criteria["C{$i}"]["weight"];
                }

                // NORMALISASI BOBOT
                $NormWeight = [];
                for ($i = 1; $i <= $CriCount; $i++) {
                    $NormWeight["C{$i}"] = [
                        'weight' => $criteria["C{$i}"]["weight"] / $weight
                    ];
                }

                // NORMALISASI NILAI ALTERNATIF
                $NormVal = [];
                for ($i = 1; $i <= $AltCount; $i++) {
                        $NormVal["A{$i}"] = [];
                }

                for ($i = 1; $i <= $CriCount; $i++) {
                    $highestValue = null;
                    $lowestValue = null;

                    $max = 0;
                    $min = 0;

                    if ($criteria["C{$i}"]["type"] == "Benefit") {
                        foreach ($alternatives as $key => $values) {
                            if ($highestValue === null || $values[$i - 1] > $highestValue) {
                                $highestValue = $values[$i - 1];
                            }

                            if ($lowestValue === null || $values[$i - 1] < $lowestValue) {
                                $lowestValue = $values[$i - 1];
                            }
                        }

                        $max = $highestValue;
                        $min = $lowestValue;

                        for ($j = 1; $j <= $AltCount; $j++) {
                            $NormVal["A{$j}"][$i - 1] = ($max - $alternatives["A{$j}"][$i - 1]) / ($max - $min);
                        }
                    }

                    if ($criteria["C{$i}"]["type"] == "Cost") {
                        foreach ($alternatives as $key => $values) {
                            if ($highestValue === null || $values[$i - 1] > $highestValue) {
                                $highestValue = $values[$i - 1];
                            }

                            if ($lowestValue === null || $values[$i - 1] < $lowestValue) {
                                $lowestValue = $values[$i - 1];
                            }
                        }

                        $min = $highestValue;
                        $max = $lowestValue;

                        for ($j = 1; $j <= $AltCount; $j++) {
                            $NormVal["A{$j}"][$i - 1] = ($max - $alternatives["A{$j}"][$i - 1]) / ($max - $min);
                        }
                    }
                }

                // FUNGSI UNTUK MENGKALIKAN NormVal DAN NormWeight
                function multiplyNormValAndNormWeight($normVal, $normWeight, $AltCount, $CriCount) {
                    $result = [];
                    for ($i = 1; $i <= $AltCount; $i++) {
                        $altKey = "A{$i}";
                        for ($j = 1; $j <= $CriCount; $j++) {
                            $criKey = "C{$j}";
                            if (!isset($result[$altKey])) {
                                $result[$altKey] = [];
                            }
                            $result[$altKey][$j - 1] = $normVal[$altKey][$j - 1] * $normWeight[$criKey]['weight'];
                        }
                    }
                    return $result;
                }

                // MENGKALIKAN NormVal DAN NormWeight
                $multipliedValues = multiplyNormValAndNormWeight($NormVal, $NormWeight, $AltCount, $CriCount);

                // MENGHITUNG Nilai S DAN Nilai R
                $S = [];
                $R = [];
                for ($i = 1; $i <= $AltCount; $i++) {
                    $alternativeKey = "A{$i}";
                    $S[$i] = array_sum($multipliedValues[$alternativeKey]);
                    $R[$i] = max($multipliedValues[$alternativeKey]);
                }

                // MENGHITUNG Smax, Smin, Rmax, Rmin
                $Smax = max($S);
                $Smin = min($S);
                $Rmax = max($R);
                $Rmin = min($R);

                // MENGHITUNG Nilai Q
                $Q = [];
                for ($i = 1; $i <= $AltCount; $i++) {
                    $Q[$i] = (0.5 * (($S[$i] - $Smin) / ($Smax - $Smin))) + (0.5 * (($R[$i] - $Rmin) / ($Rmax - $Rmin)));
                }

                // MENGHITUNG Peringkat
                asort($Q); // Sort Q in ascending order to determine ranking
                $ranking = array_keys($Q);

                //$message = "This is a message from PHP!";
                //$testValue = 0;

                //echo "<script>console.log('" . addslashes($message) . "');</script>";
                //echo "<script>console.log('" . addslashes($max) . "');</script>";
                //echo "<script>console.log('" . addslashes($min) . "');</script>";
                //echo "<script>console.log('" . addslashes($highestValue) . "');</script>";
                //echo "<script>console.log('" . addslashes($lowestValue) . "');</script>";

            ?>

            {{--               AKHIR PERHITUNGAN VIKOR                --}}

            {{-- NORMALIZED VALUE DATA TABLE --}}
            <h2 class="text-4xl mb-5 my-8">Normalized Values</h2>
            <table class="table-auto w-full mb-10">
                <thead>
                    <tr>
                        <th class="px-4 py-2">Alternative</th>
                        @foreach(range(0, $CriCount - 1) as $index)
                        <th class="px-4 py-2">C{{ $index+1 }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <?php
                        for ($i = 1; $i <= $AltCount; $i++) {
                            $alternativeKey = "A{$i}";
                            echo "<tr>
                                    <td class='border px-4 py-2'>{$alternativeKey}</td>";
                            for ($index = 0; $index < $CriCount; $index++) {
                                $valueAtIndex = isset($NormVal[$alternativeKey][$index]) ? $NormVal[$alternativeKey][$index] : 'N/A';
                                echo "<td class='border px-4 py-2'>{$valueAtIndex}</td>";
                            }
                            echo "</tr>";
                        }
                    ?>
                </tbody>
            </table>

            {{-- TABLE FOR NORMALIZED VALUES MULTIPLIED BY NORMALIZED WEIGHT --}}
            <h2 class="text-4xl mb-5 my-8">Normalized Values Multiplied by Weight</h2>
            <table class="table-auto w-full mb-10">
                <thead>
                    <tr>
                        <th class="px-4 py-2">Alternative</th>
                        @foreach(range(0, $CriCount - 1) as $index)
                        <th class="px-4 py-2">C{{ $index+1 }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <?php
                        for ($i = 1; $i <= $AltCount; $i++) {
                            $alternativeKey = "A{$i}";
                            echo "<tr>
                                    <td class='border px-4 py-2'>{$alternativeKey}</td>";
                            for ($index = 0; $index < $CriCount; $index++) {
                                $valueAtIndex = isset($multipliedValues[$alternativeKey][$index]) ? $multipliedValues[$alternativeKey][$index] : 'N/A';
                                echo "<td class='border px-4 py-2'>{$valueAtIndex}</td>";
                            }
                            echo "</tr>";
                        }
                    ?>
                </tbody>
            </table>

            {{-- TABLE FOR S AND R VALUES --}}
            <h2 class="text-4xl mb-5 my-8">S and R Values</h2>
            <table class="table-auto w-full mb-10">
                <thead>
                    <tr>
                        <th class="px-4 py-2">Alternative</th>
                        <th class="px-4 py-2">S Value</th>
                        <th class="px-4 py-2">R Value</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        for ($i = 1; $i <= $AltCount; $i++) {
                            $alternativeKey = "A{$i}";
                            echo "<tr>
                                    <td class='border px-4 py-2'>{$alternativeKey}</td>
                                    <td class='border px-4 py-2'>{$S[$i]}</td>
                                    <td class='border px-4 py-2'>{$R[$i]}</td>
                                  </tr>";
                        }
                    ?>
                </tbody>
            </table>

            {{-- RANKING DATA TABLE oh sama nilai Q juga --}}
            <h2 class="text-4xl mb-5 my-8">VIKOR Index (Q) and Ranking</h2>
            <table class="table-auto w-full mb-10">
                <thead>
                    <tr>
                        <th class="px-4 py-2">Alternative</th>
                        <th class="px-4 py-2">VIKOR Index</th>
                        <th class="px-4 py-2">Ranking</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        foreach ($ranking as $rank => $index) {
                            $alternativeKey = "A{$index}";
                            echo "<tr>
                                    <td class='border px-4 py-2'>{$alternativeKey}</td>
                                    <td class='border px-4 py-2'>{$Q[$index]}</td>
                                    <td class='border px-4 py-2'>" . ($rank + 1) . "</td>
                                </tr>";
                        }
                    ?>
                </tbody>
            </table>

        </div>
    </div>

</body>

</html>
