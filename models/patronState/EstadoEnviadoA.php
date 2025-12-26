<?php
namespace app\models\patronState;

class EstadoEnviadoA extends EstadoBase
{
    protected function getNombreClave(): string
    {
        return 'ENVIADO A';
    }

    protected function getAllowedTransitions(): array
    {
        return [
            EstadoBase::EN_REPARACION,
            EstadoBase::ENVIADO_A,
            EstadoBase::REPARADO,
            EstadoBase::IRREPARABLE,


        ];
    }

}
 ?>
