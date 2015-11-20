<?php

/**
 * @author: Fintan MacMahon
 *
 * @copyright   Copyright (C) 2015 Fintan MacMahon
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
namespace JooscoUnitTests\Views;

use Joosco\UnitTests\Helpers\TestCase;

/**
 * Unit Test class for JooscoViewDefault.
 */
class JooscoTest extends TestCase
{
    public function testConstruct()
    {
        $app = $this->joomlaReflection->getValue($this->view, 'app');

        $this->assertEquals($this->app, $app);
    }
}
