<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');
// Load the component helper.
require_once JPATH_COMPONENT.'/helpers/gescore.php';
 
class GestorcoreViewEntidadCalidad extends JView
{
    /**
    * @var		array	The array of records to display in the list.
    * @since	1.0
    */
    protected $items;

    /**
    * @var		JPagination	The pagination object for the list.
    * @since	1.0
    */
    protected $pagination;

    /**
    * @var		JObject	The model state.
    * @since	1.0
    */
    protected $state;

    /**
    * Prepare and display the Messages view.
    *
    * @return	void
    * @since	1.0
    */
    public function display($tpl = NULL)
    {
        
        // Initialise variables.
        $this->items		= $this->get('Items');
        $this->pagination	= $this->get('Pagination');
        $this->state		= $this->get('State');

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
                JError::raiseError(500, implode("\n", $errors));
                return false;
        }
        if (JRequest::getVar('layout') == 'default_infoEntidad') { //vista info entidad calidad: No usado temporalmente
            $modelCalidad = $this->getModel('entidadCalidad');
            $nombre = JRequest::getVar('id');
            $this->entidadCalidad = $modelCalidad->getEntidadCalidad($nombre);
            $this->entidadCalidad->categorias = $modelCalidad->getCategoriasEntidad($nombre);
        }
        
        // Add the toolbar and display the view layout.
        $this->addToolbar();
        parent::display($tpl);
    }

    /**
    * Add the page title and toolbar.
    *
    * @return	void
    * @since	1.0
    */
    protected function addToolbar() {
        GescoreHelper::addSubmenu('EntidadCalidad');  
        
        // Initialise variables.
        $state	= $this->get('State');

        if (JRequest::getCmd('layout') == 'default_nuevaentidadcalidad') {
            JToolBarHelper::title('GesCORE: Nueva Entidad de Calidad');
            JToolBarHelper::save('entidadcalidad.nuevaEntidadCalidad', 'Guardar');
            JToolBarHelper::cancel('entidadcalidad.cancelarEntidadCalidad', 'Cancelar');
        }
        else {
            JToolBarHelper::title('GesCORE: Lista de Entidades de Calidad');
            JToolBarHelper::deleteList('¿Desea eliminar las entidades seleccionadas?','entidadcalidad.deleteEntidadCalidad', "Eliminar Entidades");
        }
    }
 
}
    
?>