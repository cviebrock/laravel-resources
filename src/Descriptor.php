<?php namespace Cviebrock\LaravelResources;

use Cviebrock\LaravelResources\Contracts\ResourceDescriptor;
use View;


abstract class Descriptor implements ResourceDescriptor {

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
	protected $validation = [];


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
	 * Transform the native value into a format suitable for storage.
	 *
	 * @param $value mixed
	 * @return string
	 */
	public function toStorage($value) {

		return serialize($value);
	}


	/**
	 * Transform the value from the stored value to a native value.
	 *
	 * @param $value string
	 * @return mixed
	 */
	public function fromStorage($value) {

		return unserialize($value);
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
	public function renderInput() {

		return View::make($this->template, $this->getInputData())->render();
	}

	/**
	 * Form input data used to render the descriptor as an input
	 *
	 * @return array
	 */
	protected function getInputData() {

		// Group by top level key
		$keys = explode('.', $this->key);
		$groupKey = array_shift($keys);
		$key = $keys ? implode('.', $keys) : '';

		return [
			'label' => $this->getName(),
			'id' => $this->key,
			'name' => $key ? "resources[{$groupKey}][{$key}]" : "resources[$this->key]",
			'value' => $this->getValue()
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
