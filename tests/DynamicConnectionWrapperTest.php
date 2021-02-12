<?php
declare(strict_types=1);

namespace Tests;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use DynamicConnection\DynamicConnectionWrapper;
use DynamicConnection\DynamicConnection;
use PHPUnit\Framework\TestCase;

class DynamicConnectionWrapperTest extends TestCase
{
	private DynamicConnection $dynamicConnection;

	protected array $params = [
		'driver' => 'pdo_mysql',
		'host' => 'localhost',
		'user' => 'user',
		'password' => 'password',
		'port' => '3306',
		'wrapperClass' => DynamicConnectionWrapper::class
	];

	protected function setUp(): void
	{
		$this->dynamicConnection = DriverManager::getConnection($this->params);
	}

	public function testConnectionIsConnectionWrapperInstance()
	{
		self::assertInstanceOf(DynamicConnectionWrapper::class, $this->dynamicConnection);
	}

	public function testConnectionIsSubclassOfDbalConnection()
	{
		self::assertInstanceOf(Connection::class, $this->dynamicConnection);
	}

	public function testConnectionImplementsDynamicConnectionInterface()
	{
		self::assertInstanceOf(DynamicConnection::class, $this->dynamicConnection);
	}
}
