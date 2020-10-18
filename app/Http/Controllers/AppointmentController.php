<?php

namespace App\Http\Controllers;

use App\Events\AppointmentDue;
use App\Jobs\AppointmentDueJob;
use App\Jobs\SendMailJob;
use App\Models\Appointment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Appointment::orderBy(request('column') ? request('column') : 'updated_at', request('direction') ? request('direction') : 'desc')
            ->with('user')
            ->search(request('search'))
            ->paginate());
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $appointment = new Appointment();
        $appointment->title = $request->title;
        $appointment->body = $request->body;
        $appointment->save();

        if($request->item == "now") {

            $appointment->delivered = 'YES';
            $appointment->send_date = Carbon::now();
            auth('api')->user()->appointments()->save($appointment);

            $users = User::all();

            foreach($users as $user) {
                AppointmentDue::dispatch();
            }

            return response()->json('Mail sent.', 201);

        } else {

            $appointment->date_string = date("Y-m-d H:i", strtotime($request->send_date));

            auth('api')->user()->appointments()->save($appointment);

            return response()->json('Notification will be sent later.', 201);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function show(Appointment $appointment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Appointment $appointment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Appointment $appointment)
    {
        //
    }
}
