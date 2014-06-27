<?php

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