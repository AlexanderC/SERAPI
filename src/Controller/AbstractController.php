<?php
/**
 * Created by PhpStorm.
 * User: AlexanderC <alexanderc@pycoding.biz>
 * Date: 4/2/15
 * Time: 11:42
 */

namespace SERAPI\Controller;

use Doctrine\Common\Inflector\Inflector;
use SERAPI\Routing\Parameter;
use SERAPI\Helper\ApplicationAwareTrait;

/**
 * Class AbstractController
 */
abstract class AbstractController
{
    use ApplicationAwareTrait;

    const ACTION_REGEXP = '/^(?P<httpMethod>get|post|put|delete|patch)(?P<action>[A-Z].*)Action$/u';

    /**
     * @return \Silex\Controller[]|\Generator
     */
    final public function register()
    {
        $reflection = new \ReflectionClass($this);

        $publicMethods = $reflection->getMethods(\ReflectionMethod::IS_PUBLIC);

        foreach ($publicMethods as $publicMethod) {
            if ($this->matchAction($publicMethod, $actionName, $httpMethod)) {
                $route = $this->getRoute($actionName);
                $parameters = $this->getRouteParameters($publicMethod);
                $routePattern = $this->getRoutePattern($route, $parameters);

                /** @var \Silex\Controller $routeObject */
                $routeObject = $this->application->{$httpMethod}($routePattern, [$this, $publicMethod->getName()]);

                foreach ($parameters as $parameter) {
                    if (!$parameter->isRequired()) {
                        $routeObject->value($parameter->getName(), $parameter->getDefaultValue());
                    }
                }
            }
        }
    }

    /**
     * @return string
     *
     * @todo overwrite in order to change route prefix
     */
    protected function getRoutesPrefix()
    {
        return '';
    }

    /**
     * @param string $route
     * @param \SERAPI\Routing\Parameter[] $parameters
     * @return string
     */
    private function getRoutePattern($route, array $parameters)
    {
        if (empty($parameters)) {
            return $route;
        }

        $routeParametersParts = [];

        foreach ($parameters as $parameter) {
            $routeParametersParts[] = sprintf("{%s}", $parameter->getName());
        }

        return sprintf("%s/%s", $route, implode('/', $routeParametersParts));
    }

    /**
     * @param \ReflectionMethod $method
     * @return \SERAPI\Routing\Parameter[]
     */
    private function getRouteParameters(\ReflectionMethod $method)
    {
        /** @var Parameter[] $routeParameters */
        $routeParameters = [];

        foreach ($method->getParameters() as $parameter) {
            $routeParameters[] = new Parameter(
                $parameter->getName(),
                !$parameter->isOptional(),
                $parameter->isOptional() ? $parameter->getDefaultValue() : null
            );
        }

        return $routeParameters;
    }

    /**
     * @param string $actionName
     * @return string
     */
    private function getRoute($actionName)
    {
        $rawPrefix = $this->getRoutesPrefix();
        $prefix = empty($rawPrefix) ? '/' : sprintf("/%s/", trim($rawPrefix, '/'));

        $actionParts = array_map(function($actionPart) {
            return Inflector::pluralize($actionPart);
        }, explode('_', $actionName));

        $route = implode('/', $actionParts);

        return $prefix . $route;
    }

    /**
     * @param \ReflectionMethod $method
     * @param string $actionName
     * @param string $httpMethod
     * @return bool
     */
    private function matchAction(\ReflectionMethod $method, &$actionName, &$httpMethod)
    {
        if (preg_match(self::ACTION_REGEXP, $method->getName(), $matches)) {
            $httpMethod = $matches['httpMethod'];
            $actionName = Inflector::tableize($matches['action']);

            return true;
        }

        return false;
    }
}