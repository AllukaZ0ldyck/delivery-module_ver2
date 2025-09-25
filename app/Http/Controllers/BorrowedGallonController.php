<?php

// app/Http/Controllers/BorrowGallonController.php
namespace App\Http\Controllers;

use App\Models\BorrowedGallon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BorrowedGallonController extends Controller
{
    public function create()
    {
        return view('customer.borrow-gallon');
    }

    public function store(Request $request)
    {
        $request->validate([
            'gallon_count' => 'required|integer|min:1',
            'due_date' => 'required|date',
        ]);

        // Store borrowed gallon data
        BorrowedGallon::create([
            'user_id' => Auth::id(),
            'gallon_count' => $request->gallon_count,
            'due_date' => $request->due_date,
        ]);

        return redirect()->route('borrow-gallon.index')->with('success', 'Gallon borrowed successfully!');
    }

    public function index()
    {
        $borrowedGallons = BorrowedGallon::where('user_id', Auth::id())->get();
        return view('customer.my-borrowed-gallons', compact('borrowedGallons'));
    }
}
