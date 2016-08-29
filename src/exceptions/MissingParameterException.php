<?php

namespace Tp;

class MissingParameterException extends InvalidParameterException
{
	function __construct($parameter)
	{
		parent::__construct($parameter);

		$this->message = "Missing parameter " . $parameter;
	}
}