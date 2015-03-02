<?php namespace Cviebrock\LaravelResources\Descriptors;

use Cviebrock\LaravelResources\Descriptor;
use Cviebrock\LaravelResources\Traits\PlainStorage;


abstract class Text extends Descriptor {

	use PlainStorage;

	protected $template = 'resources::inputs.textarea';

}
