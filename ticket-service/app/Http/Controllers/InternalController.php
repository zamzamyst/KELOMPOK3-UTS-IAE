<?php
namespace App\Http\Controllers;
use App\Models\Ticket;
use Illuminate\Http\Request;

class InternalController extends Controller {
    public function show($id){
        $t = Ticket::find($id);
        if (!$t) return response()->json(['error'=>'Not found'],404);
        return response()->json($t);
    }

    public function updateStatus($id, Request $r){
        $r->validate(['status'=>'required|string']);
        $t = Ticket::find($id);
        if (!$t) return response()->json(['error'=>'Not found'],404);
        $t->status = $r->status;
        $t->save();
        return response()->json($t);
    }

    public function updateAmount($id, Request $r){
        $r->validate(['total_price'=>'required|numeric']);
        $t = Ticket::find($id);
        if (!$t) return response()->json(['error'=>'Not found'],404);
        $t->total_price = $r->total_price;
        $t->save();
        return response()->json($t);
    }
}
