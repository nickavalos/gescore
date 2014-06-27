<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

require_once(JPATH_COMPONENT . DS . 'controllers' . DS . 'gescore.php');
 
class GestorcoreViewGescore extends JView
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
                $this->categories       = $this->get('TypesCategory');
                //$this->hola = $this->get('gescore.Publishe');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}
                GescoreHelper::addSubmenu('Buscador');    
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
	protected function addToolbar()
	{
		// Initialise variables.
		$state	= $this->get('State');
		$canDo	= GescoreHelper::getActions();

		JToolBarHelper::title(JText::_('COM_GESCORE_BUSCADOR_TITLE'));

		/*if ($canDo->get('core.create')) {
			JToolBarHelper::addNew('message.add', 'JTOOLBAR_NEW');
		}

		if ($canDo->get('core.edit')) {
			JToolBarHelper::editList('message.edit', 'JTOOLBAR_EDIT');
		}

		if ($canDo->get('core.edit.state')) {
			JToolBarHelper::publishList('messages.publish', 'JTOOLBAR_PUBLISH');
			JToolBarHelper::unpublishList('messages.unpublish', 'JTOOLBAR_UNPUBLISH');
			JToolBarHelper::archiveList('messages.archive','JTOOLBAR_ARCHIVE');
		}

		if ($state->get('filter.published') == -2 && $canDo->get('core.delete')) {
			JToolBarHelper::deleteList('', 'messages.delete','JTOOLBAR_EMPTY_TRASH');
		} else if ($canDo->get('core.edit.state')) {
			JToolBarHelper::trash('messages.trash','JTOOLBAR_TRASH');
		}

		if ($canDo->get('core.admin')) {
			//JToolBarHelper::preferences('com_hello');
		}*/
                //$bar = JToolBar::getInstance('toolbar');
                // Add a configuration button.
                //$bar->appendButton('Popup', 'options', 'alt', 'index.php?option=com_gestorcore&amp;view=congresos&amp;layout=default_nuevoCongreso&amp;path=&amp;tmpl=component', '600', '600', 0, 0, '');
                
	}
 
}
    
?>