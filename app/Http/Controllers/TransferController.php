<?php

namespace App\Http\Controllers;

use App\Models\Transfer;
use Illuminate\Http\Request;

class TransferController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request, [
            'sender_id'   => ['required', 'integer'],
            'receiver_id' => ['required', 'integer'],
            'amount'      => ['required', 'numeric', 'min:0.01'],
        ]);

        $transfer = Transfer::create([
            'sender_id'   => $request->sender_id,
            'receiver_id' => $request->receiver_id,
            'amount'      => (int) round($request->amount * 100),
            'status'      => 'pending',
        ]);

        return response()->json(['message' => 'Transfer received', 'id' => $transfer->id], 202);
    }

    public function show(string $id)
    {
        $transfer = Transfer::find($id);

        if (!$transfer) {
            return response()->json(['message' => 'Transfer not found'], 404);
        }

        return response()->json($transfer);
    }
}
