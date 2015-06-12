<?php


require_once 'apps/eslog/lib/log.php';

// Hooks definition

class OC_esLog_Hooks {

	// ---------------------
	// Filesystem operations
	//
	// For now just reading and writing data
	// ---------------------

	static public function read($path) {
		OC_esLog::log($path,'File read');
	}
	static public function write($path) {
		OC_esLog::log($path,'File write');
	}

}
