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

jimport('joomla.event.plugin');

/**
 * Joosco Authentication Plugin.
 *
 * @author Dhjiz Trevian <dhjiz@d-bog.com>
 *
 * @since 1.5
 */
class Joosco extends JPlugin
{
    /**
     * Constructor.
     *
     * For php4 compatability we must not use the __constructor as a constructor for plugins
     * because func_get_args ( void ) returns a copy of all passed arguments NOT references.
     * This causes problems with cross-referencing necessary for the observer design pattern.
     *
     * @param object $subject The object to observe
     *
     * @since 1.5
     */
    public function plgAuthenticationJoosco(&$subject)
    {
        parent::__construct($subject);
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
    public function onAuthenticate($credentials, $options, &$response)
    {
        // This file is needed to use the Repository class
        require_once dirname(__FILE__).'/alfresco-php-library/Repository.php';

        // Get the URL of the Alfresco repository as a parameter of the plugin
        $plugin = &JPluginHelper::getPlugin('authentication', 'joosco');
        $pluginParams = new JParameter($plugin->params);

        // Set the variables
        $repositoryUrl = $pluginParams->get('alf-url');
        $userName = $credentials['username'];
        $password = $credentials['password'];

        // Connect to the repository
        $repository = new Repository($repositoryUrl);
        $ticket = null;

        try {
            // Try to create a ticket
            $ticket = $repository->authenticate($userName, $password);

            // If the ticket fails, it means that the username and/or password are wrong
            $_SESSION['ticket'] = $ticket;
            $response->status = JAUTHENTICATE_STATUS_SUCCESS;

            // There's no way to get the Alfresco name or email address of the user
            // The only thing to do is wait for an update of the Alfresco PHP API
            $response->username = $credentials['username'];
            $response->email = $credentials['username'].'@alfresco.user';
            $response->fullname = $credentials['username'];
        } catch (Exception $e) {
            // Wrong username or password creates an exception
            $response->status = JAUTHENTICATE_STATUS_FAILURE;
            $response->error_message = 'Authentication failed';
        }
    }
}
