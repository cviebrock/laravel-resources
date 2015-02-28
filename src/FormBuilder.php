<?php

namespace Cviebrock\LaravelResources;


use Cviebrock\LaravelResources\Contracts\ResourceDescriptor;
use Cviebrock\LaravelResources\Exceptions\FormResourcesNotSpecified;
use Cviebrock\LaravelResources\Exceptions\ResourceNotDefinedException;
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

	public function __construct($resources) {

		$this->errors = [];
		$this->loadResourcesWithDescriptors($resources);
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

		$resources = $this->resources;

		if (empty($resources)) {
			throw new FormResourcesNotSpecified('No resources were found to render the form.');
		}

		return View::make($this->template, compact('resources'))->render();
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

	private function loadValidationFromResource(ResourceDescriptor $resource) {

		if ($validation = $resource->validate()) {
			$this->addValidation($resource->key, $validation);
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

			$this->loadValidationFromResource($resource);
			$this->resources->put($resource->key, $resource);
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

		// Probably a resource key => val array
		// Get resource from Resource Manager
		return Resource::key($key)->getDescriptor()->setValue($resource);
	}
}