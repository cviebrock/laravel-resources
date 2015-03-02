<?php namespace Cviebrock\LaravelResources\Facades;

use Illuminate\Support\Facades\Facade;


class ResourceForm extends Facade {

	protected static function getFacadeAccessor()
	{
		return 'resources.form';
	}

}
