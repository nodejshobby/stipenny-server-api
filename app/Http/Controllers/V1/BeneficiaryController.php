<?php

namespace App\Http\Controllers\V1;

use App\Models\Role;
use App\Models\User;
use App\Models\Stipend;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\V1\StoreBeneficiaryRequest;

class BeneficiaryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Stipend $stipend, StoreBeneficiaryRequest $request)
    {
        Gate::authorize('canAddBeneficiary', $stipend);
           
        $data = $request->validated();
        $userId = $data['userId']; // User email or phone number as user identity

        // User to be added as beneficiary
        $user = User::where('email', $userId)->where('role_id', Role::USER)->whereHas('detail')->orWhereHas('detail', function ($query) use($userId) {
            $query->where('phone_number', $userId);
        })->first();

        if($user){
            // Prohibit self beneficiary
            if($user->email === auth()->user()->email){
                return response(["message" => "Self beneficiary is not supported"], 400);
            }
            // Check if the beneficiary already exists for this stipend
            $beneficiaryExist = $stipend->beneficiaries()->where('user_id', $user->id)->count();

            // Prohibit double beneficiary
            if($beneficiaryExist === 0){
                $stipend->beneficiaries()->create([
                    'user_id' => $user->id
                ]);
                return response(["message" => "Beneficiary added successfully"], 201);
            }

            return response(["message" => "Beneficiary already added"], 400);

        }

        return response(["message" => "Beneficiary not genuine"], 404);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
