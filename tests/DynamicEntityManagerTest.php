<?php

declare(strict_types=1);

namespace Tests;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use DynamicConnection\DynamicConnectionWrapper;
use DynamicConnection\DynamicEntityManager;
use DynamicConnection\Exception\ConnectionTypeException;
use PHPUnit\Framework\TestCase;

class DynamicEntityManagerTest extends TestCase
{
    public function testCanBeCreated()
    {
        $entityManagerMock = $this->createMock(EntityManager::class);
        $dynamicEntityManager = new DynamicEntityManager($entityManagerMock);

        $this->assertInstanceOf(DynamicEntityManager::class, $dynamicEntityManager);
    }

    public function testThrowsExceptionWhenConnectionTypeIsWrong()
    {
        $connectionMock = $this->createMock(Connection::class);

        $entityManagerMock = $this->createMock(EntityManager::class);
        $entityManagerMock->method('getConnection')->willReturn($connectionMock);

        $dynamicEntityManager = new DynamicEntityManager($entityManagerMock);

        $this->assertInstanceOf(Connection::class, $dynamicEntityManager->getConnection());
        $this->assertNotInstanceOf(DynamicConnectionWrapper::class, $dynamicEntityManager->getConnection());

        $this->expectException(ConnectionTypeException::class);
        $dynamicEntityManager->changeDatabase('testDb');
    }

    public function testDoesNotCallTransactionRollbackWhenTransactionIsNotActive()
    {
        $dynamicConnectionMock = $this->createMock(DynamicConnectionWrapper::class);
        $dynamicConnectionMock->method('isTransactionActive')->willReturn(false);

        $entityManagerMock = $this->createMock(EntityManager::class);
        $entityManagerMock->method('getConnection')->willReturn($dynamicConnectionMock);
        $entityManagerMock->expects($this->never())->method('rollback')->with();

        $dynamicEntityManager = new DynamicEntityManager($entityManagerMock);
        $dynamicEntityManager->changeDatabase('TmpDatabase');
    }

    public function testCallsTransactionRollbackWhenTransactionIsActive()
    {
        $dynamicConnectionMock = $this->createMock(DynamicConnectionWrapper::class);
        $dynamicConnectionMock->method('isTransactionActive')->willReturn(true);

        $entityManagerMock = $this->createMock(EntityManager::class);
        $entityManagerMock->method('getConnection')->willReturn($dynamicConnectionMock);
        $entityManagerMock->expects($this->once())->method('rollback')->with();

        $dynamicEntityManager = new DynamicEntityManager($entityManagerMock);
        $dynamicEntityManager->changeDatabase('TmpDatabase');
    }

    public function testClearsObjectManager()
    {
        $dynamicConnectionMock = $this->createMock(DynamicConnectionWrapper::class);
        $dynamicConnectionMock->method('isTransactionActive')->willReturn(false);

        $entityManagerMock = $this->createMock(EntityManager::class);
        $entityManagerMock->method('getConnection')->willReturn($dynamicConnectionMock);
        $entityManagerMock->expects($this->once())->method('clear')->with();

        $dynamicEntityManager = new DynamicEntityManager($entityManagerMock);
        $dynamicEntityManager->changeDatabase('test_database');
    }

    public function testCanReinitializeDatabaseWhenOnlyDatabaseNamePassed()
    {
        $params = [];
        $params['dbname'] = 'TestDB';

        $dynamicConnectionMock = $this->createMock(DynamicConnectionWrapper::class);
        $dynamicConnectionMock->method('isTransactionActive')->willReturn(false);
        $dynamicConnectionMock->expects($this->once())->method('reinitialize')->with($this->equalTo($params));

        $entityManagerMock = $this->createMock(EntityManager::class);
        $entityManagerMock->method('getConnection')->willReturn($dynamicConnectionMock);

        $dynamicEntityManager = new DynamicEntityManager($entityManagerMock);
        $dynamicEntityManager->changeDatabase($params['dbname']);
    }

    public function testCanReinitializeDatabaseWhenOnlyUsernamePassed()
    {
        $params = [];
        $params['user'] = 'test_user';

        $dynamicConnectionMock = $this->createMock(DynamicConnectionWrapper::class);
        $dynamicConnectionMock->method('isTransactionActive')->willReturn(false);
        $dynamicConnectionMock->expects($this->once())->method('reinitialize')->with($this->equalTo($params));

        $entityManagerMock = $this->createMock(EntityManager::class);
        $entityManagerMock->method('getConnection')->willReturn($dynamicConnectionMock);

        $dynamicEntityManager = new DynamicEntityManager($entityManagerMock);
        $dynamicEntityManager->changeDatabase(null, $params['user']);
    }

