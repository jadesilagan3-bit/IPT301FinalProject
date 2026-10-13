<?php

namespace App\Http\Controllers;

use App\Models\Owner;
use Illuminate\Http\Request;

class OwnerController extends Controller
{
    public function index()
    {
        $owners = Owner::all();
        return view('owners.index', compact('owners'));
    }

    public function create()
    {
        return view('owners.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'contact_no' => 'nullable|string|max:30',
            'email' => 'nullable|email|unique:owners,email',
            'address' => 'nullable|string',
        ]);

        Owner::create($data);

        return redirect()->route('owners.index')
            ->with('success', 'Owner added successfully.');
    }

    public function edit(Owner $owner)
    {
        return view('owners.edit', compact('owner'));
    }

    public function update(Request $request, Owner $owner)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'contact_no' => 'nullable|string|max:30',
            'email' => 'nullable|email|unique:owners,email,' . $owner->id,
            'address' => 'nullable|string',
        ]);

        $owner->update($data);

        return redirect()->route('owners.index')
            ->with('success', 'Owner updated successfully.');
    }

    public function destroy(Owner $owner)
    {
        if ($owner->vehicles()->exists()) {
            return back()->with('error', 'Delete this owner’s vehicles first.');
        }

        $owner->delete();

        return redirect()->route('owners.index')
            ->with('success', 'Owner deleted successfully.');
    }
}
