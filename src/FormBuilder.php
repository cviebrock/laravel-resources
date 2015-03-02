<?php namespace Cviebrock\LaravelResources;

use Config;
use Cviebrock\LaravelResources\Exceptions\FormActionNotDefined;
use Cviebrock\LaravelResources\Exceptions\FormResourcesNotSpecified;
use Cviebrock\LaravelResources\Traits\ResourceDescriptorLoader;
use Illuminate\Support\Collection;
use View;


class FormBuilder {

//	use ResourceDescriptorLoader;

	/**
	 * The base form for the template.
	 *
	 * @var string
	 */
	protected $template = 'resources::form';

	/**
	 * The resources shown in the form.
	 *
	 * @var Collection
	 */
	protected $resources;

	/**
	 * Extra attributes to be applied to the form.
	 *
	 * @var array
	 */
	protected $attributes = [];

	/**
	 * Form method (i.e. "POST", "GET").
	 *
	 * @var string
	 */
	protected $method = 'POST';

	/**
	 * Form action.
	 *
	 * @var string
	 */
	protected $action;


	public function __construct($resources) {

		$this->resources = $resources;
		$this->arrangeFormOrder();
	}

//
//	/**
//	 * Load required form attributes
//	 *  [
//	 *      action => The url the form is submitting to,
//	 *      method => Request type form is using to submit,
//	 *      attributes => Any attributes added to form element, such as class="form"
//	 *  ]
//	 *
//	 * @param array $formAttributes
//	 * @throws FormActionNotDefined
//	 */
//	private function loadFormAttributes(array $formAttributes) {
//
//		if (empty($formAttributes['action'])) {
//			throw new FormActionNotDefined('The form action was not set.');
//		}
//
//		$this->action = $formAttributes['action'];
//		$this->method = 'POST';
//		$this->attributes = [];
//
//		if (!empty($formAttributes['attributes'])) {
//			$this->attributes = $formAttributes['attributes'];
//		}
//
//		if (!empty($formAttributes['method'])) {
//			$this->method = $formAttributes['method'];
//		}
//	}
//

	/**
	 * Arrange the order of the resources so they mirror the order in the resources
	 * config.
	 */
	private function arrangeFormOrder() {

		$resourceMap = array_dot(Config::get('resources::resources'));
		$sortOrder = array_flip(array_keys($resourceMap));

		$this->resources->sort(function ($a, $b) use ($sortOrder) {
			return $sortOrder[$a->getKey()] > $sortOrder[$b->getKey()];
		});
	}


	/**
	 * Render the form if outputting this class.
	 *
	 * @return mixed
	 */
	public function __toString() {

		return $this->render();
	}


	/**
	 * Render a form for the given resources.
	 *
	 * @return mixed
	 * @throws FormResourcesNotSpecified
	 */
	public function render() {

		return View::make($this->template, $this->getFormData())->render();
	}


	/**
	 * Get the form data.
	 *
	 * @return array
	 * @throws FormResourcesNotSpecified
	 */
	private function getFormData() {

		$resources = $this->resources;

		if (empty($resources)) {
			throw new FormResourcesNotSpecified('No resources were found to render the form.');
		}

		return [
			'action' => $this->action,
			'method' => $this->method,
			'attributes' => $this->attributes,
			'resources' => $resources,
		];
	}


	/**
	 * Set the attributes on the form.
	 *
	 * @param array $attributes
	 * @return $this
	 */
	public function withAttributes($attributes) {
		$this->attributes = $attributes;

		return $this;
	}


	/**
	 * Set the method on the form.
	 *
	 * @param string $method
	 * @return $this
	 */
	public function method($method) {
		$this->method = $method;

		return $this;
	}


	/**
	 * Set the action of the form.
	 *
	 * @param string $action
	 * @return $this
	 */
	public function action($action) {
		$this->action = $action;

		return $this;
	}

}
