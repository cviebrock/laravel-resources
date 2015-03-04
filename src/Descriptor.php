<?php namespace Cviebrock\LaravelResources;

use Config;
use Cviebrock\LaravelResources\Contracts\DescriptorInterface;
use Cviebrock\LaravelResources\Contracts\StorageInterface;
use Illuminate\Validation\Factory as Validator;
use View;


abstract class Descriptor implements DescriptorInterface, StorageInterface {

	/**
	 * @var Validator
	 */
	protected $validator;

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
	protected $locale;

	/**
	 * @var
	 */
	protected $value;

	/**
	 * @var string
	 */
	protected $template = '';

	/**
	 * Default validation rules.
	 *
	 * @var array
	 */
	protected $validationRules = ['required'];

	/**
	 * Default validation messages.
	 *
	 * @var array
	 */
	protected $validationMessages = [];


	/**
	 * Constructor.
	 *
	 * @param Validator $validator
	 */
	public function __construct(Validator $validator) {

		$this->validator = $validator;
	}


	/**
	 * Get the value of the resource descriptor.
	 *
	 * @return mixed
	 */
	public function getValue() {

		return $this->value;
	}


	/**
	 * Set the value of the resource descriptor.
	 *
	 * @param $value
	 * @return mixed|void
	 */
	public function setValue($value) {

		$this->value = $value;
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
	 * Render the descriptor as a form input.
	 *
	 * @param mixed $value
	 * @return mixed
	 */
	public function renderInput($value) {

		$data = $this->getInputData($value);

		return View::make($this->template, $data)->render();
	}


	/**
	 * @param $value
	 * @return array
	 */
	protected function getInputData($value) {

		$data = [
			'name' => $this->getName(),
			'description' => $this->getDescription(),
			'id' => $this->key,
			'fieldName' => 'resources[' . $this->key . ']',
			'value' => $value
		];

		return $data;
	}


	/**
	 * Get the name for the resource.
	 *
	 * @return mixed
	 */
	public function getName() {

		if (!$this->name) {
			throw (new ResourceDescriptorNameNotDefinedException)->setReference(get_called_class());
		}

		return $this->name;
	}


	/**
	 * Validate value against rules.
	 *
	 * @param $value
	 * @return bool|MessageBag
	 */
	public function validate($value) {

		$key = $this->getKey();

		$validator = app('validator')->make(
			[$key => $value],
			[$key => $this->getValidationRules()],
			[$this->getValidationMessages()]
		);

		if ($validator->passes()) {
			return true;
		}

		return $validator->messages();
	}


	/**
	 * @return mixed
	 */
	public function getKey() {
		return $this->key;
	}


	/**
	 * Set the resource key.
	 *
	 * @param mixed $key
	 */
	public function setKey($key) {
		$this->key = $key;
	}


	/**
	 * @return array
	 */
	public function getValidationRules() {
		return $this->validationRules;
	}


	/**
	 * @return array
	 */
	public function getValidationMessages() {
		return $this->validationMessages;
	}


	/**
	 * @return string
	 */
	public function getLocale() {
		return $this->locale;
	}


	/**
	 * Set the resource locale.
	 *
	 * @param string $locale
	 */
	public function setLocale($locale) {
		$this->locale = $locale;
	}
}
