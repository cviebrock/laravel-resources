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
	protected $validationRules = [ 'required' ];

	/**
	 * Default validation messages.
	 *
	 * @var array
	 */
	protected $validationMessages = [];


	/**
	 * Constructor.
	 *
	 * @param $key string
	 * @param $locale string
	 */
	public function __construct($key, $locale) {

		$this->key = $key;
		$this->locale = $locale;
	}


	/**
	 * @return mixed
	 */
	public function getValue() {

		return $this->value;
	}


	/**
	 * @param $value
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
	 * Render the descriptor as a form input
	 *
	 * @param mixed $value
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
		];
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
	 * @return mixed
	 */
	public function getLocale() {
		return $this->locale;
	}
}
