<?php

namespace App\Core\Helpers;

use App\Core\Database\Conexao;
use App\Core\Handlers\Logs\LogHandler;
use PDO;
use stdClass;

class ModelHelper
{
    /**
     * Pdo singleton instance
     *
     * @var PDO $pdo
     */
    protected $pdo;

    /**
     * Toggles debug to query execution
     *
     * @var boolean
     */
    protected bool $debug = false;

    /**
     * Pdo Statement
     *
     * @var PDOStatement|false
     */
    private $pdoStmt;

    protected function __construct()
    {
        $this->pdo = Conexao::conexao();
    }

    /**
     * Set debug to TRUE
     *
     * @return self
     */
    protected function debug(): void
    {
        $this->debug = true;
    }

    /**
     * Begins a db transaction
     *
     * @return self
     */
    public function begin(): self
    {
        try {
            $this->pdo ??= Conexao::conexao();

            if (!$this->pdo->inTransaction()) {
                $this->pdo->beginTransaction();
            }

            $this->pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, false);

            return $this;
        } catch (\PDOException $th) {
            throw $th;
        }
    }

    /**
     * Commits a db transaction
     *
     * @return self
     */
    public function commit(): self
    {
        try {
            if ($this->pdo->inTransaction()) {
                $this->pdo->commit();
            }

            $this->pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, true);

            return $this;
        } catch (\PDOException $th) {
            throw $th;
        }
    }

    /**
     * Rolls back a db transaction
     *
     * @return boolean
     */
    public function rollBack(): void
    {
        try {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }

            $this->pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, true);
        } catch (\PDOException $th) {
            throw $th;
        }
    }

    /**
     * Executes a query
     *
     * @param string $sql
     * @param array $params
     * @param bool $strictTypes
     * @return self
     * @throws PDOException
     */
    protected function runQuery(string $sql, array $params = [], bool $strictTypes = false): self
    {
        if ($this->debug) {
            echo "runQuery args: \n";
            print_r([
                "sql"         => $sql,
                "params"      => $params,
                "strictTypes" => $strictTypes
            ]);
            echo "\n\n";
        }

        try {
            $this->pdo ??= Conexao::conexao();
            $this->pdoStmt = $this->pdo->prepare($sql);
            $this->bind($params, $strictTypes);
            $this->pdoStmt->execute();
            return $this;
        } catch (\PDOException $th) {
            LogHandler::logDbQuery($th, $sql, $params, $strictTypes);
            throw $th;
        }
    }

    protected function paginate(string $sql, int $page, int $limit, array $params = []): array
    {
        if ($page === 0) {
            $page = 1;
        }
        if ($limit === 0) {
            $limit = 10;
        }

        $total = $this->paginatedTotal($sql, $params);

        $totalPages = ceil($total / $limit);

        return [
            "data" => $this->paginatedData($sql, $page, $limit, $params),
            "pagination" => [
                "totalResult" => $total,
                "totalPages"  => $totalPages,
                "page"        => $page,
                "nextPage"    => $page >= $totalPages ? $page : $page + 1,
                "prevPage"    => $page <= 1 ? $page : $page - 1,
            ]
        ];
    }

    protected function getArray(): array
    {
        return ($this->hasResults())
            ? $this->pdoStmt->fetch(PDO::FETCH_ASSOC)
            : [];
    }

    protected function getObject(): object
    {
        return ($this->hasResults())
            ? $this->pdoStmt->fetch(PDO::FETCH_OBJ)
            : new stdClass();
    }

    protected function getColumn()
    {
        return ($this->hasResults())
            ? $this->pdoStmt->fetch(PDO::FETCH_COLUMN)
            : null;
    }

    protected function getColumnAsString(): string
    {
        return (string) $this->getColumn();
    }

    protected function getColumnAsFloat(): float
    {
        return (float) $this->getColumn();
    }

    protected function getColumnAsInteger(): int
    {
        return (int) $this->getColumn();
    }

    protected function getAllArray(): array
    {
        return ($this->hasResults())
            ? $this->pdoStmt->fetchAll(PDO::FETCH_ASSOC)
            : [];
    }

    protected function getAllObject(): object
    {
        return ($this->hasResults())
            ? $this->pdoStmt->fetchAll(PDO::FETCH_OBJ)
            : (object) [];
    }

    protected function getAllColumn(): array
    {
        return ($this->hasResults())
            ? $this->pdoStmt->fetchAll(PDO::FETCH_COLUMN)
            : [];
    }

    /**
     * @return string|false
     */
    protected function getLastId()
    {
        return $this->pdo->lastInsertId();
    }

    protected function getRows(): int
    {
        return $this->pdoStmt->rowCount();
    }

    protected function hasResults(): bool
    {
        return $this->getRows() > 0;
    }

    protected function prepareInParams(array $values): InParameters
    {
        return new InParameters($values);
    }

    /**
     * @param array $params
     * @param boolean $strictTypes
     * @return void
     */
    private function bind(array $params, bool $strictTypes): void
    {
        if ($strictTypes) {
            $this->bindStricted($params);
            return;
        }

        $this->simpleBind($params);
    }

    private function simpleBind(array $params): void
    {
        if ($params !== []) {
            foreach ($params as $param => $value) {
                $this->pdoStmt->bindValue($param, $value);
            }
        }
        return;
    }

    private function bindStricted(array $params): void
    {
        if ($params !== []) {
            foreach ($params as $param => $value) {
                $type = null;
                switch (gettype($value)) {
                    case "boolean":
                        $type = PDO::PARAM_BOOL;
                        break;
                    case "integer":
                        $type = PDO::PARAM_INT;
                        break;
                    case "NULL":
                        $type = PDO::PARAM_NULL;
                        break;
                    case "string":
                        $type = PDO::PARAM_STR;
                        break;
                }

                $type !== null
                    ? $this->pdoStmt->bindValue($param, $value, $type)
                    : $this->pdoStmt->bindValue($param, $value);
            }
        }
        return;
    }

    private function paginatedData(string $sql, int $page, int $limit, array $params): array
    {
        return $this->runQuery(
            $sql . " LIMIT " . $this->queryPage($page, $limit) . ", " . $this->queryLimit($limit),
            $params
        )->getAllArray();
    }

    private function paginatedTotal(string $sql, array $params): int
    {
        return $this->runQuery($sql, $params)->getRows();
    }

    private function queryPage(int $page, int $limit): int
    {
        return ($page - 1) * $limit;
    }

    private function queryLimit(int $limit): int
    {
        return $limit === 0 ? 100 : $limit;
    }
}
