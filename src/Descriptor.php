<?php namespace Cviebrock\LaravelResources;

use Cviebrock\LaravelResources\Contracts\ResourceDescriptor;
use Input;
use View;


abstract class Descriptor implements ResourceDescriptor {

	/**
	 * @var string
	 */
	protected $prefix = 'resources';

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
	 * @var array
	 */
	private $sessionValues;


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

		return [
			'label' => $this->getName(),
			'id' => $this->key,
			'name' => $this->getInputName(),
			'value' => $this->getInputValue()
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


	/**
	 * Get the form input name
	 *
	 * @return array
	 */
	protected function getInputName() {

		// Group by top level key
		$keys = explode('.', $this->key);
		$groupKey = array_shift($keys);
		$key = $keys ? implode('.', $keys) : '';

		return $key ? "{$this->prefix}[{$groupKey}][{$key}]" : "{$this->prefix}[$this->key]";
	}


	/**
	 * Get the resource key prefixed by the descriptor's prefix
	 *
	 * @return string
	 */
	protected function getPrefixedKey() {

		return "{$this->prefix}.{$this->key}";
	}


	/**
	 * Get the value from the request if it existed due to validation error.
	 *
	 * @return mixed
	 */
	protected function getInputValue() {

		if($oldValue = $this->getSessionValue()) {
			return $oldValue;
		}

		return $this->getValue();
	}


	/**
	 * Get the current resource value from old session inputs
	 *
	 * @return mixed
	 */
	protected function getSessionValue() {

		if(!$this->sessionValues) {
			$this->sessionValues = array_dot(Input::old());
		}

		return array_get($this->sessionValues, $this->getPrefixedKey());
	}

}
