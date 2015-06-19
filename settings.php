<?php
$tmpl = new OC_Template('eslog', 'settings');
$tmpl->assign('eslog_host', OC_Appconfig::getValue('eslog', 'eslog_host','127.0.0.1'));
$tmpl->assign('eslog_port', OC_Appconfig::getValue('eslog', 'eslog_port','8125'));
$tmpl->assign('eslog_proto', OC_Appconfig::getValue('eslog', 'eslog_proto','udp'));

return $tmpl->fetchPage();
