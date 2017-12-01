<?php

namespace App\Http\Controllers;

use App\Appointment;
use Session;
use Illuminate\Http\Request;

class AppointmentsController extends Controller
{
    /**
     * Display a listing of all appointments.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //FUTURE TODO: Maybe add a separate index() method for admins and employees, who sees all appointments

        //TODO: Tayyab, please change this Eloquent query to only filter appointments with the NSID matching the NSID of the current logged in user.
        $appointments = Appointment::all();

        // withAppointments() is called a "magic method". This is literally similar to the commented code below:
        // return view('appointments.index')->with('appointments', $appointments);
        return view('appointments.index')->withAppointments($appointments);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //Done. Don't touch.
        return view('appointments.create');
    }

    /**
     * Store a newly created resource in storage.
     * This is the thing that stores our user input to the DB.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate input. 
        // TODO: Tayyab, Hiba put a lot of regexes in his input validation.
        // This is where every validation is supposed to go.

        $this->validate($request, [
            'brief_desc' => 'required',
            'full_desc' => 'required'
        ]);

        // For debugging. dd() - "die and dump" - shows all the values that will be passed to the DB
        //dd($request->all());
        $input = $request->all();
        
        // create() is a native Laravel Eloquent method. Basically, this line
        // stores the input to the database. See, no SQL!
        Appointment::create($input);

        // Create a nice feedback message, then redirect to the previous page.
        Session::flash('flash_message', 'Appointment successfully booked!');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    //public function show(Appointment $appointment)
    public function show(Appointment $appointment)
    {

        // Once again, Laravel is awesome. Look at this one line of code!
        // Relevant Laracast video on Route-Model binding:
        // https://laracasts.com/series/laravel-from-scratch-2017/episodes/9?autoplay=true

        return view('appointments.show')->withAppointment($appointment);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function edit(Appointment $appointment)
    {
        //
        return view('appointments.edit')->withAppointment($appointment);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Appointment $appointment)
    {
        // This is pretty much like store() above but with slight adjustments.

        // Tayyab: Make sure that this validator is the same as the validator above for
        // the store() method.
        $this->validate($request, [
            'brief_desc' => 'required',
            'full_desc' => 'required'
        ]);

        // Grab the input, then update the appointment
        $input = $request->all();
        $appointment->fill($input)->save();

        // return with amazing success
        Session::flash('flash_message', 'Appointment successfully updated!');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Appointment $appointment)
    {
        //
        $appointment->delete();

        Session::flash('flash_message', 'Appointment successfully cancelled.');
        return redirect()->route('appointments.index');
    }
}
