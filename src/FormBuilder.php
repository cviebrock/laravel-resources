<?php namespace Cviebrock\LaravelResources;

use Cviebrock\LaravelResources\Contracts\ResourceDescriptor;
use Cviebrock\LaravelResources\Exceptions\FormActionNotDefined;
use Cviebrock\LaravelResources\Exceptions\FormResourcesNotSpecified;
use Illuminate\Support\Collection;
use Illuminate\Support\Contracts\MessageProviderInterface;
use Illuminate\Support\MessageBag;
use Resource;
use Validator;
use View;

class FormBuilder implements MessageProviderInterface {

	protected $template = 'resources::twig.form';

	/**
	 * @var array
	 */
	protected $errors;

	/**
	 * @var array
	 */
	protected $validation;

	/**
	 * @var Collection
	 */
	protected $resources;

	/**
	 * @var array
	 */
	protected $attributes;

	/**
	 * @var string
	 */
	protected $method;

	/**
	 * @var string
	 */
	protected $action;

	public function __construct($resources, $formAttributes) {

		$this->errors = [];
		$this->loadResourcesWithDescriptors($resources);
		$this->loadFormAttributes($formAttributes);
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

		return new MessageBag($this->errors);
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
	 * Render a form for the given resources
	 *
	 * @return mixed
	 * @throws FormResourcesNotSpecified
	 */
	public function renderForm() {

		return View::make($this->template, $this->getFormData())->render();
	}

	/**
	 * Runs current form validation
	 */
	protected function runValidation() {

		$validation = Validator::make($this->validation);

		if ($invalid = $validation->fails()) {
			$this->addMessageResponse($validation, true);
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

	/**
	 * Load the resource collection with descriptors
	 *
	 * @param $resources
	 */
	private function loadResourcesWithDescriptors($resources) {

		// Map the resources as a collection of descriptors
		$this->resources = new Collection();

		foreach ($resources as $resourceKey => $resourceValue) {

			$resource = $this->resolveAsResource($resourceKey, $resourceValue);

			$this->loadValidationFromResource($resourceKey, $resource);
			$this->resources->put($resourceKey, $resource);
		}

	}

	/**
	 * Resolve a resource item as a ResourceDescriptor
	 *
	 * @param $key
	 * @param $resource
	 * @return ResourceDescriptor
	 */
	private function resolveAsResource($key, $resource) {

		// Should be a resource key => val array
		// Get resource from Resource Manager
		$resourceDescriptor = Resource::key($key)->getDescriptor();

		// Set the resource value on the descriptor class
		$resourceDescriptor->setValue($resource);

		return $resourceDescriptor;
	}

	/**
	 * @return Collection
	 * @throws FormResourcesNotSpecified
	 */
	private function getFormData() {

		$resources = $this->resources;

		if (empty($resources)) {
			throw new FormResourcesNotSpecified('No resources were found to render the form.');
		}

		return compact('resources') + [
			'action' => $this->action,
			'method' => $this->method,
			'attributes' => $this->attributes,
		];
	}

	/**
	 * Load required form attributes
	 *  [
	 *      action => The url the form is submitting to,
	 *      method => Request type form is using to submit,
	 *      attributes => Any attributes added to form element, such as class="form"
	 *  ]
	 *
	 * @param $formAttributes
	 * @throws FormActionNotDefined
	 */
	private function loadFormAttributes($formAttributes) {

		if (empty($formAttributes['action'])) {
			throw new FormActionNotDefined('The form action was not set.');
		}

		$this->action = $formAttributes['action'];
		$this->method = 'POST';
		$this->attributes = [];

		if (!empty($formAttributes['attributes'])) {
			$this->attributes = $formAttributes['attributes'];
		}

		if (!empty($formAttributes['method'])) {
			$this->method = $formAttributes['method'];
		}
	}
}