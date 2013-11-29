<?php

	function pingdom_message($status = 'NOT OK' ,$response_time = 0)
	{
$string = <<<XML
<pingdom_http_custom_check>
<status>$status</status>
<response_time>$response_time</response_time>
</pingdom_http_custom_check>
XML;
		$xml = new SimpleXMLElement($string);
		echo $xml->asXML();
	}


	//------------------------------------------------//
	//-- Check if Asterisk is connected to VOIPLINE --//
	//------------------------------------------------//
	
	function voipline()
	{
		$status 			= `asterisk -x "sip show registry"`;
		$look_for_status	= 'registered';
		$service_status 	= 'OK';

		if (strpos(strtolower($status), $look_for_status) === FALSE) 
		{
			$service_status = 'NOT OK';
		}

		return $service_status;
	}
	
	//--------------------------//
	//-- Check if MYSQL is UP --//
	//--------------------------//
	
	function mysql_service()
	{
		$status = `mysqladmin -uroot -p0cds3cr3t2013 ping`;
		
		$look_for_status	= 'alive';
		$service_status 	= 'OK';
		
		if (strpos(strtolower($status), $look_for_status) === FALSE) 
		{
			$service_status = 'NOT OK';
		}

		return $service_status;
	}
	
	//----------------------------------//
	//-- Check if Doctor Portal is UP --//
	//----------------------------------//
	
	function doctorportal()
	{
		$status = `curl http://localhost:8888/ping `;
		
		$look_for_status	= 'success';
		$service_status 	= 'OK';
		
		if (strpos(strtolower($status), $look_for_status) === FALSE) 
		{
			$service_status = 'NOT OK';
		}

		return $service_status;
	}
	
	
	//--------------------------------//
	//-- Just return OK if accessed --//
	//--------------------------------//
	
	function generic()
	{
		return 'OK';
	}
	
	
	
	//-----------------//
	//-- Main SCRIPT --//
	//-----------------//


	$service = isset($_GET['service'])?$_GET['service']:'generic';

	$starttime = microtime(true);

	switch($service)
	{
		case 'voipline':
			$status = voipline();
		break;

		case 'mysql':
			$status = mysql_service();
		break;

		case 'doctorportal':
			$status = doctorportal();
		break;

		case 'generic':
		default:
			$status = generic();
		break;
	}

	$stoptime  		= microtime(true);
	$response_time 	= number_format(($stoptime - $starttime) * 1000, 3, '.', '');

	pingdom_message($status, $response_time);

?>