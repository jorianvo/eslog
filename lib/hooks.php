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

  // This function is called every time a file is written using webdav
  static public function webdav($vars) {
    // Get protocal
    if(isset($vars['SCRIPT_NAME']) && basename($vars['SCRIPT_NAME'])=='remote.php'){
      $paths=explode('/',$vars['REQUEST_URI']);
      $pos=array_search('remote.php',$paths);
      $protocol=$paths[$pos+1];
    }

    // Get request method
    $action=strtolower($vars['REQUEST_METHOD']);

    // If using webdav i.e. desktop or mobile sync client
    // Get the request method
    if($protocol=='webdav'){
      if ($action=='put') {
        OC_esLog::sendToStatsd(NULL,'File write');
      }
    }
  }
}
