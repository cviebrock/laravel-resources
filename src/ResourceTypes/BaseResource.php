<?php namespace Cviebrock\LaravelResources\ResourceTypes;

abstract class BaseResource {

	abstract public function getTitle();

	abstract public function getDescription();

	abstract public function validatorRules();
}
