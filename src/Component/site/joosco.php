<?php
/**
 * @package    D-Bog
 * @subpackage Joosco
 * components/com_joosco/joosco.php
 * @link http://www.d-bog.com
 * @license    GNU/GPL
*/

// Most of the code below comes from the companent tutorial on the Joomla Documentation
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

if (isset($_SESSION["ticket"])) {
	// Require the base controller
	require_once( JPATH_COMPONENT.DS.'controller.php' );

	// Require specific controller if requested
	if($controller = JRequest::getWord('controller')) {
		$path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';
		if (file_exists($path)) {
			require_once $path;
		} else {
			$controller = '';
		}
	}

	// Create the controller
	$classname    = 'JooscoController'.$controller;
	$controller   = new $classname( );

	// Perform the Request task
	$controller->execute( JRequest::getVar( 'task' ) );

	// Redirect if set by the controller
	$controller->redirect();
}
else {
	echo "<p>You are not logged in to Alfresco, so we can't provide any documents. It can happen if the Joomla and Alfresco user names or passwords don't match, or if you're not logged in to Joomla.</p>";
}
?>