<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');
 
// Load the component helper.
require_once JPATH_COMPONENT.'/helpers/gescore.php';

class GestorcoreViewIssueRevista extends JView
{
    function display($tpl = null) {
        GescoreHelper::addSubmenu("");
        $this->addToolBar();
        parent::display($tpl);
    }

    protected function addToolBar(){
        JToolBarHelper::title("GesCORE - Issue (volumen: " . $this->issue->volumen . " numero: " . $this->issue->numero . ") - Revista: " . $this->nomRevista);
        JToolBarHelper::back('Revista', 'index.php?option=com_gestorcore&controller=revista&task=infoRevista&id=' . $this->issue->issnRevista);
        //JToolBarHelper::deleteList('¿Desea eliminar estas ediciones?','deleteEdicion', "Eliminar");
        JToolBarHelper::editList('editIssue_infoIssue', "Editar");
    }
 
 
}
    
?>