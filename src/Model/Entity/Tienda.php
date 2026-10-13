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

    /**
     * Normaliza el número telefónico al formato estándar de Guatemala (+502 XXXX XXXX)
     */
    protected function _setTelefono(?string $telefono): ?string
    {
        if ($telefono === null || trim($telefono) === '') {
            return null;
        }

        $digits = preg_replace('/\D/', '', $telefono);
        if (str_starts_with($digits, '502') && strlen($digits) === 11) {
            $digits = substr($digits, 3);
        }

        if (strlen($digits) === 8) {
            return '+502 ' . substr($digits, 0, 4) . ' ' . substr($digits, 4);
        }

        return trim($telefono);
    }
}
