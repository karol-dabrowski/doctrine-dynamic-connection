<?php

declare(strict_types=1);

namespace DynamicConnection;

use Doctrine\ORM\Decorator\EntityManagerDecorator;
use DynamicConnection\Exception\ConnectionTypeException;

final class DynamicEntityManager extends EntityManagerDecorator
{
    public function changeDatabase(
        string $databaseName,
        string $username = null,
        string $password = null,
        string $host = null,
        int $port = null
    ): void {
        $connection = $this->getConnection();
        if (!$connection instanceof DynamicConnection) {
            throw new ConnectionTypeException();
        }

        if ($this->isTransactionActive()) {
            $this->rollback();
        }

        $this->clear();

        $params = [];
        $params['dbName'] = $databaseName;

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

        $connection->reinitialize($params);
    }

    private function isTransactionActive(): bool
    {
        return $this->getConnection()->isTransactionActive();
    }
}
