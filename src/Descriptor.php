<?php namespace Cviebrock\LaravelResources;

use Cviebrock\LaravelResources\Contracts\ResourceDescriptor;
use Cviebrock\LaravelResources\Contracts\ResourceStorage;
use View;


abstract class Descriptor implements ResourceDescriptor, ResourceStorage {

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var string
	 */
	protected $description = '';

	/**
	 * @var array
	 */
	protected $seedValues = [];

	/**
	 * @var
	 */
	protected $key;

	/**
	 * @var
	 */
	protected $value;

	/**
	 * @var string
	 */
	protected $template = '';

	/**
	 * @var array
	 */
	protected $validation = '';


	/**
	 * @param $key
	 */
	public function __construct($key) {

		$this->key = $key;
	}


	/**
	 * @param $value
	 */
	public function setValue($value) {

		$this->value = $value;
	}


	/**
	 * @return mixed
	 */
	public function getValue() {

		return $this->value;
	}


	/**
	 * @return string
	 */
	public function getDescription() {

		return $this->description;
	}


	/**
	 * @return array
	 */
	public function getSeedValues() {

		return $this->seedValues;
	}


	/**
	 * @return mixed
	 */
	public function getName() {

		if (!$this->name) {
			throw (new ResourceDescriptorNameNotDefinedException)->setReference(get_called_class());
		}

		return $this->name;
	}


	/**
	 * Render the descriptor as a form input
	 *
	 * @return mixed
	 */
	public function renderInput($value) {

		$data = $this->getInputData();
		$data['value'] = $value;

		return View::make($this->template, $data)->render();
	}


	/**
	 * Form input data used to render the descriptor as an input
	 *
	 * @return array
	 */
	protected function getInputData() {

		return [
			'label' => $this->getName(),
			'id' => $this->key,
			'name' => 'resources[' . $this->key . ']',
//			'value' => $this->getValue()
		];
	}


	/**
	 * Base validation criteria
	 *
	 * @return array
	 */
	public function validate() {

		return $this->validation;
	}

}
