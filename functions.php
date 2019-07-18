<?php
function getLacak($noresi,$courier,$api) {
	
	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL				=> "https://pro.rajaongkir.com/api/waybill",
		CURLOPT_RETURNTRANSFER	=> true,
		CURLOPT_ENCODING		=> "",
		CURLOPT_MAXREDIRS		=> 10,
		CURLOPT_TIMEOUT			=> 30,
		CURLOPT_HTTP_VERSION	=> CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST	=> "POST",
		CURLOPT_POSTFIELDS		=> "waybill=".$noresi."&courier=".$courier."",
		CURLOPT_SSL_VERIFYPEER	=> false,
		CURLOPT_SSL_VERIFYHOST	=> false,
		CURLOPT_HTTPHEADER		=> array(
			"content-type: application/x-www-form-urlencoded",
			"key: ".$api
		),
	));

	$response = curl_exec($curl);
	$err = curl_error($curl);

	curl_close($curl);

	if ($err) {
		$show = "cURL Error #:" . $err;
	} else {
		$show = $response;
	}
	return $show;
}