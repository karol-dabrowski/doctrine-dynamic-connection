<?php
declare(strict_types=1);

namespace Tests;

use DynamicConnection\Exception\ConnectionTypeException;
use PHPUnit\Framework\TestCase;

class ConnectionTypeExceptionTest extends TestCase
{
	private ConnectionTypeException $exception;

	protected function setUp(): void
	{
		$this->exception = new ConnectionTypeException();
	}

	public function testCanBeCreated()
	{
		$this->assertInstanceOf(ConnectionTypeException::class, $this->exception);
	}

	public function testReturnsCorrectMessage()
	{
		$this->assertIsString($this->exception->getMessage());
		$this->assertEquals(
			"Wrong connection type. Instance of DynamicConnection\DynamicConnection expected.",
			$this->exception->getMessage()
		);
	}
}
