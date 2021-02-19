<?php

declare(strict_types=1);

namespace DynamicConnection;

use Doctrine\ORM\Decorator\EntityManagerDecorator;
use DynamicConnection\Exception\ConnectionTypeException;

final class DynamicEntityManager extends EntityManagerDecorator
{
    public function changeDatabase(string $databaseName, bool $clear = true): void
    {
        $connection = $this->getConnection();
        if (!$connection instanceof DynamicConnection) {
            throw new ConnectionTypeException();
        }

        if ($this->isTransactionActive()) {
            $this->rollback();
        }

        if ($clear) {
            $this->clear();
        }

        $params = [];
        $params['dbName'] = $databaseName;

        $connection->reinitialize($params);
    }

    private function isTransactionActive(): bool
    {
        return $this->getConnection()->isTransactionActive();
    }
}
