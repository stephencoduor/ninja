<?php
namespace App\Ninja\PaymentDrivers\Mpesa;

use Illuminate\Http\Request;

interface MpesaPaymentInterface {
	 

	public function request($phone,$amount,$transactionType = '');
	
	
	function resolve();


}