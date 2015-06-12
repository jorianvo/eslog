<?php
require_once 'apps/eslog/vendor/autoload.php';

class OC_esLog {

	public function __construct(){

	}

	// This function will gather the stats and send them statsd
	// The path is the path of the file that is read or written
	// currently this is not used. The action is either 'File read'
	// or 'File write' and determines what value to send to statsd
	public static function sendToStatsd($path,$action){
		// Create the udp socket to send the data to statsd
		$sender = new SocketSender('localhost', 8126, 'udp');

		// Create the client which sends to data to statsd using the sender
		$client  = new StatsdClient($sender);
		$factory = new StatsdDataFactory('\Liuggio\StatsdClient\Entity\StatsdData');
		$service = new StatsdService($client, $factory);

		// Check if file is read or written
		if ($action == "File read"){
			$service->increment('read');
		} elseif ($action == "File write"){
			$service->increment('write');
		}

		// Send the data over the socket to statsd
		$service->flush();
	}
}
