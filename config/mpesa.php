<?php

return [
	"lipa" => [
		"passkey" => "bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919",
		"shortcode" => 174379,
		"consumerKey" => "BeHrhGZVFnDNGLJ9m7GdfdwV42d3OYFY",
		"consumerSecret" => "M5uFvwvJI8zDcZTx",
		"callbackUrl" => "lipa/callback"
	],
	
	"env" => env("MPESA_ENV","sandbox"),
	"domain" => [
		"live" => "https://api.safaricom.co.ke/mpesa/",
		"sandbox" => "https://sandbox.safaricom.co.ke/mpesa/"
	]
];