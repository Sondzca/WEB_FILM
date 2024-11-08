<?php

namespace App\Http\Controllers;

use App\Http\Requests\TicketRequest;
use App\Models\Category;
use App\Models\Ticket;
use Illuminate\Support\Facades\Request;

use function Psy\debug;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::with('category')->paginate(10); // Eager load categories and paginate
        return view('tickets.index', compact('tickets'));
    }
    public function create()
    {
        $categories = Category::all(); // Assuming each ticket belongs to a category
        return view('tickets.create', compact('categories'));
    }

    // Store a new ticket in the database
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:50',
            'image' => 'required|string|max:255',
            'startday' => 'required|date',
            'enday' => 'required|date|after:startday',
            'address' => 'required|string|max:100',
            'price' => 'required|numeric|min:0|max:999999.99',
            'description' => 'nullable|string|max:250',
            'nguoitochuc' => 'nullable|string',
            'noitochuc' => 'nullable|string',
        ]);

        Ticket::create($request->all());

        return redirect()->route('tickets.index')->with('success', 'Ticket created successfully.');
    }

    // Show the form to edit an existing ticket
    public function edit(Ticket $ticket)
    {
        $categories = Category::all();
        return view('tickets.edit', compact('ticket', 'categories'));
    }

    // Update an existing ticket in the database
    public function update(Request $request, Ticket $ticket)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:50',
            'image' => 'required|string|max:255',
            'startday' => 'required|date',
            'enday' => 'required|date|after:startday',
            'address' => 'required|string|max:100',
            'price' => 'required|numeric|min:0|max:999999.99',
            'description' => 'nullable|string|max:250',
            'nguoitochuc' => 'nullable|string',
            'noitochuc' => 'nullable|string',
        ]);

        $ticket->update($request->all());

        return redirect()->route('tickets.index')->with('success', 'Ticket updated successfully.');
    }

    // Delete a ticket
    public function destroy(Ticket $ticket)
    {
        $ticket->delete();
        return redirect()->route('tickets.index')->with('success', 'Ticket deleted successfully.');
    }
}
