<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\Helper;

class PaymentController extends Controller
{
    use Helper;

    public function index()
    {
        $data = $this->modelTransaction::orderBy('id', 'desc')->with('user')->get();
        return response()->json(['status' => 'success', 'data' => $data], 200);
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'book_id' => 'required',
        ]);

        $book = $this->modelBook::find($request->book_id);
        $transaction = $this->modelTransaction::find($request->user_id);

        if($transaction->user_id == $request->user_id){
            return response()->json(['status' => 'failed', 'message' => 'Maaf buku pernah di sewa sebelumnya'], 422);
        }


        $bookPrice  = $book->price;
        $adminFee   = 5000;
        $ppn        = $bookPrice * 10 / 100;
        
        $this->modelTransaction::create([
            'user_id' => $request->user_id, //Member
            'book_id' => $request->book_id,
            'total_amount' => $bookPrice,
            'total_tax_and_ppn' => $adminFee + $ppn,
            'actual_amount' => $bookPrice + ($adminFee + $ppn)
        ]);

        return response()->json(['status' => 'success', 'message' => 'Buku Berhasil di Sewa'], 200);
    }

}
