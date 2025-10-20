<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    // 🧍‍♂️ Customer side
    public function index()
    {
        $tickets = Ticket::where('user_id', Auth::id())->latest()->get();
        return view('tickets.index', compact('tickets'));
    }

    public function create()
    {
        return view('tickets.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10',
        ]);

        Ticket::create([
            'user_id' => Auth::id(),
            'subject' => $request->subject,
            'message' => $request->message,
        ]);

        return redirect()->route('tickets.index')->with('success', 'Ticket submitted successfully!');
    }

    // 🧑‍💼 Staff side
    public function staffIndex()
    {
        $tickets = Ticket::with('user')->latest()->paginate(10);
        return view('staff-personnel.tickets.index', compact('tickets'));
    }

    public function updateStatus(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,in_progress,resolved',
            'staff_response' => 'nullable|string',
        ]);

        $ticket->update([
            'status' => $request->status,
            'staff_response' => $request->staff_response,
        ]);

        return back()->with('success', 'Ticket updated successfully.');
    }
}
