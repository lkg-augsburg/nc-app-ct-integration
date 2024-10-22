<?php
$appId = OCA\churchtoolsintegration\AppInfo\Application::APP_ID;
\OCP\Util::addScript($appId, $appId . '-adminSettings');
\OCP\Util::addStyle ($appId, $appId . '-adminSettings');
?>

<div id="ct_prefs"></div>