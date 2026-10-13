<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Tabla Logros
 */
class LogrosTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('logros');
        $this->setDisplayField('descripcion');
        $this->setPrimaryKey('id');

        $this->belongsTo('Empleados', [
            'foreignKey' => 'empleado_id',
            'joinType' => 'INNER',
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->integer('empleado_id')
            ->requirePresence('empleado_id', 'create', 'Debe seleccionar un empleado.')
            ->notEmptyString('empleado_id', 'El empleado es obligatorio.');

        $validator
            ->scalar('descripcion')
            ->maxLength('descripcion', 500, 'La descripción no puede exceder los 500 caracteres.')
            ->requirePresence('descripcion', 'create', 'La descripción del logro es obligatoria.')
            ->notEmptyString('descripcion', 'Por favor describa el logro o reconocimiento.');

        $validator
            ->scalar('tipo')
            ->requirePresence('tipo', 'create', 'El tipo de logro es obligatorio.')
            ->inList('tipo', ['positivo', 'negativo'], 'El tipo debe ser "positivo" o "negativo".');

        $validator
            ->date('fecha_ocurrencia', ['ymd', 'dmy'], 'Seleccione una fecha válida.')
            ->requirePresence('fecha_ocurrencia', 'create', 'La fecha de ocurrencia es obligatoria.')
            ->notEmptyDate('fecha_ocurrencia', 'Debe especificar la fecha del acontecimiento.');

        return $validator;
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['empleado_id'], 'Empleados', 'El empleado seleccionado no es válido.'));

        return $rules;
    }
}
