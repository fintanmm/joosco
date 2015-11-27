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
class DisplayTest extends TestCase
{
    public function testConstruct()
    {
        $app = $this->joomlaReflection->getValue($this->view, 'app');

        $this->assertEquals($this->app, $app);
    }

    /**
     * @expectedException        RuntimeException
     * @expectedExceptionMessage Layout Path Not Found
     */
    public function testRenderThrowsRuntimeException()
    {
        $path = $this->viewPathHelper();
        $this->view->setPaths($path);

        $this->view->setLayout('doesNotExist');

        $view = $this->view->render();
        $this->assertTrue($view);
    }

    public function testRender()
    {
        #FIXME: We should not depend on the file system.
        $path = $this->viewPathHelper();
        $this->view->setPaths($path);

        $this->view->setLayout('default');

        $rendered = $this->view->render();

        $output = '
<!-- Header -->
<table cellspacing="0" cellpadding="2" width="95%" align="center">
    <tr>
        <td width=100%>
        <table cellspacing="0" cellpadding="0" width="100%">
        <tr>
            <td style="padding-right:4px; vertical-align: middle;"><img src="components/com_joosco/alfresco-php-library/Common/Images/AlfrescoLogo32.png" alt="Alfresco" title="Alfresco" style="border: 0;"></td>
            <td><img src="components/com_joosco/alfresco-php-library/Common/Images/titlebar_begin.gif" width="10" height="30"></td>
            <td width="100%" style="background-image: url(components/com_joosco/alfresco-php-library/Common/Images/titlebar_bg.gif)">
                <strong style="color: white;">Joosco Extension</strong>
            </td>
            <td><img src="components/com_joosco/alfresco-php-library/Common/Images/titlebar_end.gif" width="8" height="30"></td>
        </tr>
        </table>
        </td>
    </tr>
</table>
<br />


<br />
<br />
';

        $this->expectOutputString($output, $rendered);
    }

    public function setUp()
    {
        parent::setUp();

        self::setLanguageHelper();

        $helper = self::setUpView('Joosco\Component\Site\Views\Display');
        $this->view = $helper->view;
    }

    /**
     * Sets up the view to be tested.
     *
     * @param string $view The full namespace to the view being tested.
     *
     * @return object An object containing the model and the view.
     */
    private function setUpView($view)
    {
        $paths = $this->viewPathHelper();
        $view = new $view($paths);

        return (object) ['view' => $view];
    }
}
