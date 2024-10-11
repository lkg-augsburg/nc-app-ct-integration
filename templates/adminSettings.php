<?php
$appId = OCA\churchtoolsintegration\AppInfo\Application::APP_ID;
\OCP\Util::addScript($appId, '../dist/' . $appId . '-adminSettings');
\OCP\Util::addStyle($appId, '../dist/' . $appId . '-adminSettings');
?>

<div id="ct_prefs"></div>