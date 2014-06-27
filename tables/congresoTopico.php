<?php

/**
 * @version		$Id: gestorcore.php 46 2013-11-18 17:27:33Z chdemko $
 * @package		Joomla16
 * @subpackage          Components
 * @copyright           Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @author		Nick Avalos
 * @link		http://google.com/+NickAvalos
 * @license		License GNU General Public License version 2 or later
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.table');

/**
 * Table class
 */
class GestorcoreTableCongresoTopico extends JTable
{
	/**
	 * Constructor de la clase
	 *
	 * @param object Database connector object
	 */
	function __construct(&$db) 
	{
		parent::__construct('#__congresoTopico', 'nomCongreso', $db);
	}
}
