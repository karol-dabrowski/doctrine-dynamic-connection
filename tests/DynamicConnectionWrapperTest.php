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

    private array $params = [
        'dbname' => 'test_db',
        'driver' => 'pdo_mysql',
        'host' => 'localhost',
        'user' => 'user',
        'password' => 'password',
        'port' => '3306',
        'wrapperClass' => DynamicConnectionWrapper::class
    ];

    private string $newDbName = 'new_db_name';

    private string $newUsername = 'username123';

    private string $newPassword = 'newPassword';

    private string $newHost = '127.0.0.1';

    private string $newPort = '3307';

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
        $newData = array_merge($this->params, ['dbname' => $this->newDbName]);

        $this->dynamicConnection->reinitialize($newData);

        $this->assertSame($this->newDbName, $this->dynamicConnection->getDatabase());
        $this->assertSame($newData, $this->dynamicConnection->getParams());
    }

    public function testDatabaseAndUsernameCanBeChanged()
    {
        $newData = array_merge($this->params, ['dbname' => $this->newDbName, 'username' => $this->newUsername]);

        $this->dynamicConnection->reinitialize($newData);

        $this->assertSame($this->newDbName, $this->dynamicConnection->getDatabase());
        $this->assertSame($newData, $this->dynamicConnection->getParams());
    }

    public function testDatabaseAndUsernameAndPasswordCanBeChanged()
    {
        $newData = array_merge(
            $this->params,
            ['dbname' => $this->newDbName, 'username' => $this->newUsername, 'password' => $this->newPassword]
        );

        $this->dynamicConnection->reinitialize($newData);

        $this->assertSame($this->newDbName, $this->dynamicConnection->getDatabase());
        $this->assertSame($newData, $this->dynamicConnection->getParams());
    }

    public function testDatabaseAndUsernameAndPasswordAndHostAndPortCanBeChanged()
    {
        $newData = array_merge(
            $this->params,
            [
                'dbname' => $this->newDbName,
                'username' => $this->newUsername,
                'password' => $this->newPassword,
                'host' => $this->newHost,
                'port' => $this->newPort
            ]
        );

        $this->dynamicConnection->reinitialize($newData);

        $this->assertSame($this->newDbName, $this->dynamicConnection->getDatabase());
        $this->assertSame($newData, $this->dynamicConnection->getParams());
    }

    public function testUsernameAndPasswordCanBeChanged()
    {
        $newData = array_merge($this->params, ['username' => $this->newUsername, 'password' => $this->newPassword]);

        $this->dynamicConnection->reinitialize($newData);

        $this->assertSame($newData, $this->dynamicConnection->getParams());
    }

    public function testUsernameAndPasswordAndHostAndPortCanBeChanged()
    {
        $newData = array_merge(
            $this->params,
            [
                'username' => $this->newUsername,
                'password' => $this->newPassword,
                'host' => $this->newHost,
                'port' => $this->newPort
            ]
        );

        $this->dynamicConnection->reinitialize($newData);

        $this->assertSame($newData, $this->dynamicConnection->getParams());
    }

    public function testHostCanBeChanged()
    {
        $newData = array_merge($this->params, ['host' => $this->newHost]);

        $this->dynamicConnection->reinitialize($newData);

        $this->assertSame($newData, $this->dynamicConnection->getParams());
    }

    public function testHostAndPortCanBeChanged()
    {
        $newData = array_merge($this->params, ['host' => $this->newHost, 'port' => $this->newPort]);

        $this->dynamicConnection->reinitialize($newData);

        $this->assertSame($newData, $this->dynamicConnection->getParams());
    }

    public function testPortCanBeChanged()
    {
        $newData = array_merge($this->params, ['port' => $this->newPort]);

        $this->dynamicConnection->reinitialize($newData);

        $this->assertSame($newData, $this->dynamicConnection->getParams());
    }
}
