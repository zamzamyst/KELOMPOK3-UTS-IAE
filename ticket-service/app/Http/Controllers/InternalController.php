<?php
namespace App\Http\Controllers;
use App\Models\Ticket;
use Illuminate\Http\Request;

class InternalController extends Controller {
    public function updateStatus($id, Request $r){
        $r->validate(['status'=>'required|string']);
        $t = Ticket::find($id);
        if (!$t) return response()->json(['error'=>'Not found'],404);
        $t->status = $r->status;
        $t->save();
        return response()->json($t);
    }
}
