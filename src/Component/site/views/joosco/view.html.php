<?php
/**
* @package		D-Bog
* @subpackage 	Joosco
* @license		GNU/GPL, see LICENSE.php
*/

jimport( 'joomla.application.component.view');

require_once (JPATH_COMPONENT.DS.'alfresco-php-library'.DS.'Alfresco'.DS.'Service'.DS.'Repository.php');
require_once (JPATH_COMPONENT.DS.'alfresco-php-library'.DS.'Alfresco'.DS.'Service'.DS.'Session.php');
require_once (JPATH_COMPONENT.DS.'alfresco-php-library'.DS.'Alfresco'.DS.'Service'.DS.'SpacesStore.php');
require_once (JPATH_COMPONENT.DS.'alfresco-php-library'.DS.'Alfresco'.DS.'Service'.DS.'Node.php');

/**
 * HTML View class for the Joosco Component
 *
 * @package		D-Bog
 * @subpackage 	Joosco
 */
class JooscoViewJoosco extends JView
{
	function display($tpl = null)
	{
		// Get the url of the Alfresco repository from the Joosco plugin
		// That's why the Joosco plugin needs to be installed and configured before the component
		$plugin =& JPluginHelper::getPlugin('authentication', 'joosco');
		$pluginParams = new JParameter( $plugin->params );

		// Here we connect to the Repository
		$repositoryUrl = $pluginParams->get('alf-url');
		$repository = new Repository($repositoryUrl);

		// The ticket is created by the plugin when a user connects
		$ticket = $_SESSION["ticket"];
		$session = $repository->createSession($ticket);
		$store = new SpacesStore($session);
		$currentNode = null;

		$uuid = null;
		$uuid =& JRequest::getVar( 'uuid' );
		
		if (!isset($uuid))
		{
			$currentNode = $store->companyHome;
			$path = 'Company Home';
		}
		else
		{
			$currentNode = $session->getNode($store, JRequest::getVar( 'uuid' ));
			$path = JRequest::getVar( 'path' ).'|'.JRequest::getVar( 'uuid' ).'|'.JRequest::getVar( 'name' );
		}

		// Pass the values to the rest of the template
		$this->assignRef('path', $path);
		$this->assignRef('session', $session);
		$this->assignRef('store', $store);
		$this->assignRef('currentNode', $currentNode);

		$this->assignRef('option', JRequest::getVar('option'));
		$this->assignRef('view', JRequest::getVar('view'));
		$this->assignRef('itemid', JRequest::getVar('Itemid'));
		
		parent::display($tpl);
	}
}
?>
