<?php

namespace SigmaPHP\Core\Interfaces\Console;

/**
 * Console Manager Interface
 */
interface ConsoleManagerInterface
{
    /**
     * Execute console commands.
     * 
     * @param string $command
     * @return void
     */
    public function execute($command);
}