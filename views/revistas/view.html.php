<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');
 
// Load the component helper.
require_once JPATH_COMPONENT.'/helpers/gescore.php';

class GestorcoreViewRevistas extends JView
{
	function display($tpl = null)
	{
            GescoreHelper::addSubmenu("listRevistas");
            $this->addToolBar();
            parent::display($tpl);
	}
 
	protected function addToolBar() 
	{
            if (JRequest::getVar('layout') == 'default_nuevaRevista') {
                JToolBarHelper::title("GesCORE: Nuevo Congreso");
                JToolBarHelper::save('nuevaRevista', 'Guardar');
                JToolBarHelper::cancel('cancelarNuevaRevista', 'Cancelar');
            } else {
                JToolBarHelper::title("GesCORE: Lista de Revistas");
                JToolBarHelper::deleteList('¿Desea eliminar las revistas seleccionadas?','deleteRevista', "Eliminar revistas");
//                JToolBarHelper::deleteList('Desea eliminar estos mensajes?','eliminar');
//                JToolBarHelper::editList('editar');
//                JToolBarHelper::addNew('nuevo');
            }
	}
 
 
}
    
?>