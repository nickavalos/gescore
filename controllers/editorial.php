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
 
class GestorcoreControllerEditorial extends JControllerAdmin {
    var $model_editorial = 'editorial';
    var $view_editorial = 'editorial';
    var $type_html = 'html'; 
    
    function postNuevaEditorial() {
        $document = JFactory::getDocument();
        $document->setMimeEncoding( 'application/json' );
        $modelEditorial = $this->getModel($this->model_editorial);
        $nombre = JRequest::getVar('nombre');
        $siglas = JRequest::getVar('siglas');
        $link = JRequest::getVar('link');
        
        if ($modelEditorial->existsEditorial($nombre)) {
            echo json_encode(array('error' => 'Ya existe una editorial con ese nombre'));
        }else {
            $modelEditorial->insertEditorial($nombre, $siglas, $link);
            echo json_encode(array('result' => 'OK'));
        }
    }

    function display($cachable = false, $urlparams= false)
    {	
        parent::display();
    }
    
}

?>