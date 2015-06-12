<?php

/**
 * ownCloud - Dashboard
 *
 * @author Patrick Paysant <ppaysant@linagora.com>
 * @copyright 2014 CNRS DSI
 * @license This file is licensed under the Affero General Public License version 3 or later. See the COPYING file.
 */

use \OCA\Eslog\Lib\Log;
use \OCA\Eslog\Lib\Hooks;

OC::$CLASSPATH['OC_Eslog'] = 'eslog/lib/log.php';
OC::$CLASSPATH['OC_esLog_Hooks'] = 'eslog/lib/hooks.php';

OCP\Util::addStyle('eslog', 'eslog');
OCP\Util::addScript('eslog', 'eslog');
OCP\App::registerAdmin('eslog','settings');
OCP\App::registerPersonal('eslog', 'settings');

/* HOOKS */
// For now we are only interested in reads and writes
OC_HOOK::connect('OC_Filesystem', 'read', 'OC_esLog_Hooks', 'read');
OC_HOOK::connect('OC_Filesystem', 'write', 'OC_esLog_Hooks', 'write');

// Cleanning settings
\OCP\BackgroundJob::addRegularTask('OC_Eslog', 'clean');
if (isset($_POST['superlog_lifetime']) && is_numeric($_POST['superlog_lifetime'])) {
OC_Appconfig::setValue('eslog', 'superlog_lifetime', $_POST['superlog_lifetime']);
}
