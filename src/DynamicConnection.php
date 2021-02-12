<?php
declare(strict_types=1);

namespace DynamicConnection;

use Doctrine\DBAL\Driver\Connection;

interface DynamicConnection extends Connection
{
	public function changeDatabase(string $databaseName): void;
}
