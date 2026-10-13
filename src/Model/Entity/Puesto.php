<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Entidad Puesto
 *
 * @property int $id
 * @property string $nombre
 * @property string|null $descripcion
 * @property \Cake\I18n\FrozenTime|null $creado_en
 * @property \App\Model\Entity\Empleado[] $empleados
 */
class Puesto extends Entity
{
    protected array $_accessible = [
        'nombre' => true,
        'descripcion' => true,
        'creado_en' => true,
        'empleados' => true,
    ];
}
