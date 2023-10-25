<?php

namespace Devertix\LaravelApiGenerator\Generators;

class RequestBaseGenerator extends GeneratorAbstract
{

    protected function getStubFileName()
    {
        return 'RequestBase.php.txt';
    }

    protected function getDestinationFileName()
    {
        return $this->modelName . 'RequestBase.php';
    }

    protected function extendReplaceData()
    {
        $code = '';
        foreach ($this->fields as $fieldData) {
            $code .= $this->indentString("'data.attributes." . $fieldData['name'] . "' => 'required',", 3);
        }
        $this->stringsToReplace['%%code%%'] = rtrim($code);
    }
}
