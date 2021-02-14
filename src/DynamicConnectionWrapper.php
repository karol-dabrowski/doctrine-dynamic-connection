<?php
declare(strict_types=1);

namespace DynamicConnection;

use Doctrine\Common\EventManager;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver;

class DynamicConnectionWrapper extends Connection implements DynamicConnection
{
	public function __construct(
		array $params,
		Driver $driver,
		?Configuration $config = null,
		?EventManager $eventManager = null
	) {
		parent::__construct($params, $driver, $config, $eventManager);
	}

	public function changeDatabase(string $databaseName): void
	{
		if ($this->isConnected()) {
			$this->close();
		}

		$params = $this->getParams();
		$params['dbname'] = $databaseName;
		parent::__construct($params, $this->_driver, $this->_config, $this->_eventManager);
	}
}
