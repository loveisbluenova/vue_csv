<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Payment;

class PaymentController extends Controller
{
    public function index()
    {
        return view('welcome');
    }

    public function store(Request $request)
    {	
    	Payment::truncate();
    	$payments=$request->input('csv');
		foreach ($payments as $key => $payment) {
			$payment = Payment::insert($payment);
		}
		return response()->json(['initial_data'=>$payments]);

    }
    public function show(Request $request, $id)
    {
    	$result = Payment::limit(4)->get();
    	
    	return response()->json(['initial_data'=>$result]);
    }
    public function getfields(Request $request) {
    }
}
