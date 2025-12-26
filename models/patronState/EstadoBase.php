<?php
namespace app\models\patronState;

abstract class EstadoBase implements State
{
  // Constantes para IDs de estados
     public const PENDIENTE_DE_REPARACION = 1;
     public const EN_REPARACION = 2; // Estado final para cuando el patólogo termina
     public const REPARADO = 3;
     public const IRREPARABLE = 4;
     public const ENVIADO_A = 5;


    protected $modelo;

    public function __construct($modelo)
    {
        $this->modelo = $modelo;
    }

    public function getName(): string
    {
        return $this->modelo->nombre;
    }

    abstract protected function getNombreClave(): string;

    abstract protected function getAllowedTransitions(): array;

    public function canTransitionTo($toStateId, $user, $entity = null): bool
    {
        return in_array($toStateId, $this->getAllowedTransitions(), true);
    }

    public function isValidForEntityType($entityClass): bool
    {
        // Por defecto todos los estados son válidos para todas las entidades
        return true;
    }

    public function onEnter($entity): void {}
    public function onExit($entity): void {}
}
