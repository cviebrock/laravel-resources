<?php namespace Cviebrock\LaravelResources;

use Cviebrock\LaravelResources\Exceptions\FormActionNotDefined;
use Cviebrock\LaravelResources\Exceptions\FormResourcesNotSpecified;
use Cviebrock\LaravelResources\Traits\ResourceDescriptorLoader;
use Illuminate\Support\Collection;
use Resource;
use View;

class FormBuilder {

	use ResourceDescriptorLoader;

	/**
	 * @var string
	 */
	protected $template = 'resources::form';

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

		$this->resources = $this->loadResourcesWithDescriptors($resources);
		$this->loadFormAttributes($formAttributes);
		$this->arrangeFormOrder();
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
	 * Render the form if outputting this class
	 *
	 * @return mixed
	 */
	public function __toString() {

		return $this->renderForm();
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


	/**
	 * Arrange the order of the resources so they mirror the order in the resources
	 * config.
	 */
	private function arrangeFormOrder() {

		$resourceKeysOrdered = new Collection(array_only(Resource::getResourceMap(), $this->resources->keys()));

		$this->resources = $resourceKeysOrdered->merge($this->resources);

	}

}