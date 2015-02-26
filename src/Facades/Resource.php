<?php namespace Cviebrock\LaravelResources\Facades;

use Illuminate\Support\Facades\Facade;


class Resource extends Facade {

	protected static function getFacadeAccessor()
	{
		return 'resources.manager';
	}

}
