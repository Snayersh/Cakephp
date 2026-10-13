<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Tabla Puestos
 */
class PuestosTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('puestos');
        $this->setDisplayField('nombre');
        $this->setPrimaryKey('id');

        $this->hasMany('Empleados', [
            'foreignKey' => 'puesto_id',
            'dependent' => false,
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('nombre')
            ->maxLength('nombre', 100, 'El nombre del puesto no puede exceder 100 caracteres.')
            ->requirePresence('nombre', 'create', 'El nombre del puesto es requerido.')
            ->notEmptyString('nombre', 'Por favor ingrese el nombre del puesto.');

        $validator
            ->scalar('descripcion')
            ->maxLength('descripcion', 255, 'La descripción no puede exceder 255 caracteres.')
            ->allowEmptyString('descripcion');

        return $validator;
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->isUnique(['nombre'], 'Ya existe un puesto registrado con este nombre.'));

        return $rules;
    }
}
