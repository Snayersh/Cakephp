<?php
declare(strict_types=1);

namespace App\Database;

use Cake\Database\Query;
use Cake\Database\QueryCompiler;
use Cake\Database\ValueBinder;

/**
 * Compilador de Consultas SQL nativo para Oracle Database (12c, 19c, 21c, 23c Free)
 */
class OracleCompiler extends QueryCompiler
{
    /**
     * Plantillas SQL base omitiendo limit y offset para gestionarlos con sintaxis ANSI/Oracle
     *
     * @var array<string, string>
     */
    protected $_templates = [
        'delete' => 'DELETE',
        'where' => ' WHERE %s',
        'group' => ' GROUP BY %s ',
        'having' => ' HAVING %s ',
        'order' => ' %s',
        'epilog' => ' %s',
    ];

    /**
     * En Oracle estándar, la cláusula OFFSET precede a FETCH FIRST / FETCH NEXT
     *
     * @var array<string>
     */
    protected $_selectParts = [
        'with', 'select', 'from', 'join', 'where', 'group', 'having', 'window', 'order',
        'offset', 'limit', 'union', 'epilog',
    ];

    /**
     * Genera la cláusula OFFSET para Oracle
     *
     * @param int|null $offset
     * @param \Cake\Database\Query $query
     * @param \Cake\Database\ValueBinder $binder
     * @return string
     */
    protected function _buildOffsetPart($offset, Query $query, ValueBinder $binder): string
    {
        if ($offset === null) {
            return '';
        }

        return sprintf(' OFFSET %d ROWS', (int)$offset);
    }

    /**
     * Genera la cláusula FETCH FIRST / NEXT para paginación en Oracle
     *
     * @param int|null $limit
     * @param \Cake\Database\Query $query
     * @param \Cake\Database\ValueBinder $binder
     * @return string
     */
    protected function _buildLimitPart($limit, Query $query, ValueBinder $binder): string
    {
        if ($limit === null) {
            return '';
        }

        $offset = $query->clause('offset');
        if ($offset !== null) {
            return sprintf(' FETCH NEXT %d ROWS ONLY', (int)$limit);
        }

        return sprintf(' FETCH FIRST %d ROWS ONLY', (int)$limit);
    }
}
