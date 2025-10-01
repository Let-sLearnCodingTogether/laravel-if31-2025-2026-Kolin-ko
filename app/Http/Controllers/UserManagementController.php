<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserManagementRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $users = User::all();
            return response()->json([
                'message' => 'List User',
                'data' => $users,
            ],200);
        } catch (Exception $e){
            return response()->json([
                'message' => $e->getMessage(),
                'data' => null,
            ],500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserManagementRequest $request, User $user)
    {

        try {
            $validated = $request->safe()->all();
            $user->update($validated);

            \Log::info('After update', ['user' => $user->toArray()]);


            return response()->json([
                'message' => "Berhasil update role user",
                'data' => $user,
            ], 200);
        } catch (Exception $e){
            return response()->json([
                'message' => $e->getMessage(),
                'data' => null,
            ], 500);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
