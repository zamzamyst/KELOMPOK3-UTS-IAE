<?php
namespace App\Http\Controllers;
use App\Models\Schedule;
use Illuminate\Http\Request;

class InternalController extends Controller {
    public function getSchedule($id){
        return Schedule::with(['bus','route'])->findOrFail($id);
    }

    public function reserveSeats($id, Request $req){
        $req->validate(['seats'=>'required|integer|min:1']);
        $s = Schedule::findOrFail($id);
        if ($s->available_seats < $req->seats) 
            return response()->json(['error'=>'Insufficient seats'],400);
        
        $s->available_seats -= $req->seats;
        $s->save();

        $bus = $s->bus;
        $bus->capacity -= $req->seats;
        $bus->save();

        return response()->json([
            'message'=>'Reserved',
            'available_seats'=>$s->available_seats,
            'bus' => $bus
        ]);
    }
}
