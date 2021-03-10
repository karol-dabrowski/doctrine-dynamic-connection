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
    private DynamicConnectionWrapper $dynamicConnectionMock;

    private EntityManager $entityManagerMock;

    protected function setUp(): void
    {
        $this->dynamicConnectionMock = $this->createMock(DynamicConnectionWrapper::class);
        $this->dynamicConnectionMock->method('isTransactionActive')->willReturn(false);

        $this->entityManagerMock = $this->createMock(EntityManager::class);
    }

    public function testCanBeCreated()
    {
        $dynamicEntityManager = new DynamicEntityManager($this->entityManagerMock);

        $this->assertInstanceOf(DynamicEntityManager::class, $dynamicEntityManager);
    }

    public function testThrowsExceptionWhenConnectionTypeIsWrong()
    {
        $connectionMock = $this->createMock(Connection::class);
        $this->entityManagerMock->method('getConnection')->willReturn($connectionMock);
        $dynamicEntityManager = new DynamicEntityManager($this->entityManagerMock);

        $this->assertInstanceOf(Connection::class, $dynamicEntityManager->getConnection());
        $this->assertNotInstanceOf(DynamicConnectionWrapper::class, $dynamicEntityManager->getConnection());

        $this->expectException(ConnectionTypeException::class);
        $dynamicEntityManager->modifyConnection('testDb');
    }

    public function testDoesNotCallTransactionRollbackWhenTransactionIsNotActive()
    {
        $this->entityManagerMock->method('getConnection')->willReturn($this->dynamicConnectionMock);
        $this->entityManagerMock->expects($this->never())->method('rollback')->with();

        $dynamicEntityManager = new DynamicEntityManager($this->entityManagerMock);
        $dynamicEntityManager->modifyConnection('TmpDatabase');
    }

    public function testCallsTransactionRollbackWhenTransactionIsActive()
    {
        $dynamicConnectionMock = $this->createMock(DynamicConnectionWrapper::class);
        $dynamicConnectionMock->method('isTransactionActive')->willReturn(true);

        $this->entityManagerMock->method('getConnection')->willReturn($dynamicConnectionMock);
        $this->entityManagerMock->expects($this->once())->method('rollback')->with();

        $dynamicEntityManager = new DynamicEntityManager($this->entityManagerMock);
        $dynamicEntityManager->modifyConnection('TmpDatabase');
    }

    public function testClearsObjectManager()
    {
        $this->entityManagerMock->method('getConnection')->willReturn($this->dynamicConnectionMock);
        $this->entityManagerMock->expects($this->once())->method('clear')->with();

        $dynamicEntityManager = new DynamicEntityManager($this->entityManagerMock);
        $dynamicEntityManager->modifyConnection('test_database');
    }

    public function testCanReinitializeWithSameParametersWhenNoParametersPassed()
    {
        $this->dynamicConnectionMock->expects($this->once())->method('reinitialize')->with($this->equalTo([]));
        $this->entityManagerMock->method('getConnection')->willReturn($this->dynamicConnectionMock);

        $dynamicEntityManager = new DynamicEntityManager($this->entityManagerMock);
        $dynamicEntityManager->modifyConnection();
    }

    public function testCanReinitializeDatabaseWhenOnlyDatabaseNamePassed()
    {
        $params = [];
        $params['dbname'] = 'TestDB';

        $this->dynamicConnectionMock->expects($this->once())->method('reinitialize')->with($this->equalTo($params));
        $this->entityManagerMock->method('getConnection')->willReturn($this->dynamicConnectionMock);

        $dynamicEntityManager = new DynamicEntityManager($this->entityManagerMock);
        $dynamicEntityManager->modifyConnection($params['dbname']);
    }

    public function testCanReinitializeDatabaseWhenOnlyUsernamePassed()
    {
        $params = [];
        $params['user'] = 'test_user';

        $this->dynamicConnectionMock->expects($this->once())->method('reinitialize')->with($this->equalTo($params));
        $this->entityManagerMock->method('getConnection')->willReturn($this->dynamicConnectionMock);

        $dynamicEntityManager = new DynamicEntityManager($this->entityManagerMock);
        $dynamicEntityManager->modifyConnection(null, $params['user']);
    }

    public function testCanReinitializeDatabaseWhenUsernameAndPasswordPassed()
    {
        $params = [];
        $params['user'] = 'test_user';
        $params['password'] = 'new_password_123';

        $this->dynamicConnectionMock->expects($this->once())->method('reinitialize')->with($this->equalTo($params));
        $this->entityManagerMock->method('getConnection')->willReturn($this->dynamicConnectionMock);

        $dynamicEntityManager = new DynamicEntityManager($this->entityManagerMock);
        $dynamicEntityManager->modifyConnection(null, $params['user'], $params['password']);
    }

    public function testCanReinitializeDatabaseWhenDatabaseNameAndUsernameAndPasswordPassed()
    {
        $params = [];
        $params['dbname'] = 'TestDB';
        $params['user'] = 'test_user';
        $params['password'] = 'new_password_123';

        $this->dynamicConnectionMock->expects($this->once())->method('reinitialize')->with($this->equalTo($params));
        $this->entityManagerMock->method('getConnection')->willReturn($this->dynamicConnectionMock);

        $dynamicEntityManager = new DynamicEntityManager($this->entityManagerMock);
        $dynamicEntityManager->modifyConnection($params['dbname'], $params['user'], $params['password']);
    }

    public function testCanReinitializeDatabaseWhenOnlyHostPassed()
    {
        $params = [];
        $params['host'] = 'localhost2';

        $this->dynamicConnectionMock->expects($this->once())->method('reinitialize')->with($this->equalTo($params));
        $this->entityManagerMock->method('getConnection')->willReturn($this->dynamicConnectionMock);

        $dynamicEntityManager = new DynamicEntityManager($this->entityManagerMock);
        $dynamicEntityManager->modifyConnection(null, null, null, $params['host']);
    }

    public function testCanReinitializeDatabaseWhenOnlyPortPassed()
    {
        $params = [];
        $params['port'] = '3308';

        $this->dynamicConnectionMock->expects($this->once())->method('reinitialize')->with($this->equalTo($params));
        $this->entityManagerMock->method('getConnection')->willReturn($this->dynamicConnectionMock);

        $dynamicEntityManager = new DynamicEntityManager($this->entityManagerMock);
        $dynamicEntityManager->modifyConnection(null, null, null, null, $params['port']);
    }

    public function testCanReinitializeDatabaseWhenDatabaseNameAndHostAndPortPassed()
    {
        $params = [];
        $params['dbname'] = 'TestDB';
        $params['host'] = 'localhost2';
        $params['port'] = '3308';

        $this->dynamicConnectionMock->expects($this->once())->method('reinitialize')->with($this->equalTo($params));
        $this->entityManagerMock->method('getConnection')->willReturn($this->dynamicConnectionMock);

        $dynamicEntityManager = new DynamicEntityManager($this->entityManagerMock);
        $dynamicEntityManager->modifyConnection($params['dbname'], null, null, $params['host'], $params['port']);
    }

    public function testCanReinitializeDatabaseWhenDatabaseNameAndUsernameAndPasswordAndHostAndPortPassed()
    {
        $params = [];
        $params['dbname'] = 'TestDB';
        $params['user'] = 'test_user';
        $params['password'] = 'new_password_123';
        $params['host'] = 'localhost2';
        $params['port'] = '3308';

        $this->dynamicConnectionMock->expects($this->once())->method('reinitialize')->with($this->equalTo($params));
        $this->entityManagerMock->method('getConnection')->willReturn($this->dynamicConnectionMock);

        $dynamicEntityManager = new DynamicEntityManager($this->entityManagerMock);
        $dynamicEntityManager->modifyConnection(
            $params['dbname'],
            $params['user'],
            $params['password'],
            $params['host'],
            $params['port']
        );
    }
}
