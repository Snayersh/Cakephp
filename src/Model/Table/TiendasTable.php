<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Tabla Tiendas
 */
class TiendasTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('tiendas');
        $this->setDisplayField('nombre');
        $this->setPrimaryKey('id');

        $this->hasMany('Empleados', [
            'foreignKey' => 'tienda_id',
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
            ->maxLength('nombre', 100, 'El nombre no puede exceder 100 caracteres.')
            ->requirePresence('nombre', 'create', 'El nombre de la tienda es requerido.')
            ->notEmptyString('nombre', 'Por favor ingrese el nombre de la tienda.');

        $validator
            ->scalar('direccion')
            ->maxLength('direccion', 255, 'La dirección no puede exceder 255 caracteres.')
            ->allowEmptyString('direccion');

        $validator
            ->scalar('telefono')
            ->maxLength('telefono', 20, 'El teléfono no puede exceder 20 caracteres.')
            ->allowEmptyString('telefono');

        return $validator;
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->isUnique(['nombre'], 'Ya existe una tienda registrada con este nombre.'));

        return $rules;
    }
}
