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

            // we will introduce the argumentsIndex as an index
            // so we can decrement if the parameter will be injected
            // otherwise we will fetch directly from the $route['parameters']
            // in order , for example :
            //
            // action(Request $request, $name)
            //
            // in the is case we have 2 arguments , but actually only one of
            // them is exists , which is $name , so the array should only handle
            // the primitive

            $arguments = [];
            $argumentsIndex = 0;

            foreach ($reflection->getParameters() as $index => $parameter) {
                // check if the parameter is class
                $parameterIsClass = ($parameter->getType() !== null) &&
                    !$parameter->getType()->isBuiltin();

                // if the parameter doesn't exist in the route's parameters
                // and it's not optional in the action (no default value !)
                // we throw exception !
                if (!isset($route['parameters'][$index]) &&
                    !$parameter->isOptional() && !$parameterIsClass
                ) {
                    throw new \InvalidArgumentException(
                        "Missing parameter [{$parameter->getName()}] " .
                        "for route [{$route['path']}]"
                    );
                }

                if (!$parameterIsClass) {
                    // for primitive parameters , first we check if they 
                    // they have value from route's parameters if yes
                    // we put that value , otherwise we check for the default
                    // value from the Reflection class , if not we 
                    // put null !
                    $arguments[$parameter->getName()] = 
                        isset($route['parameters'][$index]) ?
                            ($route['parameters'][$index]) :
                            ($parameter->isDefaultValueAvailable() ?
                                $parameter->getDefaultValue() : null);
                    
                    $argumentsIndex += 1;
                }
            }

            // if the parameter is a class , the container will take care 
            // otherwise we prepare all the primitive parameters in the
            // $arguments and then pass it
            container()->call(
                $route['controller'],
                $route['action'],
                $arguments
            );
        }
    }
}