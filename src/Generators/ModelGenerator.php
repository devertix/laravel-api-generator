<?php

namespace Devertix\LaravelApiGenerator\Generators;

class ModelGenerator extends GeneratorAbstract
{
    protected function getStubFileName()
    {
        return 'Model.php.txt';
    }

    protected function getDestinationFileName()
    {
        return $this->modelName . '.php';
    }

    protected function extendReplaceData()
    {
        $code = '';
        if (!$this->timestamps) {
            $code .= $this->indentString('public $timestamps = false;', 1);
        }
        $dates = '';
        $code .= $this->indentString('protected $fillable = [', 1);
        foreach ($this->fields as $fieldData) {
            $code .= $this->indentString("'" . $fieldData['name'] . "',", 2);
            if ($fieldData['type'] == 'datetime') {
                $dates .= $this->indentString("'" . $fieldData['name'] . "',", 2);
            }
        }
        $code .= $this->indentString('];', 1);
        if ($dates) {
            $code .= $this->indentString('protected $dates = [', 1);
            if ($this->timestamps) {
                $code .= $this->indentString("'created_at',", 2);
                $code .= $this->indentString("'updated_at',", 2);
            }
            $code .= $dates;
            $code .= $this->indentString('];', 1);
        }
        $this->stringsToReplace['%%code%%'] = rtrim($code);
    }
}
