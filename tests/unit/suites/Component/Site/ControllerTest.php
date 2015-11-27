<?php

/**
 * @author: Fintan MacMahon
 *
 * @copyright   Copyright (C) 2015 Fintan MacMahon
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
namespace JooscoUnitTests;

use Joosco\UnitTests\Helpers\TestCase;
use Joosco\Component\Site\Controller;

/**
 * Unit Test class for JooscoViewDefault.
 */
class ControllerTest extends TestCase
{
    public function testExecuteThrowsRuntimeException()
    {
        $this->setExpectedException('RuntimeException', 'Layout Path Not Found');

        $controller = new Controller();
        $controller->execute();
    }

    public function testExecute()
    {
        self::executeTestHelper('Display');

        $executeController = $this->controller->execute();

        $output = '';

        $this->expectOutputString($output, $executeController);
        $this->assertTrue($executeController);
    }

    public function executeTestHelper($setView = '')
    {
        $this->joomlaReflection->setValue($this->controller, 'paths', $this->viewPathHelper());
        $this->joomlaReflection->setValue($this->controller, 'viewClass', 'Joosco\Component\Site\Views\\'.ucfirst($setView));
        $this->joomlaReflection->setValue($this->controller, 'viewName', $setView);
    }

    public function testSetViewName()
    {
        $this->input->set('view', 'default');

        $this->joomlaReflection->invoke($this->controller, 'setViewName');
        $viewNameValue = $this->joomlaReflection->getValue($this->controller, 'viewName');
        $this->assertEquals($viewNameValue, 'default');
    }

    public function testRegisterPaths()
    {
        $this->joomlaReflection->setValue($this->controller, 'viewName', 'default');
        $this->joomlaReflection->invoke($this->controller, 'registerPaths');

        $pathValue = $this->joomlaReflection->getValue($this->controller, 'paths');
        $testPath = JPATH_COMPONENT.'/Views/default/tmpl';
        $this->assertEquals($pathValue->current(), $testPath);
    }

    public function testSetViewFormat()
    {
        $this->input->set('format', 'html');

        $this->joomlaReflection->invoke($this->controller, 'setViewFormat');
        $viewFormatValue = $this->joomlaReflection->getValue($this->controller, 'viewFormat');

        $this->assertEquals($viewFormatValue, 'html');
    }

    public function testCreateClassView()
    {
        $this->joomlaReflection->setValue($this->controller, 'viewName', 'form');
        $this->joomlaReflection->setValue($this->controller, 'viewFormat', 'html');

        $this->joomlaReflection->invoke($this->controller, 'createClassView');
        $view = $this->joomlaReflection->getValue($this->controller, 'viewClass');
        $this->assertEquals($view, 'Joosco\Component\Site\Views\Form');
    }

    public function testCreateLayout()
    {
        self::layoutHelper('Display');
    }

    public function testCreateLayoutHtml()
    {
        self::layoutHelper('Display');
    }

    private function layoutHelper($view = '')
    {
        $this->joomlaReflection->setValue($this->controller, 'paths', $this->viewPathHelper());
        $this->joomlaReflection->setValue($this->controller, 'viewClass', 'Joosco\Component\Site\Views\\'.$view);

        $layout = $this->joomlaReflection->invoke($this->controller, 'createLayout');

        $this->assertInstanceOf('Joosco\Component\Site\Views\\'.$view, $layout);
    }

    public function setUp()
    {
        parent::setUp();

        $this->controller = new Controller();
    }
}
