<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Entidad Logro
 *
 * @property int $id
 * @property int $empleado_id
 * @property string $descripcion
 * @property string $tipo
 * @property \Cake\I18n\FrozenDate $fecha_ocurrencia
 * @property \Cake\I18n\FrozenTime|null $creado_en
 * @property \App\Model\Entity\Empleado $empleado
 */
class Logro extends Entity
{
    protected array $_accessible = [
        'empleado_id' => true,
        'descripcion' => true,
        'tipo' => true,
        'fecha_ocurrencia' => true,
        'creado_en' => true,
        'empleado' => true,
    ];

    /**
     * Indica si el logro es positivo
     */
    public function isPositivo(): bool
    {
        return strtolower($this->tipo ?? '') === 'positivo';
    }
}
