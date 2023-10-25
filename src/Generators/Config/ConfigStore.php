<?php

namespace Devertix\LaravelApiGenerator\Generators\Config;

class ConfigStore
{
    /**
     * @var array
     */
    public $fields = [];

    /**
     * @var boolean
     */
    public $timestamps;

    /**
     * @var string
     */
    public $modelName;
}
