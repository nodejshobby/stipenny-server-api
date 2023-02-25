<?php

namespace App\Http\Controllers\V1;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreStipendRequest;

class StipendController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreStipendRequest $request)
    {
        $data =  $request->validated();

        $data['created_at'] = Carbon::now();
        $interval = $data['interval'];
        $limit = $data['limit'];

        $intervalMapp = [
            'daily' => 1,
            'weekly' => 7,
            'monthly' => 30,
        ];


        switch ($interval) {
            case 'daily': 
                $data['next_billing'] = (new Carbon($data['created_at']))->addDays($intervalMapp['daily']);
                $data['due_date'] = (new Carbon($data['created_at']))->addDays($limit * $intervalMapp['daily']);
                break;

            case 'weekly':
                $data['next_billing'] = (new Carbon($data['created_at']))->addDays($intervalMapp['weekly']);
                $data['due_date'] = (new Carbon($data['created_at']))->addDays($limit * $intervalMapp['weekly']);
                break;

            case 'monthly':
                $data['next_billing'] = (new Carbon($data['created_at']))->addDays($intervalMapp['monthly']);
                $data['due_date'] = (new Carbon($data['created_at']))->addDays($limit * $intervalMapp['monthly']);
                break;
            
            default:
                # code...
                break;
        }

        $request->user()->stipends()->create($data);
        return response(['message' => "Stipend plan is created successfully"], 201);

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
