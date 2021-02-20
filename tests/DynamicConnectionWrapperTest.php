<?php

declare(strict_types=1);

namespace Tests;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver;
use Doctrine\DBAL\DriverManager;
use DynamicConnection\DynamicConnectionWrapper;
use DynamicConnection\DynamicConnection;
use PHPUnit\Framework\TestCase;

class DynamicConnectionWrapperTest extends TestCase
{
    private DynamicConnection $dynamicConnection;

    protected array $params = [
        'dbname' => 'test_db',
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

    public function testCanBeCreated()
    {
        $this->assertInstanceOf(DynamicConnectionWrapper::class, $this->dynamicConnection);
    }

    public function testIsSubclassOfDbalConnection()
    {
        $this->assertInstanceOf(Connection::class, $this->dynamicConnection);
    }

    public function testImplementsDynamicConnectionInterface()
    {
        $this->assertInstanceOf(DynamicConnection::class, $this->dynamicConnection);
    }

    public function testClosesConnectionIfIsConnected()
    {
        $dynamicConnection = $this->getMockBuilder(DynamicConnectionWrapper::class)
            ->setConstructorArgs([$this->params, $this->createMock(Driver::class)])
            ->onlyMethods(['isConnected', 'close'])
            ->getMock();
        $dynamicConnection->method('isConnected')->willReturn(true);

        $dynamicConnection->expects($this->once())->method('close')->with();
        $dynamicConnection->reinitialize($this->params);
    }

    public function testDoesNotCloseConnectionIfIsNotConnected()
    {
        $dynamicConnection = $this->getMockBuilder(DynamicConnectionWrapper::class)
            ->setConstructorArgs([$this->params, $this->createMock(Driver::class)])
            ->onlyMethods(['isConnected', 'close'])
            ->getMock();
        $dynamicConnection->method('isConnected')->willReturn(false);

        $dynamicConnection->expects($this->never())->method('close')->with();
        $dynamicConnection->reinitialize($this->params);
    }
}
