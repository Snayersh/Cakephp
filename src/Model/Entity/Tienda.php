<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Entidad Tienda
 *
 * @property int $id
 * @property string $nombre
 * @property string|null $direccion
 * @property string|null $telefono
 * @property \Cake\I18n\FrozenTime|null $creado_en
 * @property \App\Model\Entity\Empleado[] $empleados
 */
class Tienda extends Entity
{
    protected $_accessible = [
        'nombre' => true,
        'direccion' => true,
        'telefono' => true,
        'creado_en' => true,
        'empleados' => true,
    ];
}
