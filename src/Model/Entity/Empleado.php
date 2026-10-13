<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\I18n\FrozenDate;
use Cake\ORM\Entity;
use DateTime;

/**
 * Entidad Empleado
 *
 * @property int $id
 * @property string $nombres
 * @property string $apellidos
 * @property \Cake\I18n\FrozenDate $fecha_nacimiento
 * @property string|null $fotografia
 * @property float $salario
 * @property int $puesto_id
 * @property int $tienda_id
 * @property \Cake\I18n\FrozenTime|null $creado_en
 * @property \App\Model\Entity\Puesto $puesto
 * @property \App\Model\Entity\Tienda $tienda
 * @property \App\Model\Entity\Logro[] $logros
 * @property \App\Model\Entity\LlamadaAtencion[] $llamadas_atencion
 * @property string $nombre_completo
 * @property int|null $edad
 * @property string $salario_formateado
 * @property string $fotografia_url
 */
class Empleado extends Entity
{
    protected $_accessible = [
        'nombres' => true,
        'apellidos' => true,
        'fecha_nacimiento' => true,
        'fotografia' => true,
        'salario' => true,
        'puesto_id' => true,
        'tienda_id' => true,
        'creado_en' => true,
        'puesto' => true,
        'tienda' => true,
        'logros' => true,
        'llamadas_atencion' => true,
    ];

    protected $_virtual = [
        'nombre_completo',
        'edad',
        'salario_formateado',
        'fotografia_url',
    ];

    /**
     * Nombre completo del empleado
     */
    protected function _getNombreCompleto(): string
    {
        return trim(($this->nombres ?? '') . ' ' . ($this->apellidos ?? ''));
    }

    /**
     * Edad calculada en años cumplidos
     */
    protected function _getEdad(): ?int
    {
        if (empty($this->fecha_nacimiento)) {
            return null;
        }

        $formatted = $this->fecha_nacimiento instanceof \DateTimeInterface
            ? $this->fecha_nacimiento->format('Y-m-d')
            : (string)$this->fecha_nacimiento;

        $birthDate = new DateTime($formatted);
        $now = new DateTime();
        return $now->diff($birthDate)->y;
    }

    /**
     * Salario formateado en formato de moneda
     */
    protected function _getSalarioFormateado(): string
    {
        return '$' . number_format((float)($this->salario ?? 0), 2, '.', ',');
    }

    /**
     * URL o ruta de la fotografía, con avatar por defecto en caso de no existir
     */
    protected function _getFotografiaUrl(): string
    {
        if (!empty($this->fotografia)) {
            if (str_starts_with($this->fotografia, 'http://') || str_starts_with($this->fotografia, 'https://')) {
                return $this->fotografia;
            }
            $localPath = WWW_ROOT . 'img' . DS . 'empleados' . DS . $this->fotografia;
            if (file_exists($localPath)) {
                return '/img/empleados/' . rawurlencode($this->fotografia);
            }
        }
        return '/img/empleados/avatar-default.svg';
    }
}
