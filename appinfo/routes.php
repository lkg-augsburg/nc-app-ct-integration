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
			'name' => 'church_tools_client#test',
			'url' => '/api/test',
			'verb' => 'GET',
		],
		[
			'name' => 'church_tools_client#fetch_csrf_token',
			'url' => '/api/csrftoken',
			'verb' => 'GET',
		],
		[
			'name' => 'church_tools_client#fetch_who_am_i',
			'url' => '/api/whoami',
			'verb' => 'GET',
		],
		[
			'name' => 'church_tools_client#fetch_tags',
			'url' => '/api/ct-tags',
			'verb' => 'GET',
		],
		[
			'name' => 'church_tools_client#fetch_group_type_groups',
			'url' => '/api/ct-group-type/{id}/groups',
			'verb' => 'GET',
		],
		[
			'name' => 'church_tools_client#fetch_group_types',
			'url' => '/api/ct-group-types',
			'verb' => 'GET',
		],
		[
			'name' => 'church_tools_client#save_configuration',
			'url' => '/api/save-config',
			'verb' => 'POST',
		],
		[
			'name' => 'church_tools_client#fetch_groups',
			'url' => '/api/groups',
			'verb' => 'GET',
		],
		[
			'name' => 'church_tools_client#sanc',
			'url' => '/api/sync',
			'verb' => 'POST',
		],
		[
			'name' => 'nc#fetch_existing_groups',
			'url' => '/api/nc-groups',
			'verb' => 'GET',
		],
		[
			'name' => 'nc#sync_groups',
			'url' => '/api/sync-groups',
			'verb' => 'POST',
		],
	],
];
