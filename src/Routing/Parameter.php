<?php
/**
 * Created by PhpStorm.
 * User: AlexanderC <alexanderc@pycoding.biz>
 * Date: 4/2/15
 * Time: 12:10
 */

namespace SERAPI\Routing;


/**
 * Class Parameter
 * @package ERAPI\Routing
 */
class Parameter 
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var bool
     */
    protected $isRequired;

    /**
     * @var mixed
     */
    protected $defaultValue;

    /**
     * @param string $name
     * @param bool $isRequired
     * @param mixed $defaultValue
     */
    public function __construct($name, $isRequired, $defaultValue)
    {
        $this->name = (string)$name;
        $this->isRequired = (bool)$isRequired;
        $this->defaultValue = $defaultValue;
    }

    /**
     * @return mixed
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return boolean
     */
    public function isRequired()
    {
        return $this->isRequired;
    }
}