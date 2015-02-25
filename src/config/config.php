<?php

return [

	/**
	 * The prefix applied to table names used by the package.
	 *
	 * By default, the package uses tables called:
	 *
	 *  - resources
	 *  - resource_translations
	 *
	 * If those conflict with tables already in your application,
	 * then just supply a prefix here to avoid conflict.
	 *
	 * Note: This is in addition to any database-wide prefix defined
	 * in /app/config/database.php
	 */
	'tablePrefix' => '',

	/**
	 * The cache key used to store the settings.
	 */
	'cacheKey' => 'resources',

	/**
	 * The default locale to use for translateable resources.
	 *
	 * By default, the package will use the value returned by
	 * Config::get('app.locale'), but you can override that here.
	 */
	'defaultLocale' => null,

	'classes' => [
//		'home' => 'MyApp\Resources\HomeResourceDefinition',
	]

];
