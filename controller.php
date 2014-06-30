<?php
/**
 * This file is part of the project Gestor de Congresos y Revistas en Joomla (GesCORE).
 *
 * @package		GesCORE backend
 * @subpackage  - 
 * @copyright   Copyright (C) 2014 Open Source Matters, Inc. All rights reserved.
 * @authors		Nick Avalos, Enric Mayol, Mª José Casany
 * @link		https://github.com/nickavalos/gescore
 * @license		License GNU General Public License
 *
 * GesCORE is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * GesCORE is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with GesCORE. If not, see <http://www.gnu.org/licenses/>.
 */

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