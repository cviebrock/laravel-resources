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
	 * The cache prefix used to store the settings.
	 */
	'cachePrefix' => 'resources',

	/**
	 * Here is where you define all the resources your application needs.
	 * You can make it a nested array, or use dot-notation.
	 *
	 * The values of the array represent the resource descriptor classes
	 * that are used
	 */
	'resources' => [

		//	'home.title' => 'My App'
	]

];
