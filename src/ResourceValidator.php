<?php namespace Cviebrock\LaravelResources;

use Cviebrock\LaravelResources\Contracts\ResourceDescriptor;
use Cviebrock\LaravelResources\Traits\ResourceDescriptorLoader;
use Illuminate\Support\Contracts\MessageProviderInterface;
use Illuminate\Support\MessageBag;
use Validator;

class ResourceValidator implements MessageProviderInterface {

	use ResourceDescriptorLoader;

	/**
	 * @var array
	 */
	protected $errors;

	/**
	 * @var array
	 */
	protected $validation;

	/**
	 * @var array
	 */
	protected $resources = [];


	/**
	 * @param $resources
	 */
	public function __construct($resources) {

		$this->errors = [];
		$this->resources = $this->loadResourcesWithDescriptors($resources);
	}


	/**
	 * Add validation rules
	 *
	 * @param $field
	 * @param $rule
	 */
	public function addValidation($field, $rule) {

		$this->validation[$field] = $rule;
	}


	/**
	 * The error messages.
	 *
	 * @return MessageBag
	 */
	public function errors() {

		if ($this->errors instanceof MessageBag) {
			return $this->errors;
		}

		return $this->errors = new MessageBag($this->errors);
	}


	/**
	 * Get the messages for the instance.
	 *
	 * @return \Illuminate\Support\MessageBag
	 */
	public function getMessageBag() {

		return $this->errors();
	}


	/**
	 * Check if form validation passes
	 *
	 * @return bool
	 */
	public function passes() {

		return $this->runValidation();
	}


	/**
	 * Check if form validation fails
	 *
	 * @return bool
	 */
	public function fails() {

		return !$this->passes();
	}


	/**
	 * Add load processing hooks for newly loaded resources.
	 *
	 * @param $resourceKey
	 * @param $resource
	 */
	protected function processResourceOnLoad($resourceKey, $resource) {

		$this->loadValidationFromResource($resourceKey, $resource);
	}


	/**
	 * Runs current form validation
	 */
	protected function runValidation() {

		$validation = Validator::make($this->validation, $this->validateCollection);

		if ($invalid = $validation->fails()) {
			$this->errors = $validation->errors();
		}

		return !$invalid;
	}


	/**
	 * Load validation rules from a resource
	 *
	 * @param $resourceKey
	 * @param ResourceDescriptor $resource
	 */
	private function loadValidationFromResource($resourceKey, ResourceDescriptor $resource) {

		if ($validation = $resource->validate()) {
			$this->addValidation($resourceKey, $validation);
		}
	}

}