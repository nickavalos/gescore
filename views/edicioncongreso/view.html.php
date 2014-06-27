<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');
 
// Load the component helper.
require_once JPATH_COMPONENT.'/helpers/gescore.php';

class GestorcoreViewEdicionCongreso extends JView
{
    function display($tpl = null) {
        GescoreHelper::addSubmenu("");
        $this->addToolBar();
        parent::display($tpl);
    }

    protected function addToolBar(){
        JToolBarHelper::title("GesCORE: Edición " . $this->edicion->orden . " de " . $this->edicion->nomCongreso);
        JToolBarHelper::back('Congreso', 'index.php?option=com_gestorcore&controller=congreso&task=infoCongreso&id=' . $this->edicion->nomCongreso);
        //JToolBarHelper::deleteList('¿Desea eliminar estas ediciones?','deleteEdicion', "Eliminar");
        JToolBarHelper::editList('editEdicion_infoEdicion', "Editar");
    }
 
 
}
    
?>