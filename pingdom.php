<?php

	function pingdom_message($status = 'NOT OK' ,$response_time = 0)
	{
$string = <<<XML
<pingdom_http_custom_check>
<status>$status</status>
<response_time>$response_time</response_time>
</pingdom_http_custom_check>
XML;

		echo $string;
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
	$response_time 	= number_format(($stoptime - $starttime) * 1000, 3, '.', '');

	pingdom_message($status, $response_time);

?>