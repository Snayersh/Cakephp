<?php
declare(strict_types=1);

namespace App\Database\Schema;

use Cake\Database\Schema\SchemaDialect;
use Cake\Database\Schema\TableSchema;

class OracleSchemaDialect extends SchemaDialect
{
    public function listTablesSql(array $config): array
    {
        return [
            "SELECT table_name FROM user_tables ORDER BY table_name",
            []
        ];
    }

    public function listTablesWithoutViewsSql(array $config): array
    {
        return $this->listTablesSql($config);
    }

    public function describeColumnSql(string $tableName, array $config): array
    {
        $sql = "SELECT column_name, data_type, data_length, data_precision, data_scale, nullable, data_default
                FROM user_tab_cols
                WHERE table_name = UPPER(?)
                ORDER BY column_id";
        return [$sql, [$tableName]];
    }

    public function describeIndexSql(string $tableName, array $config): array
    {
        $sql = "SELECT a.index_name, a.column_name, b.uniqueness,
                       (SELECT 1 FROM user_constraints c WHERE c.table_name = UPPER(?) AND c.constraint_type = 'P' AND c.index_name = a.index_name) as is_pk
                FROM user_ind_columns a
                JOIN user_indexes b ON a.index_name = b.index_name
                WHERE a.table_name = UPPER(?)
                ORDER BY a.column_position";
        return [$sql, [$tableName, $tableName]];
    }

    public function describeForeignKeySql(string $tableName, array $config): array
    {
        return ["SELECT 1 FROM dual WHERE 1=0", []];
    }

    public function convertColumnDescription(TableSchema $schema, array $row): void
    {
        $field = strtolower((string)($row['COLUMN_NAME'] ?? $row['column_name']));
        $type = strtoupper((string)($row['DATA_TYPE'] ?? $row['data_type']));
        $precision = $row['DATA_PRECISION'] ?? $row['data_precision'] ?? null;
        $scale = $row['DATA_SCALE'] ?? $row['data_scale'] ?? null;
        $length = $row['DATA_LENGTH'] ?? $row['data_length'] ?? null;
        $null = strtoupper((string)($row['NULLABLE'] ?? $row['nullable'])) === 'Y';
        $default = $row['DATA_DEFAULT'] ?? $row['data_default'] ?? null;

        $cakeType = 'string';
        if (str_contains($type, 'NUMBER')) {
            if ($scale > 0) {
                $cakeType = 'decimal';
            } else {
                $cakeType = 'integer';
            }
        } elseif (str_contains($type, 'DATE')) {
            $cakeType = 'date';
        } elseif (str_contains($type, 'TIMESTAMP')) {
            $cakeType = 'datetime';
        } elseif (str_contains($type, 'CLOB')) {
            $cakeType = 'text';
        }

        $schema->addColumn($field, [
            'type' => $cakeType,
            'length' => (int)($precision ?: $length),
            'precision' => (int)$scale,
            'null' => $null,
            'default' => $default ? trim((string)$default) : null,
        ]);
    }

    public function convertIndexDescription(TableSchema $schema, array $row): void
    {
        $isPk = !empty($row['IS_PK'] ?? $row['is_pk']);
        $col = strtolower((string)($row['COLUMN_NAME'] ?? $row['column_name']));
        $indexName = strtolower((string)($row['INDEX_NAME'] ?? $row['index_name']));
        $unique = strtoupper((string)($row['UNIQUENESS'] ?? $row['uniqueness'])) === 'UNIQUE';

        if ($isPk) {
            $schema->addConstraint('primary', [
                'type' => TableSchema::CONSTRAINT_PRIMARY,
                'columns' => [$col],
            ]);
        } elseif ($unique) {
            $schema->addConstraint($indexName, [
                'type' => TableSchema::CONSTRAINT_UNIQUE,
                'columns' => [$col],
            ]);
        } else {
            $schema->addIndex($indexName, [
                'type' => TableSchema::INDEX_INDEX,
                'columns' => [$col],
            ]);
        }
    }

    public function convertForeignKeyDescription(TableSchema $schema, array $row): void
    {
    }

    public function createTableSql(TableSchema $schema, array $columns, array $constraints, array $indexes): array
    {
        return [];
    }

    public function columnSql(TableSchema $schema, string $name): string
    {
        return '';
    }

    public function addConstraintSql(TableSchema $schema): array
    {
        return [];
    }

    public function dropConstraintSql(TableSchema $schema): array
    {
        return [];
    }

    public function constraintSql(TableSchema $schema, string $name): string
    {
        return '';
    }

    public function indexSql(TableSchema $schema, string $name): string
    {
        return '';
    }

    public function truncateTableSql(TableSchema $schema): array
    {
        return ["TRUNCATE TABLE " . $this->_driver->quoteIdentifier($schema->name())];
    }
}
