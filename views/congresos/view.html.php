<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');
 
// Load the component helper.
require_once JPATH_COMPONENT.'/helpers/gescore.php';

class GestorcoreViewCongresos extends JView
{
	function display($tpl = null)
	{
                // Load the submenu.
                GescoreHelper::addSubmenu("listCongresos");
                $this->addToolBar();
		parent::display($tpl);
	}
 
	protected function addToolBar() {
            
            if (JRequest::getVar('layout') == 'default_nuevoCongreso') {
                JToolBarHelper::title("GesCORE: Nuevo Congreso");
                JToolBarHelper::save('nuevoCongreso', 'Guardar');
                JToolBarHelper::cancel('cancelarNuevoCongreso', 'Cancelar');
            }
            else {
                JToolBarHelper::title("GesCORE: Lista de Congresos");
                JToolBarHelper::deleteList('¿Desea eliminar los congresos seleccionados?','deleteCongreso', "Eliminar congresos");
//                JToolBarHelper::deleteList('Desea eliminar estos mensajes?','eliminar');
//                JToolBarHelper::editList('editar');
//                JToolBarHelper::addNew('nuevo');
            }
	}
 
 
}
    
?>