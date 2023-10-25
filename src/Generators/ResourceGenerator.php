<?php

namespace Devertix\LaravelApiGenerator\Generators;

class ResourceGenerator extends GeneratorAbstract
{

    protected function getStubFileName()
    {
        return 'Resource.php.txt';
    }

    protected function getDestinationFileName()
    {
        return $this->modelName . 'Resource.php';
    }

    protected function extendReplaceData()
    {
        $code = '';
        if ($this->timestamps) {
            $code .= $this->indentString("'created_at' => \$this->created_at->toDateTimeString(),", 3);
            $code .= $this->indentString("'updated_at' => \$this->updated_at->toDateTimeString(),", 3);
        }
        foreach ($this->fields as $fieldData) {
            switch ($fieldData['type']) {
                case 'datetime':
                    $code .= $this->indentString("'" . $fieldData['name'] . "' => \$this" . '->' . $fieldData['name'] . '->toDateTimeString(),', 3);
                    break;
                default:
                    $code .= $this->indentString("'" . $fieldData['name'] . "' => \$this" . '->' . $fieldData['name'] . ',', 3);
                    break;
            }
        }
        $this->stringsToReplace['%%code%%'] = rtrim($code);
    }
}
