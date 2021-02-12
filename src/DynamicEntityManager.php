<?php
declare(strict_types=1);

namespace DynamicConnection;

use Doctrine\ORM\Decorator\EntityManagerDecorator;
use DynamicConnection\Exception\ConnectionTypeException;

final class DynamicEntityManager extends EntityManagerDecorator
{
	public function changeDatabase(string $databaseName): void
	{
		$connection = $this->getConnection();
		if(!$connection instanceof DynamicConnection) {
			throw new ConnectionTypeException();
		}

		$this->clear();
		$connection->changeDatabase($databaseName);
	}
}
