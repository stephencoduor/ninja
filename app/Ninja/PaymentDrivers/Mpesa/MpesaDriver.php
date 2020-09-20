<?php

namespace App\Ninja\PaymentDrivers\Mpesa;

use Error;

class MpesaDriver  {

	public  $config = [];

	function __construct() {
		$this->env = config('mpesa.env');
		$this->config = [
			"passKey" => config("mpesa.lipa.passkey"),
			"callback" => config("mpesa.lipa.callbackUrl"),
			"shortcode" => config("mpesa.lipa.shortcode"),
			"env" => config("mpesa.env"),
			"domain" => config("mpesa.domain.{$this->env}")
		];
	}
	
	function config():object{
		return (object) $this->config;
	}
	

	function token() {
		$url = 
		($this->env == "sandbox")
		? "https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials"
		: "https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials";
		$credentials = base64_encode(config("mpesa.lipa.consumerKey") . ":" .config("mpesa.lipa.consumerSecret"));
		$response = json_decode($this->curl_get($url,$credentials));
		if (!json_last_error() == \JSON_ERROR_NONE)
			throw new Error("Json could not be deserialized");
		return $response->access_token ??"";
	}

	function curl_get($url,$authentication = null) {
		$handler = curl_init();
        curl_setopt($handler, CURLOPT_URL, $url);
        curl_setopt($handler, CURLOPT_HTTPHEADER, array("Authorization: Basic " . $authentication));
        curl_setopt($handler, CURLOPT_HEADER, false);
        curl_setopt($handler, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($handler, CURLOPT_SSL_VERIFYPEER, false);

		$resp =  curl_exec($handler);
		curl_close($handler);
		return $resp;
	}

	function curl_post($url,$data = []) {
		
		$token       = $this->token();
        $handler        = curl_init();
        $data = json_encode($data);
        curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handler, CURLOPT_POST, true);
        curl_setopt($handler, CURLOPT_POSTFIELDS, $data);
        curl_setopt($handler, CURLOPT_HEADER, false);
        curl_setopt($handler, CURLOPT_URL, $url);
        curl_setopt(
            $handler,
            CURLOPT_HTTPHEADER,
            array(
                "Content-Type:application/json",
                "Authorization:Bearer " . $token,
            )
        );

		$response =  curl_exec($handler);
		curl_close($handler);
		return $response;
	}

}