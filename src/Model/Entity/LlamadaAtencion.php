<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Entidad Llamada de Atención
 *
 * @property int $id
 * @property int $empleado_id
 * @property string $descripcion
 * @property \Cake\I18n\FrozenDate $fecha_ocurrencia
 * @property \Cake\I18n\FrozenTime|null $creado_en
 * @property \App\Model\Entity\Empleado $empleado
 */
class LlamadaAtencion extends Entity
{
    protected $_accessible = [
        'empleado_id' => true,
        'descripcion' => true,
        'fecha_ocurrencia' => true,
        'creado_en' => true,
        'empleado' => true,
    ];
}
