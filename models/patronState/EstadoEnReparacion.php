<?php
namespace app\models\patronState;

class EstadoEnReparacion extends EstadoBase
{
    protected function getNombreClave(): string
    {
        return 'EN REPARACIÓN';
    }

    protected function getAllowedTransitions(): array
    {
        return [
            EstadoBase::EN_REPARACION,
            EstadoBase::REPARADO,
            EstadoBase::IRREPARABLE,


        ];
    }
    // public function isValidForEntityType($entityClass): bool
    // {
    //     // Por defecto todos los estados son válidos para todas las entidades
    //     return true;
    //
    // }

}
 ?>
