<?php

namespace App\Http\Controllers;


use App\Models\Category;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::with('category')->paginate(10);
        return view('tickets.index', compact('tickets'));
    }
    public function create()
    {
        $categories = Category::all();
        return view('tickets.create', compact('categories'));
    }


    public function store(Request $request)
    {

        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|max:255',
            'image' => 'required|image',
            'startday' => 'required|date',
            'enday' => 'required|date|after:startday',
            'address' => 'required|max:255',
            'quantity' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|max:1024',
            'nguoitochuc' => 'nullable|max:255',
        ]);

        $data['is_active'] = $request->filled('is_active') ? 1 : 0;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('ticket', 'public');
        }


        Ticket::create($data);

        return redirect()->route('tickets.index')->with('success', 'Ticket created successfully!');
    }


    public function edit(Ticket $ticket)
    {
        $categories = Category::all();
        return view('tickets.edit', compact('ticket', 'categories'));
    }

    // Update an existing ticket in the database
    public function update(Request $request, Ticket $ticket)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|max:255',
            'image' => 'nullable|image', 
            'startday' => 'required|date',
            'enday' => 'required|date|after:startday',
            'address' => 'required|max:255',
            'quantity' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|max:1024',
            'nguoitochuc' => 'nullable|max:255',
        ]);
    
        $data['is_active'] = $request->filled('is_active') ? 1 : 0; 
        
        if ($request->hasFile('image')) {
            if ($ticket->image) {
                Storage::delete($ticket->image);
            }
    
            $data['image'] = $request->file('image')->store('ticket', 'public');
        }
    
        $ticket->update($data);
    
        return redirect()->route('tickets.index')->with('success', 'Ticket updated successfully.');
    }
    

    // Delete a ticket
    public function destroy(Ticket $ticket)
    {
        $ticket->delete();
        return redirect()->route('tickets.index')->with('success', 'Ticket deleted successfully.');
    }
}