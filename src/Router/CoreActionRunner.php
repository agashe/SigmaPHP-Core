<?php

namespace SigmaPHP\Core\Router;

use SigmaPHP\Router\Interfaces\RunnerInterface;

/**
 * SigmaPHP-Core Action Runner
 */
class CoreActionRunner implements RunnerInterface
{
    /**
     * Execute the route's action.
     * 
     * @param array $route
     * @return void
     */
    public function execute($route)
    {
        if (!isset($route['controller']) || empty($route['controller'])) {
            call_user_func($route['action'],...$route['parameters']);
        } else {
            // get match arguments with parameters value
            $reflection = new \ReflectionMethod(
                $route['controller'],
                $route['action']
            );

            $arguments = [];
            foreach ($reflection->getParameters() as $index => $parameter) {
                // check if the parameter is class
                $parameterIsClass = ($parameter->getType() !== null) &&
                    !$parameter->getType()->isBuiltin();

                // if the parameter doesn't exist in the route's parameters
                // and it's not optional in the action (no default value !)
                // we thraw exception !
                if (!isset($route['parameters'][$index]) &&
                    !$parameter->isOptional() && !$parameterIsClass
                ) {
                    throw new \InvalidArgumentException(
                        "Missing parameter [{$parameter->getName()}] " .
                        "for route [{$route['path']}]"
                    );
                }

                if ($parameterIsClass) {
                    $arguments[] = container()->get(
                        $parameter->getType()->getName()
                    );
                } else {
                    // for primitive parameters , first we check if they 
                    // they have value from route's parameters if yes
                    // we put that value , otherwise we check for the default
                    // value from the Reflection class , if not we 
                    // put null !
                    $arguments[] = isset($route['parameters'][$index]) ?
                        ($route['parameters'][$index]) :
                        ($parameter->isDefaultValueAvailable() ?
                            $parameter->getDefaultValue() : null);
                }
            }

            $controller = container()->get($route['controller']);
            $controller->{$route['action']}(...$arguments);
        }
    }
}