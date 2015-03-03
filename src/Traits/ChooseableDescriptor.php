<?php namespace Cviebrock\LaravelResources\Traits;

use Cviebrock\LaravelResources\Exceptions\ResourceDescriptorChoicesNotDefinedException;


trait ChooseableDescriptor {

	/**
	 * Add choice options to form input data.
	 *
	 * @param $value
	 * @return array
	 * @throws ResourceDescriptorChoicesNotDefinedException
	 */
	protected function getInputData($value) {

		$data = parent::getInputData($value);

		$data['choices'] = $this->getChoices();

		return $data;
	}


	/**
	 * Return the list of choices available for the descriptor.
	 *
	 * @return array
	 * @throws ResourceDescriptorChoicesNotDefinedException
	 */
	public function getChoices() {

		$locale = $this->getLocale();
		$choices = array_get($this->choiceValues, $locale);

		if (!$choices) {
			throw (new ResourceDescriptorChoicesNotDefinedException)->setReference(get_called_class(), $locale);
		}

		return $choices;
	}
}
