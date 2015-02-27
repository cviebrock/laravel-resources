<?php namespace Cviebrock\LaravelResources\Exceptions;

class ResourceDescriptorNameNotDefinedException extends \Exception {

	/**
	 * @var string
	 */
	protected $reference;


	/**
	 * Set the key name.
	 *
	 * @param string $reference
	 * @return $this
	 */
	public function setReference($reference) {
		$this->reference = $reference;
		$this->message = "Resource name not defined for descriptor [{$reference}].";

		return $this;
	}



	/**
	 * @return string
	 */
	public function getReference() {
		return $this->reference;
	}
}
