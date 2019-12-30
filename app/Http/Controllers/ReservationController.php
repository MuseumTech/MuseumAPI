<?php

namespace App\Http\Controllers;

use App\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index()
    {
        return response()->json(Reservation::all());
    }

    public function show($id)
    {
        return response()->json(Reservation::find($id));
    }

    public function create(Request $request)
    {

        $this->validate($request, [
            'title' => 'required'
        ]);

        $reservation = Reservation::create($request->all());

        return response()->json($reservation, 201);
    }

    public function update($id, Request $request)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->update($request->all());

        return response()->json($reservation, 200);
    }

    public function delete($id)
    {
        Reservation::findOrFail($id)->delete();
        return response('Deleted Successfully', 200);
    }
}
