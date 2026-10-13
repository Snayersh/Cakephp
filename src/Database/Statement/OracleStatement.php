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

    public function __construct(StatementDecorator $statement, DriverInterface $driver, array $aliasMap = [])
    {
        parent::__construct($statement, $driver);
        $this->_aliasMap = $aliasMap;
    }

    public function execute(?array $params = null): bool
    {
        if ($this->_statement instanceof BufferedStatement) {
            $this->_statement = $this->_statement->getInnerStatement();
        }

        if ($this->_bufferResults) {
            $this->_statement = new BufferedStatement($this->_statement, $this->_driver);
        }

        return $this->_statement->execute($params);
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
