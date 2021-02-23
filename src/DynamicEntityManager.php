<?php

declare(strict_types=1);

namespace DynamicConnection;

use Doctrine\ORM\Decorator\EntityManagerDecorator;
use DynamicConnection\Exception\ConnectionTypeException;

final class DynamicEntityManager extends EntityManagerDecorator
{
    public function changeDatabase(
        ?string $databaseName = null,
        ?string $username = null,
        ?string $password = null,
        ?string $host = null,
        ?int $port = null
    ): void {
        $connection = $this->getConnection();
        if (!$connection instanceof DynamicConnection) {
            throw new ConnectionTypeException();
        }

        if ($this->isTransactionActive()) {
            $this->rollback();
        }

        $this->clear();

        $params = $this->getParams($databaseName, $username, $password, $host, $port);
        $connection->reinitialize($params);
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
        ?int $port
    ): array {
        $params = [];

        if ($databaseName) {
            $params['dbName'] = $databaseName;
        }

        if ($username) {
            $params['user'] = $username;
        }

        if ($password) {
            $params['password'] = $password;
        }

        if ($host) {
            $params['host'] = $host;
        }

        if ($port) {
            $params['port'] = $port;
        }

        return $params;
    }
}
