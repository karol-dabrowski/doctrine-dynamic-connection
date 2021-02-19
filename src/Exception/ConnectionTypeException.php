<?php

declare(strict_types=1);

namespace DynamicConnection\Exception;

use DynamicConnection\DynamicConnection;
use Exception;

class ConnectionTypeException extends Exception
{
	public function __construct()
	{
		parent::__construct(sprintf(
			'Wrong connection type. Instance of %s expected.', DynamicConnection::class
		));
	}
}
