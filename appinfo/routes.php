<?php

declare(strict_types=1);
// SPDX-FileCopyrightText: Sören Liebich <soeren.liebich@gmail.com>
// SPDX-License-Identifier: AGPL-3.0-or-later

/**
 * Create your routes in here. The name is the lowercase name of the controller
 * without the controller part, the stuff after the hash is the method.
 * e.g. page#index -> OCA\ChurchToolsIntegration\Controller\PageController->index()
 *
 * The controller class has to be registered in the application.php file since
 * it's instantiated in there
 */
return [
	'resources' => [],
	'routes' => [
		[
			'name' => 'church_tools_client#authenticate',
			'url' => '/api/authenticate',
			'verb' => 'POST',
		],
		[
			'name' => 'nc#save_configuration',
			'url' => '/api/configuration',
			'verb' => 'POST',
		],
		[
			'name' => 'church_tools_client#fetch_group_types',
			'url' => '/api/group-type',
			'verb' => 'GET',
		],
		[
			'name' => 'church_tools_client#fetch_all_groups',
			'url' => '/api/group',
			'verb' => 'GET',
		],
	],
];
