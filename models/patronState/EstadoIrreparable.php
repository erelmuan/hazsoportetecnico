<?php
namespace app\models\patronState;


class EstadoIrreparable extends EstadoBase
{
    protected function getNombreClave(): string
    {
        return 'IRREPARABLE';
    }

    protected function getAllowedTransitions(): array
    {
      return [
        EstadoBase::IRREPARABLE,
          EstadoBase::EN_REPARACION,
      ];
    }

    // public function canTransitionTo($toStateId, $user, $entity = null): bool
    // {
    //   return true;
    // }

}

 ?>
