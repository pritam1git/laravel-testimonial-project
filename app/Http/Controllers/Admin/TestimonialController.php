<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Crud;

class TestimonialController extends Controller
{
    public function index()
    {
        $testimonials = Crud::latest()->get();
        return view('admin.testimonials', compact('testimonials'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'heading' => 'required|string|max:255',
            'text' => 'required|string|max:1000',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imageName = time() . '_' . uniqid() . '.' . $request->image->extension();
        $request->image->move(public_path('images'), $imageName);

        Crud::create([
            'heading' => $request->heading,
            'text' => $request->text,
            'image' => $imageName,
        ]);

        return response()->json(['message' => 'Testimonial added successfully.']);
    }

    public function update(Request $request, $id)
    {
        $testimonial = Crud::findOrFail($id);

        $request->validate([
            'heading' => 'required|string|max:255',
            'text' => 'required|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $oldPath = public_path('images/' . $testimonial->image);
            if (file_exists($oldPath)) unlink($oldPath);

            $imageName = time() . '_' . uniqid() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);
            $testimonial->image = $imageName;
        }

        $testimonial->heading = $request->heading;
        $testimonial->text = $request->text;
        $testimonial->save();

        return response()->json(['message' => 'Testimonial updated successfully.']);
    }

    public function destroy($id)
    {
        $testimonial = Crud::findOrFail($id);
        $imagePath = public_path('images/' . $testimonial->image);
        if (file_exists($imagePath)) unlink($imagePath);

        $testimonial->delete();

        return response()->json(['message' => 'Testimonial deleted successfully.']);
    }
}
