<?php

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controlleradmin');
require_once( JPATH_COMPONENT.DS.'consoleLog.php' );

//jimport( 'joomla.html.html.jgrid' );
 
class GestorcoreControllerGescore extends JControllerAdmin {	
    /**
    * Proxy for getModel.
    *
    * @param	string	$name	The name of the model.
    * @param	string	$prefix	The prefix for the model class name.
    * @param	string	$config	The model configuration array.
    *
    * @return	HelloModelMessages	The model for the controller set to ignore the request.
    * @since	1.6
    */
    /*public function getModel($name = 'Gescore', $prefix = 'GestorCORE', $config = array('ignore_request' => true))
    {
           return parent::getModel($name, $prefix, $config);
    }*/
    
    var $model_gescore = 'gescore'; 
    
    function display($cachable = false, $urlparams= false) {	
        parent::display();
    }
    
    function getResults_rapidSearch() {
        $document = JFactory::getDocument();
        $document->setMimeEncoding( 'application/json' );
        $model = $this->getModel($this->model_gescore);
        echo json_encode($model->getResults_rapidSearch(JRequest::getVar('filter')));
    }
    
}

?>