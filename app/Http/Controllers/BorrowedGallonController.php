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
        $request->validate([
            'borrows' => 'required|array|min:1',

            'borrows.*.gallon_type'  => 'required|string|max:255',
            'borrows.*.gallon_count' => 'required|integer|min:1',
            'borrows.*.due_date'     => 'required|date|after_or_equal:today',
        ]);

        foreach ($request->borrows as $borrow) {
            BorrowedGallon::create([
                'user_id'      => auth()->id(),
                'gallon_type'  => $borrow['gallon_type'],
                'gallon_count' => $borrow['gallon_count'],
                'due_date'     => $borrow['due_date'],
                'status'       => 'pending',
            ]);
        }

        return redirect()->route('borrow-gallon.index')
                        ->with('success', 'Your borrow request has been submitted for approval.');
    }

}
