<?php

namespace SigmaPHP\Core\Router;

use SigmaPHP\Router\Interfaces\RunnerInterface;
use SigmaPHP\Core\Exceptions\InvalidActionException;
use SigmaPHP\Router\Exceptions\ActionNotFoundException;
use SigmaPHP\Router\Exceptions\ControllerNotFoundException;

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
        $return = null;

        // ensure that the session has started before any execution
        session_start();

        if (!isset($route['controller']) || empty($route['controller'])) {
            if (!function_exists($route['action'])) {
                throw new ActionNotFoundException("
                    The action {$route['action']} is not found
                ");
            }

            $return = call_user_func($route['action'],...$route['parameters']);
        } else {
            if (!class_exists($route['controller'])) {
                throw new ControllerNotFoundException("
                    The controller {$route['controller']} is not found
                ");
            }

            if (!method_exists($route['controller'], $route['action'])) {
                throw new ActionNotFoundException("
                    The action {$route['action']} is not found in 
                    {$route['controller']} controller
                ");
            }

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
                if (!isset($route['parameters'][$argumentsIndex]) &&
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
                        isset($route['parameters'][$argumentsIndex]) ?
                            ($route['parameters'][$argumentsIndex]) :
                            ($parameter->isDefaultValueAvailable() ?
                                $parameter->getDefaultValue() : null);
                    
                    $argumentsIndex += 1;
                }
            }

            // if the parameter is a class , the container will take care 
            // otherwise we prepare all the primitive parameters in the
            // $arguments and then pass it
            $return = container()->call(
                $route['controller'],
                $route['action'],
                $arguments
            );

            // here we check if the action returned object of type Response !
            // if not we throw the following exception
            //  
            // Please note : currently $return might looks meaningless , but 
            // in future when the architecture will be revamped , this variable
            // will play key role !!
            if (!($return instanceof \SigmaPHP\Core\Http\Response)) {
                throw new InvalidActionException(
                    "The action '{$route['action']}'" . 
                    "doesn't return valid Response !"
                );
            }
        }
    }
}