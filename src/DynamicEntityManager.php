<?php

declare(strict_types=1);

namespace DynamicConnection;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\Decorator\EntityManagerDecorator;
use DynamicConnection\Exception\ConnectionTypeException;

final class DynamicEntityManager extends EntityManagerDecorator
{
    public function changeDatabase(
        ?string $databaseName = null,
        ?string $username = null,
        ?string $password = null,
        ?string $host = null,
        ?string $port = null
    ): void {
        $connection = $this->getConnection();
        $this->validateConnection($connection);

        if ($this->isTransactionActive()) {
            $this->rollback();
        }

        $this->clear();

        $params = $this->getParams($databaseName, $username, $password, $host, $port);
        $connection->reinitialize($params);
    }

    private function validateConnection(Connection $connection)
    {
        if (!$connection instanceof DynamicConnection) {
            throw new ConnectionTypeException();
        }
    }

    private function isTransactionActive(): bool
    {
        return $this->getConnection()->isTransactionActive();
    }

    private function getParams(
        ?string $databaseName,
        ?string $username,
        ?string $password,
        ?string $host,
        ?string $port
    ): array {
        $params = [];

        $params = $this->addParam($params, 'dbname', $databaseName);
        $params = $this->addParam($params, 'user', $username);
        $params = $this->addParam($params, 'password', $password);
        $params = $this->addParam($params, 'host', $host);

        return $this->addParam($params, 'port', $port);
    }

    private function addParam(array $params, string $paramName, ?string $paramValue): array
    {
        if ($paramValue) {
            $params[$paramName] = $paramValue;
        }

        return $params;
    }
}
