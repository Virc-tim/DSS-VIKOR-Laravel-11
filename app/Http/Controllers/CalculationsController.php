<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CalculationsController extends Controller
{
    public function store(Request $request)
    {
        $CriCount = $request->input('CriCount');
        $AltCount = $request->input('AltCount');

        $criteria = [];
        for ($i = 1; $i <= $CriCount; $i++) {
            $criteria["C{$i}"] = [
                'name' => $request->input("CriName{$i}"),
                'weight' => $request->input("CriWeight{$i}"),
                'type' => $request->input("CriType{$i}"),
            ];
        }

        $alternatives = [];
        for ($i = 1; $i <= $AltCount; $i++) {
            $alternatives["A{$i}"] = [];
            for ($j = 1; $j <= $CriCount; $j++) {
                $alternatives["A{$i}"][] = $request->input("AltValue{$i}_{$j}");
            }
        }

        return view('result', compact('criteria', 'alternatives', 'CriCount', 'AltCount'));
    }
}
