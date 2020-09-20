<?php 
namespace App\Ninja\PaymentDrivers\Mpesa\Clients;

use Illuminate\Http\Request;
use App\Ninja\PaymentDrivers\Mpesa\MpesaDriver;
use App\Ninja\PaymentDrivers\Mpesa\MpesaPaymentInterface;

class LipaNaMpesa implements MpesaPaymentInterface {

	
	private  $driver;

	public function __construct(MpesaDriver $driver) {
		$this->driver = $driver;
		$this->endpoint = "stkpush/v1/processrequest";
	}

	function request($phone,$amount,$transactionType = "CustomerPayBillOnline",$reference="",$description = "") {
        $phone = (substr($phone, 0, 1) == "+") ? str_replace("+", "", $phone) : $phone;
        $phone = (substr($phone, 0, 1) == "0") ? preg_replace("/^0/", "254", $phone) : $phone;
        $phone = (substr($phone, 0, 1) == "7") ? "254{$phone}" : $phone;

        $timestamp = date("YmdHis");
        $password  = base64_encode($this->driver->config()->shortcode . $this->driver->config()->passKey . $timestamp);
		$data = array(
            "BusinessShortCode" => $this->driver->config()->shortcode,
            "Password"          => $password,
            "Timestamp"         => $timestamp,
            "TransactionType"   => $transactionType,
            "Amount"            => round($amount),
            "PartyA"            => $phone,
            "PartyB"            => $this->driver->config()->shortcode,
            "PhoneNumber"       => $phone,
            "CallBackURL"       => $this->driver->config()->callback,
            "AccountReference"  => $reference,
            "TransactionDesc"   => $description,
            
		);

		return $this->driver->curl_post($this->driver->config()->domain.$this->endpoint,$data);
		

	}

	function resolve() {
		$response = json_decode(file_get_contents("php://input"), true);
	}

}