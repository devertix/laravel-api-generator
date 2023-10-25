<?php

namespace Devertix\LaravelApiGenerator\Generators;

class FactoryGenerator extends GeneratorAbstract
{

    protected function getStubFileName()
    {
        return 'factory.php.txt';
    }

    protected function getDestinationFileName()
    {
        return $this->modelName . 'Factory.php';
    }

    protected function extendReplaceData()
    {
        $code = '';
        foreach ($this->fields as $fieldData) {
            switch ($fieldData['type']) {
                case 'string':
                    $code .= $this->indentString("'" . $fieldData['name'] . "' => \$this->faker->words(3, true),", 2);
                    break;
                case 'integer':
                    $code .= $this->indentString("'" . $fieldData['name'] . "' => \$this->faker->numberBetween(0, 10000),", 2);
                    break;
                case 'text':
                    $code .= $this->indentString("'" . $fieldData['name'] . "' => \$this->faker->realText(),", 2);
                    break;
                case 'boolean':
                    $code .= $this->indentString("'" . $fieldData['name'] . "' => \$this->faker->randomElement([true, false]),", 2);
                    break;
                case 'datetime':
                    $code .= $this->indentString("'" . $fieldData['name'] . "' => Carbon::now(),", 2);
                    break;
                default:
                    throw $this->invalidFieldTypeException($fieldData['type']);
            }
        }
        $this->stringsToReplace['%%code%%'] = rtrim($code);
    }
}
