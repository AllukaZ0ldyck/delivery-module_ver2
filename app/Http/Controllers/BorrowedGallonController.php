<?php

namespace App\Http\Controllers;

use App\Models\BorrowedGallon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BorrowedGallonController extends Controller
{
    public function index()
    {
        // Show all borrow requests by this user
        $borrowedGallons = BorrowedGallon::where('user_id', Auth::id())->get();
        return view('customer.borrowed-gallons', compact('borrowedGallons'));
    }

    public function create()
    {
        return view('customer.borrow-gallon');
    }

    public function store(Request $request)
    {
        // dd('Reached BorrowedGallon@store', $request->all());
        $request->validate([
            'gallon_type' => 'required|string|max:255',
            'gallon_count' => 'required|integer|min:1',
            'due_date' => 'required|date|after_or_equal:today',
        ]);

        BorrowedGallon::create([
            'user_id' => Auth::id(),
            'gallon_type' => $request->gallon_type,
            'gallon_count' => $request->gallon_count,
            'due_date' => $request->due_date,
            'status' => 'pending', // requires admin approval
        ]);

        return redirect()->route('borrow-gallon.index')
                         ->with('success', 'Your borrow request has been submitted for approval.');
    }
}
