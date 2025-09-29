<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSpotRequest;
use App\Http\Requests\UpdateSpotRequest;
use App\Models\Category;
use App\Models\Spot;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SpotController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $spots = Spot::with([
                'user:id:name',
                'category:category,spot_id'
            ])
            ->withCount([
                'reviews'
            ])
            ->withSum('reviews', 'rating')
            ->orderBy('created_at', 'desc')
            ->paginate(request('size', 10));

            return response()->json([
                'message' => 'List Spot',
                'data' => $spots,
            ],200);
        } catch (Exception $e){
            return response()->json([
                'message' => $e->getMessage(),
                'data' => null,
            ],500);


        }
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
    public function store(StoreSpotRequest $request)
    {
        try{
            $validated = $request->safe()->all();

            $picture_path = Storage::disk('public')->putFile('spots', $request->file('picture'));

            $validated['user_id'] = Auth::user()->id;
            $validated['picture'] = $picture_path;


            $spot = Spot::create($validated);
            if($spot){
                $categories = [];

                forEach($validated['category'] as $category){
                    $categories[] = [
                        'spot_id' => $spot->id,
                        'category' => $category
                    ];
                }
                Category::fillAndInsert($categories);
                return response()->json([
                    'message' => 'Spot berhasil ditambah',
                    'data' => $validated,
                ],201);
            }
        } catch(Exception $e){
             return response()->json([
                'message' => 'gagal menambah data',
                'error' => $e -> getMessage(),
                ],500);


        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Spot $spot)
    {
         try{
            return response()->json([
                'message' => 'Detail Spot',
                'data' => $spot->load([
                    'user:id:name',
                    'category:category,spot_id'
                ])

                ->loadCount('reviews')
                ->loadSum('reviews', 'rating')
            ],200);
        } catch (Exception $e){
            return response()->json([
                'message' => $e->getMessage(),
                'data' => null,
            ],500);

        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Spot $spot)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSpotRequest $request, Spot $spot)
    {
        //

        try{
            $validated = $request->safe()->all();

            if(isset($validate['picture'])){
                $picture_path = Storage::disk('public')->putFile('spots', $request->file('picture'));
                $request->file('picture');
            }
            if(isset($validate['category'])){

                Category::where('spot_id', $spot->id)->delete();
                $categories = [];
                forEach($validated['category'] as $category){
                    $categories[] = [
                        'spot_id' => $spot->id,
                        'category' => $category
                    ];
                }
                Category::fillAndInsert($categories);
            }

            $spot->update([
                'name' => $validated['name'],
                'picture' => $picture_path ?? $spot->picture,
                'address' => $validated['address'],
            ]);
            return response()->json([
                'message' => "Berhasil update spot",
                'data' => $spot,
            ], 200);

        } catch(Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'data' => null,
            ],500);

        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Spot $spot)
    {
        try{
            $user = Auth::user();

            if($spot->user_id == $user->id || $user->role == 'ADMIN')
                if($spot->delete()){
                     return response()->json([
                        'message' => 'Spot Berhasil dihapus',
                        'data' => null,
                    ],200);
                } else {
                    return response()->json([
                        'message' => 'Spot gagal dihapus',
                        'data' => null,
                    ],200);

                }

            } catch (Exception $e){
                return response()->json([
                    'message' => $e->getMessage(),
                    'data' => null,
                ],500);

        }
    }

    public function reviews(Spot $spot){
        try{
            return response()->json([
                'message' => "List review",
                'data' => $spot->reviews()->with([
                    'user:id,nama'
                ])->get()
                ], 200);
        } catch (Exception $e){
            return response()->json([
                'message' => $e->getMessage(),
                'data' => null,
            ],500);
        }
    }
}
