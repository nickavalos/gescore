<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');
// Load the component helper.
require_once JPATH_COMPONENT.'/helpers/gescore.php';
 
class GestorcoreViewImport extends JView
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
        //$this->items		= $this->get('Items');
        //$this->pagination	= $this->get('Pagination');
        //$this->state		= $this->get('State');
        //$this->layout = JRequest::getCmd('layout');

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
                JError::raiseError(500, implode("\n", $errors));
                return false;
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
        GescoreHelper::addSubmenu('Import');  
        
        // Initialise variables.
        JToolBarHelper::title('GesCORE: Importar desde Scopus');
        JToolBarHelper::custom('', 'upload.png', '', 'Importar Seleccionados', false);
        
        if (JRequest::getCmd('layout') == 'default_scopus') {
            //JToolBarHelper::title('GesCORE: Importar desde Scopus');
            //JToolBarHelper::save('topico.nuevoTopico', 'Guardar');
            //JToolBarHelper::cancel('topico.cancelarTopico', 'Cancelar');
        }
        else {
            //JToolBarHelper::title('GesCORE: Importar desde Scopus');
            //JToolBarHelper::deleteList('¿Desea eliminar los tópicos seleccionados?','topico.deleteTopico', "Eliminar tópicos");
        }
    }
 
}
    
?>