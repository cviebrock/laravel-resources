<?php namespace Cviebrock\LaravelResources\Facades;

use Illuminate\Support\Facades\Facade;


class ResourceGroup extends Facade {

	protected static function getFacadeAccessor()
	{
		return 'resources.group';
	}

}
