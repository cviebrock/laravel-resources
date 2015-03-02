<?php namespace Cviebrock\LaravelResources\Descriptors;


use Cviebrock\LaravelResources\Descriptor;
use Cviebrock\LaravelResources\Descriptors\Storage\SerializedStorage;


abstract class ChooseMany extends Descriptor {

	use SerializedStorage;

	protected $template = 'resources::inputs.checkboxes';

	protected $choiceValues;

	/**
	 * @return mixed
	 */
	public function getChoices() {

		if (!$this->choiceValues) {
			throw (new ResourceDescriptorChoicesNotDefinedException)->setReference(get_called_class());
		}

		return $this->choiceValues;;
	}

}
