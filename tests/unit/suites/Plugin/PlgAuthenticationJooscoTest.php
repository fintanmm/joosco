<?php

/**
 * @author: Fintan MacMahon
 *
 * @copyright   Copyright (C) 2015 Fintan MacMahon
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
namespace JooscoUnitTests\Plugin;

use Joosco\UnitTests\Helpers\TestCase;
use Joosco\Plugin\Joosco;
use SplObjectStorage;
use GuzzleHttp;
use Dkd\PhpCmis\SessionParameter;
use Joomla\Registry\Registry;

/**
 * Unit Test class for Joosco\Plugin\Joosco.
 */
class PlgAuthenticationJoosco extends TestCase
{
    public function testConstructor()
    {
        $this->markTestSkipped();

        $mockObject = new SplObjectStorage();
        $plugin = new Joosco($mockObject);

        $this->assertInstanceOf('JPlugin', $plugin);

        $sessionFactory = $this->joomlaReflection->getValue($plugin, 'sessionFactory');
        $this->assertInstanceOf('\Dkd\PhpCmis\SessionFactory', $sessionFactory);
    }

    public function testOnUserAuthenticateReturnFalse()
    {
        $this->markTestIncomplete();

        $credentials = ['username' => 'admin', 'password' => 'admin', 'mail' => 'admin@acme.com'];
        $response = (object) ['status' => ''];

        $authentication = $this->plugin->onUserAuthenticate($credentials, [], $response);
        $this->assertFalse($authentication);
    }

    public function testOnUserAuthenticateReturnTrue()
    {
        $credentials = ['username' => 'admin', 'password' => 'admin', 'mail' => 'admin@acme.com'];
        $response = (object) ['status' => ''];

        $authentication = $this->plugin->onUserAuthenticate($credentials, [], $response);
        $this->assertTrue($authentication);
    }

    public function testCreateSession()
    {
        $session = $this->plugin->createSession();

        $this->assertInstanceOf('\Dkd\PhpCmis\Session', $session);
    }

    public function testCreateSessionThowsIllegalStateException()
    {
        $this->markTestIncomplete();

        $this->setExpectedException('\Dkd\PhpCmis\Exception\IllegalStateException', 'Repository ID is not set!');
        $this->plugin->params->set('SessionParameter', []);
        $this->plugin->createSession();
    }

    public function testGetSessionParamaters()
    {
        $clientDetails = ['defaults' => ['auth' => ['admin','admin']]];

        $httpInvoker = new GuzzleHttp\Client($clientDetails);

        $params = new Registry();
        $params->set('SessionParameter', [
            'dkd.phpcmis.binding.type' => 'browser',
            'dkd.phpcmis.binding.browser.url' => 'http://localhost:8888/alfresco/api/-default-/public/cmis/versions/1.1/browser',
            'dkd.phpcmis.binding.browser.succinct' => false,
            'dkd.phpcmis.binding.httpinvoker.object' => $httpInvoker,
        ]);

        $parameters = $this->plugin->getSessionParameters();

        $this->assertEquals($params->get('SessionParameter'), $parameters);
    }

    public function testDefaultGetRepository()
    {
        $this->plugin->getRepository();
        $expectedRepoId = '-default-';
        $repoId = $this->plugin->params->get('SessionParameter')['dkd.phpcmis.session.repository.id'];
        $this->assertEquals($expectedRepoId, $repoId);
    }

    public function setUp()
    {
        parent::setUp();
        $mockObject = new SplObjectStorage();
        $this->params = ['params' => "{'username': 'admin', 'password': 'admin', 'mail': 'admin@acme.com'}"];
        $this->plugin = new Joosco($mockObject, $this->params);
    }
}
