<?php
namespace app\models\patronState;

class EstadoPendienteDeReparacion extends EstadoBase
{
    protected function getNombreClave(): string
    {
        return 'PENDIENTE DE REPARACIÓN';
    }

    protected function getAllowedTransitions(): array
    {
        return [
          EstadoBase::PENDIENTE_DE_REPARACION,
          EstadoBase::EN_REPARACION,
          EstadoBase::REPARADO,
          EstadoBase::IRREPARABLE,


        ];
    }
    // public function isValidForEntityType($entityClass): bool
    // {
    //     // Por defecto todos los estados son válidos para todas las entidades
    //     // if ($entityClass === \app\models\Biopsia::class || $entityClass === \app\models\Pap::class) {
    //     //     return false;
    //     // }else {
    //       return true;
    //     // }
    // }

}
 ?>
