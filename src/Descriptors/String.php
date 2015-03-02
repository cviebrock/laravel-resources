<?php namespace Cviebrock\LaravelResources\Descriptors;

use Cviebrock\LaravelResources\Descriptor;
use Cviebrock\LaravelResources\Traits\PlainStorage;


abstract class String extends Descriptor {

	use PlainStorage;

	protected $template = 'resources::inputs.string';

}
