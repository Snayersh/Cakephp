<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Tabla Llamadas de Atención
 */
class LlamadasAtencionTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('llamadas_atencion');
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
            ->maxLength('descripcion', 500, 'La descripción de la falta no puede superar 500 caracteres.')
            ->requirePresence('descripcion', 'create', 'La descripción es obligatoria.')
            ->notEmptyString('descripcion', 'Por favor describa la causa de la llamada de atención.');

        $validator
            ->date('fecha_ocurrencia', ['ymd', 'dmy'], 'Seleccione una fecha válida.')
            ->requirePresence('fecha_ocurrencia', 'create', 'La fecha de la falta es requerida.')
            ->notEmptyDate('fecha_ocurrencia', 'Debe especificar la fecha de ocurrencia.')
            ->add('fecha_ocurrencia', 'noFutura', [
                'rule' => function ($value, $context) {
                    if (empty($value)) return true;
                    try {
                        $date = new \DateTime((string)$value);
                        $today = new \DateTime('today');
                        return $date <= $today;
                    } catch (\Throwable) {
                        return false;
                    }
                },
                'message' => 'La fecha de la incidencia no puede ser una fecha futura.'
            ]);

        return $validator;
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['empleado_id'], 'Empleados', 'El empleado seleccionado no es válido.'));

        return $rules;
    }
}
