<?php
namespace app\models\patronState;

class EstadoReparado extends EstadoBase
{
    protected function getNombreClave(): string
    {
        return 'REPARADO';
    }

    protected function getAllowedTransitions(): array
    {
        return [
          EstadoBase::REPARADO,
          EstadoBase::EN_REPARACION,

        ];
    }


}
 ?>
