<?php

namespace App\Http\Controllers;


use App\Models\Place;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PlaceController extends Controller
{
    /**
     * Display a listing of places.
     */
    public function index()
    {
        $places = Place::orderBy('name')->paginate(15);
        $categories = Place::getCategories();
        
        return view('locations.index', compact('places', 'categories'));
    }

    /**
     * Show the form for creating a new place.
     */
    public function create()
    {
        $categories = Place::getCategories();
        return view('locations.create', compact('categories'));
    }

    /**
     * Store a newly created place.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'description' => 'required|string',
            'category' => 'required|string|max:100',
            'is_active' => 'boolean',
            'image' => 'nullable|image|max:2048', // Max 2MB
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('places', 'public');
            $validated['image'] = $path;
        }

        Place::create($validated);

        return redirect()
            ->route('places.index')
            ->with('success', 'Tempat berhasil ditambahkan!');
    }

    /**
     * Display the specified place.
     */
    public function show($id)
    {
        $place = Place::where('place_id', $id)->firstOrFail();
        
        return view('locations.show', compact('place'));
    }

    /**
     * Show the form for editing the specified place.
     */
    public function edit($id)
    {
        $place = Place::where('place_id', $id)->firstOrFail();
        $categories = Place::getCategories();
        
        return view('locations.edit', compact('place', 'categories'));
    }

    /**
     * Update the specified place.
     */
    public function update(Request $request, $id)
    {
        $place = Place::where('place_id', $id)->firstOrFail();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'description' => 'required|string',
            'category' => 'required|string|max:100',
            'is_active' => 'boolean',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($place->image) {
                Storage::disk('public')->delete($place->image);
            }
            
            $path = $request->file('image')->store('places', 'public');
            $validated['image'] = $path;
        }

        $place->update($validated);

        return redirect()
            ->route('places.index')
            ->with('success', 'Tempat berhasil diperbarui!');
    }

    /**
     * Remove the specified place.
     */
    public function destroy($id)
    {
        $place = Place::where('place_id', $id)->firstOrFail();
        $place->delete();

        return redirect()
            ->route('places.index')
            ->with('success', 'Tempat berhasil dihapus!');
    }

    /**
     * Search places.
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        
        $places = Place::query()
            ->where('name', 'like', "%{$query}%")
            ->orWhere('address', 'like', "%{$query}%")
            ->orderBy('name')
            ->limit(20)
            ->get();

        return response()->json($places);
    }
}
