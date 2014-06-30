<?php
/**
 * This file is part of the project Gestor de Congresos y Revistas en Joomla (GesCORE).
 *
 * @package		GesCORE backend 
 * @subpackage  Views
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

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');
 
require_once JPATH_COMPONENT.'/helpers/gescore.php';

class GestorcoreViewCongreso extends JView
{
    function display($tpl = null) {
        $this->addToolBar();
        
        GescoreHelper::addSubmenu("listCongresos");
        parent::display($tpl);
    }

    protected function addToolBar(){
        if(JRequest::getCmd("task") == "newEdicionCongreso") {
            JToolBarHelper::title("GesCORE: Añadir edición al congreso: " . $this->nomCongreso);
            JToolBarHelper::save('saveNuevaEdicionCongreso', "Guardar");
            JToolBarHelper::cancel('cancelar_showCongreso', 'Cancelar');
	}
        else if (JRequest::getCmd('task') == 'nuevaEdicion') {
            JToolBarHelper::title("GesCORE: Nueva edición");
            JToolBarHelper::save('saveNuevaEdicion', "Guardar"); //
            JToolBarHelper::cancel('cancelarNuevaEdicion', 'Cancelar'); //volver
        }
        else if (JRequest::getCmd("task") == "editEdicion_infoCongreso") {
            JToolBarHelper::title("GesCORE: Editar Edición (Nro orden: " . $this->edicion->orden . ")" );
            JToolBarHelper::save('saveEdicion_showCongreso', "Guardar");
            JToolBarHelper::cancel('cancelar_showCongreso', 'Cancelar');
        }
        else if (JRequest::getCmd("task") == "editEdicion_infoEdicion") {
            JToolBarHelper::title("GesCORE: Editar Edición (Nro orden: " . $this->edicion->orden . ")" );
            JToolBarHelper::save('saveEdicion_showEdicion', "Guardar");  
            JToolBarHelper::cancel('cancelar_showEdicion', 'Cancelar');
        }
        else {
            JToolBarHelper::back('Congresos', 'index.php?option=com_gestorcore&controller=congreso&task=listCongresos');
            JToolBarHelper::title("GesCORE - Congreso: " . $this->nomCongreso);
            JToolBarHelper::deleteList('¿Desea eliminar estas ediciones?','deleteEdicion', "Eliminar edición");
            JToolBarHelper::editList('editEdicion_infoCongreso', "Editar edición");
            JToolBarHelper::addNew('newEdicionCongreso', 'Nueva edición');
        }
    }
 
 
}
    
?>