    public function testCanReinitializeDatabaseWhenUsernameAndPasswordPassed()
    {
        $params = [];
        $params['user'] = 'test_user';
        $params['password'] = 'new_password_123';

        $dynamicConnectionMock = $this->createMock(DynamicConnectionWrapper::class);
        $dynamicConnectionMock->method('isTransactionActive')->willReturn(false);
        $dynamicConnectionMock->expects($this->once())->method('reinitialize')->with($this->equalTo($params));

        $entityManagerMock = $this->createMock(EntityManager::class);
        $entityManagerMock->method('getConnection')->willReturn($dynamicConnectionMock);

        $dynamicEntityManager = new DynamicEntityManager($entityManagerMock);
        $dynamicEntityManager->changeDatabase(null, $params['user'], $params['password']);
    }

    public function testCanReinitializeDatabaseWhenDatabaseNameAndUsernameAndPasswordPassed()
    {
        $params = [];
        $params['dbname'] = 'TestDB';
        $params['user'] = 'test_user';
        $params['password'] = 'new_password_123';

        $dynamicConnectionMock = $this->createMock(DynamicConnectionWrapper::class);
        $dynamicConnectionMock->method('isTransactionActive')->willReturn(false);
        $dynamicConnectionMock->expects($this->once())->method('reinitialize')->with($this->equalTo($params));

        $entityManagerMock = $this->createMock(EntityManager::class);
        $entityManagerMock->method('getConnection')->willReturn($dynamicConnectionMock);

        $dynamicEntityManager = new DynamicEntityManager($entityManagerMock);
        $dynamicEntityManager->changeDatabase($params['dbname'], $params['user'], $params['password']);
    }

    public function testCanReinitializeDatabaseWhenOnlyHostPassed()
    {
        $params = [];
        $params['host'] = 'localhost2';

        $dynamicConnectionMock = $this->createMock(DynamicConnectionWrapper::class);
        $dynamicConnectionMock->method('isTransactionActive')->willReturn(false);
        $dynamicConnectionMock->expects($this->once())->method('reinitialize')->with($this->equalTo($params));

        $entityManagerMock = $this->createMock(EntityManager::class);
        $entityManagerMock->method('getConnection')->willReturn($dynamicConnectionMock);

        $dynamicEntityManager = new DynamicEntityManager($entityManagerMock);
        $dynamicEntityManager->changeDatabase(null, null, null, $params['host']);
    }

    public function testCanReinitializeDatabaseWhenOnlyPortPassed()
    {
        $params = [];
        $params['port'] = '3308';

        $dynamicConnectionMock = $this->createMock(DynamicConnectionWrapper::class);
        $dynamicConnectionMock->method('isTransactionActive')->willReturn(false);
        $dynamicConnectionMock->expects($this->once())->method('reinitialize')->with($this->equalTo($params));

        $entityManagerMock = $this->createMock(EntityManager::class);
        $entityManagerMock->method('getConnection')->willReturn($dynamicConnectionMock);

        $dynamicEntityManager = new DynamicEntityManager($entityManagerMock);
        $dynamicEntityManager->changeDatabase(null, null, null, null, $params['port']);
    }

    public function testCanReinitializeDatabaseWhenDatabaseNameAndHostAndPortPassed()
    {
        $params = [];
        $params['dbname'] = 'TestDB';
        $params['host'] = 'localhost2';
        $params['port'] = '3308';

        $dynamicConnectionMock = $this->createMock(DynamicConnectionWrapper::class);
        $dynamicConnectionMock->method('isTransactionActive')->willReturn(false);
        $dynamicConnectionMock->expects($this->once())->method('reinitialize')->with($this->equalTo($params));

        $entityManagerMock = $this->createMock(EntityManager::class);
        $entityManagerMock->method('getConnection')->willReturn($dynamicConnectionMock);

        $dynamicEntityManager = new DynamicEntityManager($entityManagerMock);
        $dynamicEntityManager->changeDatabase($params['dbname'], null, null, $params['host'], $params['port']);
    }

    public function testCanReinitializeDatabaseWhenDatabaseNameAndUsernameAndPasswordAndHostAndPortPassed()
    {
        $params = [];
        $params['dbname'] = 'TestDB';
        $params['user'] = 'test_user';
        $params['password'] = 'new_password_123';
        $params['host'] = 'localhost2';
        $params['port'] = '3308';

        $dynamicConnectionMock = $this->createMock(DynamicConnectionWrapper::class);
        $dynamicConnectionMock->method('isTransactionActive')->willReturn(false);
        $dynamicConnectionMock->expects($this->once())->method('reinitialize')->with($this->equalTo($params));

        $entityManagerMock = $this->createMock(EntityManager::class);
        $entityManagerMock->method('getConnection')->willReturn($dynamicConnectionMock);

        $dynamicEntityManager = new DynamicEntityManager($entityManagerMock);
        $dynamicEntityManager->changeDatabase(
            $params['dbname'],
            $params['user'],
            $params['password'],
            $params['host'],
            $params['port']
        );
    }
}
