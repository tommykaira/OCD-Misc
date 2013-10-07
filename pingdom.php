<?php

	function pingdom_message($status = 'NOT OK' ,$response_time = 0)
	{
		$data = '<?xml version="1.0" encoding="ISO-8859-1"?>';
		$data .= "<pingdom_http_custom_check>";
		$data .= "<status>$status</status>";
		$data .= "<response_time>$response_time</response_time>";
		$data .= "</pingdom_http_custom_check>";

		echo $data;
	}


	function voipline()
	{
		$status 			= `asterisk -x "sip show registry"`;
		$look_for_status	= 'registered';
		$voipline_status 	= 'OK';

		if (strpos(strtolower($status), $look_for_status) === FALSE) 
		{
			$voipline_status = 'NOT OK';
		}

		return $voipline_status;
	}


	$service = isset($_GET['service'])?$_GET['service']:'voipline';

	$starttime = microtime(true);

	switch($service)
	{
		case 'voipline':
			$status = voipline();
		break;

		default:
		break;
	}

	$stoptime  		= microtime(true);
	$response_time 	= number_format(($stoptime - $starttime) * 1000, 4, '.', '');

	pingdom_message($status, $response_time);

?>