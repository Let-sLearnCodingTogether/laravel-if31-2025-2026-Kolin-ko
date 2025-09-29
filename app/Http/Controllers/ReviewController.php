<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewRequest;
use App\Models\Review;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReviewRequest $request)
    {
        try{
            $validated = $request->safe()->all();
            $validated['user_id'] = Auth::user()->id;

            $response = Review::create($validated);
            if($response){
                return response()->json([
                    'message' => 'Review berhasil dibuat',
                    'data' => null,
                ],200);
            }

            return response()->json([
                    'message' => 'Review gagal dibuat',
                    'data' => null,
                ],500);

        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'data' => null,
            ],500);

        }

    }

    /**
     * Display the specified resource.
     */
    public function show(Review $review)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Review $review)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Review $review)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Review $review)
    {
        try{
            if($review->delete()){
                return response()->json([
                    'message' => "Review berhasil dihapus",
                    'data' => null
                ], 200);
            }
            return response()->json([
                'message' => "Review gagal dihapus",
                'data' => null
            ], 500);

        } catch(Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'data' => null
            ], 500);
        }
    }
}
