<?php

// Add this file into /var/www/owncloud/remote.php
// require_once 'apps/eslog/spy.php';
require_once 'apps/eslog/lib/log.php';
require_once 'apps/eslog/lib/hooks.php';
OC_esLog_Hooks::webdav($_SERVER);
