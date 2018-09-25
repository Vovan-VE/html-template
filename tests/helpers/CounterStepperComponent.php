<?php
namespace VovanVE\HtmlTemplate\tests\helpers;

use VovanVE\HtmlTemplate\components\ComponentInterface;
use VovanVE\HtmlTemplate\components\ComponentSpawnerInterface;

class CounterStepperComponent implements ComponentSpawnerInterface
{
    private $stepsPassed = 0;

    public function getComponent(array $properties = []): ComponentInterface
    {
        return new CounterStepComponent([
            'step' => ++$this->stepsPassed,
        ]);
    }
}
