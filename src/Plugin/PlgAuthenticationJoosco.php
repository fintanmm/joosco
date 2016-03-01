<?php

/*
 * @version     $Id: alfresco.php $
 *
 * @copyright   (C) 2007 SARL D-Bog
 * @license     GNU/GPL, see LICENSE.php
 */

namespace Joosco\Plugin;

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

use JPlugin;
use AlfrescoSoap\Repository;
use JPluginHelper;
use JAuthentication;
use GuzzleHttp;
use Dkd\PhpCmis\SessionFactory;
use Dkd\PhpCmis\SessionParameter;
use Dkd\PhpCmis\Enum;

/**
 * Joosco Authentication Plugin.
 *
 * @author Dhjiz Trevian <dhjiz@d-bog.com>
 *
 * @since 1.5
 */
class PlgAuthenticationJoosco extends JPlugin
{
    private $sessionFactory;

    public function __construct(&$subject, $config = array())
    {
        parent::__construct($subject, $config);
        $this->sessionFactory = new SessionFactory();
    }

    /**
     * This method should handle any authentication and report back to the subject.
     *
     * @param array  $credentials Array holding the user credentials
     * @param array  $options     Array of extra options
     * @param object $response    Authentication response object
     *
     * @return bool
     *
     * @since 1.5
     */
    public function onUserAuthenticate($credentials, $options, &$response)
    {
        // Get the URL of the Alfresco repository as a parameter of the plugin
        // $plugin = JPluginHelper::getPlugin('authentication', 'joosco');
        // // Set the variables
        // $repositoryUrl = $pluginParams->get('alf-url');

        try {
            self::createSession();

            $response->status = JAuthentication::STATUS_SUCCESS;

            return true;
        } catch (\Dkd\PhpCmis\Exception\IllegalStateException $e) {
            // If no repository id is found, it creates an exception
            $response->status = JAuthentication::STATUS_FAILURE;
            $response->error_message = JText::_('JGLOBAL_AUTH_FAIL');
        }

        return false;
    }

    public function createSession()
    {
        self::getRepository();
        $sessionParameters = $this->params->get('SessionParameter');
        $session = $this->sessionFactory->createSession($sessionParameters);

        return $session;
    }

    public function getRepository($byRepoId = null)
    {
        $sessionParameters = self::getSessionParameters();
        $repositories = $this->sessionFactory->getRepositories($sessionParameters);
        // If no repository id is defined use the first repository
        if ($byRepoId === null) {
            $byRepoId = $repositories[0]->getId();
        }

        self::setSessionParameters($byRepoId);
    }

    public function getSessionParameters()
    {
        self::setSessionParameters();

        return $this->params->get('SessionParameter');
    }

    public function setSessionParameters($repoId = null)
    {
        $clientDetails = ['defaults' => ['auth' => [
                            'admin',
                            'admin', ],
                        ],
                    ];
        $httpInvoker = new GuzzleHttp\Client($clientDetails);
        $sessionParameters = [
            SessionParameter::BINDING_TYPE => Enum\BindingType::BROWSER,
            SessionParameter::BROWSER_URL => 'http://my-cmis-inmemory:8080/chemistry-opencmis-server-inmemory-1.0.0-SNAPSHOT/atom11',
            SessionParameter::BROWSER_SUCCINCT => false,
            SessionParameter::HTTP_INVOKER_OBJECT => $httpInvoker,
        ];
        if ($repoId !== null) {
            $sessionParameters[SessionParameter::REPOSITORY_ID] = $repoId;
        }
        $this->params->set('SessionParameter', $sessionParameters);
    }
}
