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
}
