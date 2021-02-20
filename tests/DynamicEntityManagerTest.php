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

    public function testCanReinitializeDatabaseWhenOnlyDatabaseNamePassed()
    {
        $dbName = 'TestDB';
        $params = [];
        $params['dbName'] = $dbName;

        $dynamicConnectionMock = $this->createMock(DynamicConnectionWrapper::class);
        $dynamicConnectionMock->method('isTransactionActive')->willReturn(false);
        $dynamicConnectionMock->expects($this->once())->method('reinitialize')->with($this->equalTo($params));

        $entityManagerMock = $this->createMock(EntityManager::class);
        $entityManagerMock->method('getConnection')->willReturn($dynamicConnectionMock);

        $dynamicEntityManager = new DynamicEntityManager($entityManagerMock);
        $dynamicEntityManager->changeDatabase($dbName);
    }
}
