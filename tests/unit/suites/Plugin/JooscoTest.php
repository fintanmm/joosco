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
use JPlugin;

/**
 * Unit Test class for Joosco\Plugin\Joosco.
 */
class JooscoTest extends TestCase
{
    public function testConstructor()
    {
        $mockObject = new MockClass();
        // define('JAUTHENTICATE_STATUS_SUCCESS', 'success');
        $plugin = new Joosco($mockObject);

        $this->assertInstanceOf('JPlugin', $plugin);
    }

    public function testOnAuthenticate()
    {
        $this->markTestIncomplete();

        $credentials = ['username' => 'admin', 'password' => 'admin', 'mail' => 'admin@acme.com'];
        $response = (object) ['username' => 'admin', 'password' => 'admin',
                              'mail' => 'admin@acme.com',
                              'status' => STATUS_SUCCESS, ];

        $plugin = $this->plugin->onAuthenticate($credentials, [], $response);

        $this->assertTrue($plugin);
    }

    public function testBindToRepository()
    {
        $bind = $this->plugin->bindToRepository();

        $this->assertTrue($bind);
    }

    public function setUp()
    {
        parent::setUp();
        $mockObject = new MockClass();
        $this->plugin = new Joosco($mockObject);
    }
}

/**
 *
 */
class MockClass extends \stdClass
{
    public function attach()
    {
        # code...
    }
}
