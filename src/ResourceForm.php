<?php namespace Cviebrock\LaravelResources;

use Config;
use Illuminate\Support\Collection;
use Illuminate\Support\MessageBag;
use View;


class ResourceForm {

	/**
	 * The resources shown in the form.
	 *
	 * @var Collection
	 */
	protected $resources;

	protected $data = [];

	protected $errors;

	/**
	 * @return mixed
	 */
	public function getErrors() {
		return $this->errors;
	}


	public function validateData() {

		$this->errors = new MessageBag;

		foreach ($this->resources as $key => $resource) {

			$check = $resource->validate($data[$key]);

			if ($check !== true) {
				$this->errors->merge($check);
			}
		}

		return $this->errors->count() == 0;
	}
}
