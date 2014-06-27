<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');
 
// Load the component helper.
require_once JPATH_COMPONENT.'/helpers/gescore.php';

class GestorcoreViewRevista extends JView
{
    function display($tpl = null) {
        GescoreHelper::addSubmenu("listRevistas");
        $this->addToolBar();
        parent::display($tpl);
    }

    protected function addToolBar(){
        if(JRequest::getCmd("task") == "newIssueRevista") {
            JToolBarHelper::title("GesCORE: Nueva Issue - Revista: " . $this->issnRevista);
            JToolBarHelper::save('saveNuevaIssueRevista', "Guardar");
            JToolBarHelper::cancel('cancelar_showRevista', 'Cancelar');
	}
        else if (JRequest::getCmd('task') == 'nuevaIssue') {
            JToolBarHelper::title("GesCORE: Nueva Issue");
            JToolBarHelper::save('saveNuevaIssue', "Guardar"); //
            JToolBarHelper::cancel('cancelarNuevaIssue', 'Cancelar'); //volver
        }
        else if (JRequest::getCmd("task") == "editIssue_infoIssue") { 
            JToolBarHelper::title("GesCORE: Editar Issue (volumen: " . $this->issue->volumen . " - numero: " . $this->issue->numero .")");
            JToolBarHelper::save('saveIssue_showIssue', "Guardar"); 
            JToolBarHelper::cancel('cancelar_showIssue', 'Cancelar');
        }
        else if (JRequest::getCmd("task") == "editIssue_infoRevista") {
            JToolBarHelper::title("GesCORE: Editar Issue (volumen: " . $this->issue->volumen . " - numero: " . $this->issue->numero .")");
            JToolBarHelper::save('saveIssue_showRevista', "Guardar");
            JToolBarHelper::cancel('cancelar_showRevista', 'Cancelar');
        }
        else {
            JToolBarHelper::back('Revistas', 'index.php?option=com_gestorcore&controller=revista&task=listRevistas');
            JToolBarHelper::title("GesCORE: Revista ( issn: " . $this->revista->issn . ")");
            JToolBarHelper::deleteList('¿Desea eliminar estas issues?','deleteIssue', "Eliminar Issue");
            JToolBarHelper::editList('editIssue_infoRevista', "Editar Issue");
            JToolBarHelper::addNew('newIssueRevista', 'Nueva issue');
        }
    }
 
 
}
    
?>