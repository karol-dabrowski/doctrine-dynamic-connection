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

    public function testDatabaseCanBeChanged()
    {
        $newDbName = 'new_db_name';
        $newData = array_merge($this->params, ['dbname' => $newDbName]);

        $this->dynamicConnection->reinitialize($newData);

        $this->assertSame($newDbName, $this->dynamicConnection->getDatabase());
        $this->assertSame($newData, $this->dynamicConnection->getParams());
    }

    public function testDatabaseAndUsernameCanBeChanged()
    {
        $newDbName = 'new_db_name';
        $newUsername = 'username123';
        $newData = array_merge($this->params, ['dbname' => $newDbName, 'username' => $newUsername]);

        $this->dynamicConnection->reinitialize($newData);

        $this->assertSame($newDbName, $this->dynamicConnection->getDatabase());
        $this->assertSame($newData, $this->dynamicConnection->getParams());
    }

    public function testDatabaseAndUsernameAndPasswordCanBeChanged()
    {
        $newDbName = 'new_db_name';
        $newUsername = 'username123';
        $newPassword = 'newPassword';
        $newData = array_merge(
            $this->params,
            ['dbname' => $newDbName, 'username' => $newUsername, 'password' => $newPassword]
        );

        $this->dynamicConnection->reinitialize($newData);

        $this->assertSame($newDbName, $this->dynamicConnection->getDatabase());
        $this->assertSame($newData, $this->dynamicConnection->getParams());
    }

    public function testDatabaseAndUsernameAndPasswordAndHostAndPortCanBeChanged()
    {
        $newDbName = 'new_db_name';
        $newUsername = 'username123';
        $newPassword = 'newPassword';
        $newHost = '127.0.0.1';
        $newPort = '3307';
        $newData = array_merge(
            $this->params,
            [
                'dbname' => $newDbName,
                'username' => $newUsername,
                'password' => $newPassword,
                'host' => $newHost,
                'port' => $newPort
            ]
        );

        $this->dynamicConnection->reinitialize($newData);

        $this->assertSame($newDbName, $this->dynamicConnection->getDatabase());
        $this->assertSame($newData, $this->dynamicConnection->getParams());
    }

    public function testUsernameAndPasswordCanBeChanged()
    {
        $newUsername = 'username123';
        $newPassword = 'newPassword';
        $newData = array_merge($this->params, ['username' => $newUsername, 'password' => $newPassword]);

        $this->dynamicConnection->reinitialize($newData);

        $this->assertSame($newData, $this->dynamicConnection->getParams());
    }

    public function testUsernameAndPasswordAndHostAndPortCanBeChanged()
    {
        $newUsername = 'username123';
        $newPassword = 'newPassword';
        $newHost = '127.0.0.1';
        $newPort = '3307';
        $newData = array_merge(
            $this->params,
            ['username' => $newUsername, 'password' => $newPassword, 'host' => $newHost, 'port' => $newPort]
        );

        $this->dynamicConnection->reinitialize($newData);

        $this->assertSame($newData, $this->dynamicConnection->getParams());
    }

    public function testHostCanBeChanged()
    {
        $newHost = '127.0.0.1';
        $newData = array_merge($this->params, ['host' => $newHost]);

        $this->dynamicConnection->reinitialize($newData);

        $this->assertSame($newData, $this->dynamicConnection->getParams());
    }

    public function testHostAndPortCanBeChanged()
    {
        $newHost = '127.0.0.1';
        $newPort = '3307';
        $newData = array_merge($this->params, ['host' => $newHost, 'port' => $newPort]);

        $this->dynamicConnection->reinitialize($newData);

        $this->assertSame($newData, $this->dynamicConnection->getParams());
    }

    public function testPortCanBeChanged()
    {
        $newPort = '3307';
        $newData = array_merge($this->params, ['port' => $newPort]);

        $this->dynamicConnection->reinitialize($newData);

        $this->assertSame($newData, $this->dynamicConnection->getParams());
    }
}
