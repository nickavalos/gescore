<?php

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controller');
 
jimport( 'joomla.html.html.jgrid' );

class GestorcoreController extends JController
{	
	protected $default_view = 'gescore';

	/**
	 * Override the display method for the controller.
	 *
	 * @return	void
	 * @since	1.0
	 */
	function display($cachable = false, $urlparams = false) 
	{
		// Load the component helper.
		require_once JPATH_COMPONENT.'/helpers/gescore.php';

		// Load the submenu.
		//$view = JRequest::getCmd('view', 'messages');
		//GescoreHelper::addSubmenu($view);
                
                parent::display();
	}
        
}

?>