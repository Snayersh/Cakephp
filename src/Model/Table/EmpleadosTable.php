<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Tabla Empleados
 */
class EmpleadosTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('empleados');
        $this->setDisplayField('nombres');
        $this->setPrimaryKey('id');

        $this->belongsTo('Puestos', [
            'foreignKey' => 'puesto_id',
            'joinType' => 'INNER',
        ]);

        $this->belongsTo('Tiendas', [
            'foreignKey' => 'tienda_id',
            'joinType' => 'INNER',
        ]);

        $this->hasMany('Logros', [
            'foreignKey' => 'empleado_id',
            'dependent' => true,
            'cascadeCallbacks' => true,
        ]);

        $this->hasMany('LlamadasAtencion', [
            'foreignKey' => 'empleado_id',
            'dependent' => true,
            'cascadeCallbacks' => true,
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('nombres')
            ->maxLength('nombres', 100, 'Los nombres no pueden superar 100 caracteres.')
            ->requirePresence('nombres', 'create', 'El campo nombres es obligatorio.')
            ->notEmptyString('nombres', 'Por favor especifique los nombres del empleado.');

        $validator
            ->scalar('apellidos')
            ->maxLength('apellidos', 100, 'Los apellidos no pueden superar 100 caracteres.')
            ->requirePresence('apellidos', 'create', 'El campo apellidos es obligatorio.')
            ->notEmptyString('apellidos', 'Por favor especifique los apellidos del empleado.');

        $validator
            ->date('fecha_nacimiento', ['ymd', 'dmy'], 'Proporcione una fecha de nacimiento válida.')
            ->requirePresence('fecha_nacimiento', 'create', 'La fecha de nacimiento es obligatoria.')
            ->notEmptyDate('fecha_nacimiento', 'Debe seleccionar la fecha de nacimiento.')
            ->add('fecha_nacimiento', 'noFuturaMayorEdad', [
                'rule' => function ($value, $context) {
                    if (empty($value)) {
                        return true;
                    }
                    try {
                        $date = new \DateTime((string)$value);
                        $today = new \DateTime('today');
                        if ($date > $today) {
                            return false;
                        }
                        return $today->diff($date)->y >= 18;
                    } catch (\Throwable) {
                        return false;
                    }
                },
                'message' => 'La fecha de nacimiento no puede ser futura y el empleado debe tener al menos 18 años.'
            ]);

        $validator
            ->numeric('salario', 'El salario debe ser una cantidad numérica válida.')
            ->requirePresence('salario', 'create', 'El salario es obligatorio.')
            ->notEmptyString('salario', 'El salario es requerido.')
            ->greaterThan('salario', 0, 'El salario debe ser estrictamente mayor a 0.00.');

        $validator
            ->integer('puesto_id')
            ->requirePresence('puesto_id', 'create', 'Debe asignar un puesto de trabajo.')
            ->notEmptyString('puesto_id', 'Seleccione un puesto para el empleado.');

        $validator
            ->integer('tienda_id')
            ->requirePresence('tienda_id', 'create', 'Debe asignar una tienda o sucursal.')
            ->notEmptyString('tienda_id', 'Seleccione la tienda a la que pertenece el empleado.');

        $validator
            ->scalar('fotografia')
            ->maxLength('fotografia', 255)
            ->allowEmptyString('fotografia');

        $validator
            ->allowEmptyFile('fotografia_file')
            ->add('fotografia_file', 'fileType', [
                'rule' => ['extension', ['jpg', 'jpeg', 'png', 'webp']],
                'message' => 'Solo se permiten imágenes en formato JPG, PNG o WEBP.',
            ])
            ->add('fotografia_file', 'fileSize', [
                'rule' => ['fileSize', '<=', '5MB'],
                'message' => 'El tamaño máximo de la imagen es de 5 Megabytes.',
            ]);

        return $validator;
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['puesto_id'], 'Puestos', 'El puesto seleccionado no existe en el sistema.'));
        $rules->add($rules->existsIn(['tienda_id'], 'Tiendas', 'La tienda seleccionada no existe en el sistema.'));

        return $rules;
    }
}
