<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VikorController extends Controller
{
    public function store(Request $request)
    {
        $CriCount = $request->input('criteria');
        $AltCount = $request->input('alternatives');
        // You can now use $CriCount and $AltCount as needed

        // Store in session
        session(['CriCount' => $CriCount, 'AltCount' => $AltCount]);

        // Redirect to the calculation view
        return redirect()->route('sessionStore');
    }

    public function sessionStore()
    {
        // Retrieve the stored session data
        $CriCount = session('CriCount');
        $AltCount = session('AltCount');

        // Pass the data to the view
        return view('calculations', compact('CriCount', 'AltCount'));
    }
}
