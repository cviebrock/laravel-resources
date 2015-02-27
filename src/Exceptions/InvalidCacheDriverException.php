<?php namespace Cviebrock\LaravelResources\Exceptions;

class InvalidCacheDriverException extends \Exception {

	protected $message = 'Application cache driver must support cache tagging for use with resources package.';

}
