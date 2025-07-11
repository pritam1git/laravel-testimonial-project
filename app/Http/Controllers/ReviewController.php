<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReviewController extends Controller
{
    public function showFrontend()
    {
        $reviews = Review::latest()->get();
        return view('review', compact('reviews'));
    }

    public function storeFrontend(Request $request)
    {
        $validated = $request->validate([
            'first_name'   => 'nullable|string|max:100',
            'last_name'    => 'nullable|string|max:100',
            'review_star'  => 'required|integer|min:1|max:5',
            'review_text'  => 'required|string|max:1000',
            'user_image'   => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if (!empty($validated['first_name']) && !empty($validated['last_name'])) {
            $username = $validated['first_name'] . ' ' . $validated['last_name'];
        } elseif (Auth::check()) {
            $username = Auth::user()->name;
        } else {
            $username = 'Anonymous';
        }

        $imageName = null;
        if ($request->hasFile('user_image')) {
            $image = $request->file('user_image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/reviews'), $imageName);
        }

        Review::create([
            'username'     => $username,
            'review_star'  => $validated['review_star'],
            'review_text'  => $validated['review_text'],
            'review_date'  => now(),
            'user_image'   => $imageName,
        ]);

        return response()->json(['success' => true]);
    }



    public function adminList()
    {
        $reviews = Review::latest()->get();
        return view('dashboard', compact('reviews'));
    }

    public function storeAdmin(Request $request)
    {
        $validated = $request->validate([
            'first_name'   => 'nullable|string|max:100',
            'last_name'    => 'nullable|string|max:100',
            'review_text'  => 'required|string|max:1000',
            'review_star'  => 'required|integer|min:1|max:5',
        ]);

        $username = trim($validated['first_name'] . ' ' . $validated['last_name']);
        if ($username === '') {
            $username = auth()->check() ? auth()->user()->name : 'Anonymous';
        }

        Review::create([
            'username'     => $username,
            'review_star'  => $validated['review_star'],
            'review_text'  => $validated['review_text'],
            'review_date'  => now(),
        ]);

        return response()->json(['success' => true, 'message' => 'Review added successfully']);
    }
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'first_name'   => 'nullable|string|max:100',
            'last_name'    => 'nullable|string|max:100',
            'review_star'  => 'required|integer|min:1|max:5',
            'review_text'  => 'required|string|max:1000',
        ]);

        $review = Review::findOrFail($id);

        $username = trim($validated['first_name'] . ' ' . $validated['last_name']);
        if ($username === '') {
            $username = auth()->check() ? auth()->user()->name : 'Anonymous';
        }

        $review->update([
            'username'     => $username,
            'review_star'  => $validated['review_star'],
            'review_text'  => $validated['review_text'],
        ]);

        return response()->json(['success' => true, 'message' => 'Review updated successfully']);
    }


    public function destroy($id)
    {
        Review::findOrFail($id)->delete();
        return response()->json(['status' => 'deleted']);
    }
}
