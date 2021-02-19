<?php

declare(strict_types=1);

namespace Tests;

use Doctrine\ORM\EntityManager;
use DynamicConnection\DynamicEntityManager;
use PHPUnit\Framework\TestCase;

class DynamicEntityManagerTest extends TestCase
{
	public function testCanBeCreated()
	{
		$entityManagerMock = $this->createMock(EntityManager::class);
		$dynamicEntityManager = new DynamicEntityManager($entityManagerMock);

		$this->assertInstanceOf(DynamicEntityManager::class, $dynamicEntityManager);
	}
}
