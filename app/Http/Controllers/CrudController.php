<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Crud;
use App\Models\Review;
use Illuminate\Support\Facades\Storage;

class CrudController extends Controller
{
public function public()
{
    $testimonials = Crud::latest()->get();
    return view('home', compact('testimonials'));
}

public function index()
{
    $testimonials = Review::latest()->get();
    return view('dashboard', compact('testimonials'));
}
public function store(Request $request)
{
    $request->validate([
        'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        'heading' => 'required|string|max:255',
        'text' => 'required|string|max:1000',
    ]);

    $image = $request->file('image');
    $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
    $image->move(public_path('images'), $imageName);

    Crud::create([
        'image' => $imageName,
        'heading' => $request->heading,
        'text' => $request->text,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Testimonial added successfully!',
        'filename' => $imageName,
    ]);
}


public function update(Request $request, $id)
{
    $crud = Crud::findOrFail($id);

    $request->validate([
        'heading' => 'required|string|max:255',
        'text' => 'required|string|max:1000',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    if ($request->hasFile('image')) {
        $oldImagePath = public_path('images/' . $crud->image);
        if (file_exists($oldImagePath)) {
            unlink($oldImagePath);
        }
        $image = $request->file('image');
        $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('images'), $imageName);
        $crud->image = $imageName;
    }

    $crud->heading = $request->heading;
    $crud->text = $request->text;
    $crud->save();

    return response()->json([
        'success' => true,
        'message' => 'Testimonial updated successfully.',
        'filename' => $crud->image
    ]);
}

public function destroy($id)
{
    $crud = Crud::findOrFail($id);
    $imagePath = public_path('images/' . $crud->image);
    if (file_exists($imagePath)) {
        unlink($imagePath);
    }
    $crud->delete();
    return response()->json([
        'success' => true,
        'message' => 'Testimonial deleted successfully.'
    ]);
}


}
