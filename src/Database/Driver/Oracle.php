<?php
declare(strict_types=1);

namespace App\Database\Driver;

use App\Database\OracleCompiler;
use App\Database\Schema\OracleSchemaDialect;
use App\Database\Statement\OracleStatement;
use Cake\Database\Driver;
use Cake\Database\Driver\SqlDialectTrait;
use Cake\Database\Driver\TupleComparisonTranslatorTrait;
use Cake\Database\Query;
use Cake\Database\QueryCompiler;
use Cake\Database\Schema\SchemaDialect;
use Cake\Database\Statement\PDOStatement;
use Cake\Database\StatementInterface;
use PDO;

class Oracle extends Driver
{
    use SqlDialectTrait;
    use TupleComparisonTranslatorTrait;

    protected $_baseConfig = [
        'persistent' => false,
        'host' => 'localhost',
        'port' => '1521',
        'database' => 'FREEPDB1',
        'username' => 'sterenfix',
        'password' => '',
        'encoding' => 'AL32UTF8',
        'flags' => [
            PDO::ATTR_CASE => PDO::CASE_LOWER,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ],
        'init' => [],
    ];

    protected $_startQuote = '"';
    protected $_endQuote = '"';
    protected ?OracleSchemaDialect $_schemaDialect = null;

    public function connect(): bool
    {
        if ($this->_connection) {
            return true;
        }

        $config = $this->_config;
        $host = $config['host'] ?? 'localhost';
        $port = $config['port'] ?? '1521';
        $database = $config['database'] ?? 'FREEPDB1';
        $encoding = $config['encoding'] ?? 'AL32UTF8';

        $dsn = "oci:dbname=//{$host}:{$port}/{$database};charset={$encoding}";

        $flags = ($config['flags'] ?? []) + [
            PDO::ATTR_CASE => PDO::CASE_LOWER,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ];

        $connection = new PDO($dsn, $config['username'] ?? '', $config['password'] ?? '', $flags);
        $this->setConnection($connection);

        // Formatos de fecha y timestamp de sesión
        $connection->exec("ALTER SESSION SET NLS_DATE_FORMAT = 'YYYY-MM-DD'");
        $connection->exec("ALTER SESSION SET NLS_TIMESTAMP_FORMAT = 'YYYY-MM-DD HH24:MI:SS'");

        return true;
    }

    public function enabled(): bool
    {
        return in_array('oci', PDO::getAvailableDrivers(), true);
    }

    public function newCompiler(): QueryCompiler
    {
        return new OracleCompiler();
    }

    public function prepare($query): StatementInterface
    {
        $this->connect();
        $isObject = $query instanceof Query;
        $sql = $isObject ? $query->sql() : (string)$query;

        $aliasMap = [];
        if (preg_match_all('/AS\s+([a-zA-Z0-9_]+)/i', $sql, $matches)) {
            foreach ($matches[1] as $alias) {
                $aliasMap[strtolower($alias)] = $alias;
            }
        }

        $statement = $this->_connection->prepare($sql);
        $pdoStatement = new PDOStatement($statement, $this);
        $result = new OracleStatement($pdoStatement, $this, $aliasMap);

        if ($isObject && $query->isBufferedResultsEnabled() === false) {
            $result->bufferResults(false);
        }

        return $result;
    }

    public function schemaDialect(): SchemaDialect
    {
        if ($this->_schemaDialect === null) {
            $this->_schemaDialect = new OracleSchemaDialect($this);
        }

        return $this->_schemaDialect;
    }

    public function quoteIdentifier(string $identifier): string
    {
        if ($identifier === '*') {
            return '*';
        }
        $identifier = trim($identifier);
        if (str_starts_with($identifier, '"') && str_ends_with($identifier, '"')) {
            return $identifier;
        }

        return '"' . strtoupper($identifier) . '"';
    }

    public function schema(): string
    {
        return strtoupper((string)($this->_config['username'] ?? 'STERENFIX'));
    }

    public function lastInsertId(?string $table = null, ?string $column = null): string|int
    {
        return (int)$this->getConnection()->lastInsertId();
    }

    public function disableForeignKeySQL(): string
    {
        return '';
    }

    public function enableForeignKeySQL(): string
    {
        return '';
    }

    public function supportsDynamicConstraints(): bool
    {
        return false;
    }
}
