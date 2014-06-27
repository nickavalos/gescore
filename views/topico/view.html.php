<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');
// Load the component helper.
require_once JPATH_COMPONENT.'/helpers/gescore.php';
 
class GestorcoreViewTopico extends JView
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
        $this->topicosSimilares = $this->get('AllTopicosSimilares');

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
                JError::raiseError(500, implode("\n", $errors));
                return false;
        }
        
        foreach ($this->topicosSimilares as $topico) {
            $this->similares[$topico->nomTopico][] = $topico->nomTopicoSimilar;
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
        GescoreHelper::addSubmenu('Topicos');  
        
        // Initialise variables.
        //$state	= $this->get('State');

        if (JRequest::getCmd('layout') == 'default_nuevotopico') {
            JToolBarHelper::title('GesCORE: Nuevo Tópico');
            JToolBarHelper::save('topico.nuevoTopico', 'Guardar');
            JToolBarHelper::cancel('topico.cancelarTopico', 'Cancelar');
        }
        else {
            JToolBarHelper::title('GesCORE: Lista de Tópicos');
            JToolBarHelper::deleteList('¿Desea eliminar los tópicos seleccionados?','topico.deleteTopico', "Eliminar tópicos");
        }
    }
 
}
    
?>