<?php
declare(strict_types=1);

namespace App\Database\Statement;

use Cake\Database\DriverInterface;
use Cake\Database\Statement\BufferedStatement;
use Cake\Database\Statement\BufferResultsTrait;
use Cake\Database\Statement\StatementDecorator;

class OracleStatement extends StatementDecorator
{
    use BufferResultsTrait;

    protected array $_aliasMap = [];
    protected string $_sql = '';

    public function __construct(StatementDecorator $statement, DriverInterface $driver, array $aliasMap = [], string $sql = '')
    {
        parent::__construct($statement, $driver);
        $this->_aliasMap = $aliasMap;
        $this->_sql = $sql;
    }

    public function execute(?array $params = null): bool
    {
        if ($this->_statement instanceof BufferedStatement) {
            $this->_statement = $this->_statement->getInnerStatement();
        }

        $isSelect = preg_match('/^\s*(?:WITH|SELECT)/i', $this->_sql) === 1;
        if ($this->_bufferResults && $isSelect) {
            $this->_statement = new BufferedStatement($this->_statement, $this->_driver);
        }

        return $this->_statement->execute($params);
    }

    public function rowCount(): int
    {
        $stmt = $this->_statement;
        if ($stmt instanceof BufferedStatement) {
            $stmt = $stmt->getInnerStatement();
        }

        // Para sentencias que no son SELECT (INSERT, UPDATE, DELETE), retornar directamente rowCount de PDO
        if (!preg_match('/^\s*(?:WITH|SELECT)/i', $this->_sql)) {
            return $stmt->rowCount();
        }

        return parent::rowCount();
    }

    public function fetch($type = self::FETCH_TYPE_NUM)
    {
        $row = parent::fetch($type);
        if ($row && is_array($row) && !empty($this->_aliasMap)) {
            $normalized = [];
            foreach ($row as $k => $v) {
                $lower = strtolower((string)$k);
                $key = $this->_aliasMap[$lower] ?? $k;
                $normalized[$key] = $v;
            }
            return $normalized;
        }

        return $row;
    }

    public function fetchAll($type = self::FETCH_TYPE_NUM): array
    {
        $rows = parent::fetchAll($type);
        if (!empty($rows) && !empty($this->_aliasMap) && is_array($rows[0] ?? null)) {
            $result = [];
            foreach ($rows as $row) {
                $normalized = [];
                foreach ($row as $k => $v) {
                    $lower = strtolower((string)$k);
                    $key = $this->_aliasMap[$lower] ?? $k;
                    $normalized[$key] = $v;
                }
                $result[] = $normalized;
            }
            return $result;
        }

        return $rows;
    }
}
