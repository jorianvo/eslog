<?php


require_once 'apps/eslog/lib/log.php';

// Hooks definition

class OC_esLog_Hooks {

	// ---------------------
	// Filesystem operations
	//
	// For now just reading and writing data
	// ---------------------

  // This function is called every time a file is read
	static public function read($path) {
		OC_esLog::sendToStatsd($path,'File read');
	}

	// This function is called every time a file is written
	static public function write($path) {
		OC_esLog::sendToStatsd($path,'File write');
	}

}
