<?php
namespace App\Http\Controllers;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InternalController extends Controller {
    public function getSchedule($id){
        return Schedule::with(['bus','route'])->findOrFail($id);
    }

    public function reserveSeats($id, Request $req){
        $req->validate(['seats'=>'required|integer|min:1']);
        $result = DB::transaction(function() use ($id, $req) {
            $s = Schedule::lockForUpdate()->findOrFail($id);
            if ($s->available_seats < $req->seats) {
                // throw to rollback
                return ['error' => 'Insufficient seats', 'status' => 400];
            }

            $s->available_seats -= $req->seats;
            $s->save();

            $bus = $s->bus; // keep for response, but DO NOT modify bus capacity

            return ['message' => 'Reserved', 'available_seats' => $s->available_seats, 'bus' => $bus];
        });

        if (isset($result['error'])) {
            return response()->json(['error' => $result['error']], $result['status']);
        }

        return response()->json($result);
    }
}
