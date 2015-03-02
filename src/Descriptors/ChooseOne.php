<?php namespace Cviebrock\LaravelResources\Descriptors;


use Cviebrock\LaravelResources\Descriptor;
use Cviebrock\LaravelResources\Traits\ChooseableDescriptor;
use Cviebrock\LaravelResources\Traits\SerializedStorage;


abstract class ChooseOne extends Descriptor {

	use SerializedStorage;
	use ChooseableDescriptor;


	protected $template = 'resources::inputs.radios';

	protected $choiceValues;

}
