<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function storeTransaction(Request $request)
    {

        try {
            $storedNum = $request->input('storedNum');

            $transaction = Transaction::create([
                'transaction_num' =>  $request->get('storedNum')
            ]);
            
            return response()->json(['message' => 'Transaction stored successfully']);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
