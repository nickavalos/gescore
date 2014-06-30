<?php
/**
 * This file is part of the project Gestor de Congresos y Revistas en Joomla (GesCORE).
 *
 * @package		GesCORE backend 
 * @subpackage  Controllers 
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
jimport('joomla.application.component.controlleradmin');
require_once( JPATH_COMPONENT.DS.'consoleLog.php' );

//jimport( 'joomla.html.html.jgrid' );
 
class GestorcoreControllerGescore extends JControllerAdmin {	
    /**
    * Proxy for getModel.
    *
    * @param	string	$name	The name of the model.
    * @param	string	$prefix	The prefix for the model class name.
    * @param	string	$config	The model configuration array.
    *
    * @return	HelloModelMessages	The model for the controller set to ignore the request.
    * @since	1.6
    */
    /*public function getModel($name = 'Gescore', $prefix = 'GestorCORE', $config = array('ignore_request' => true))
    {
           return parent::getModel($name, $prefix, $config);
    }*/
    
    var $model_gescore = 'gescore'; 
    
    function display($cachable = false, $urlparams= false) {	
        parent::display();
    }
    
    function getResults_rapidSearch() {
        $document = JFactory::getDocument();
        $document->setMimeEncoding( 'application/json' );
        $model = $this->getModel($this->model_gescore);
        echo json_encode($model->getResults_rapidSearch(JRequest::getVar('filter')));
    }
    
}

?>