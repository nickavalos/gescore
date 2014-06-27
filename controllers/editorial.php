<?php

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controlleradmin');
require_once( JPATH_COMPONENT.DS.'consoleLog.php' );

//jimport( 'joomla.html.html.jgrid' );
 
class GestorcoreControllerEditorial extends JControllerAdmin {
    var $model_editorial = 'editorial';
    var $view_editorial = 'editorial';
    var $type_html = 'html'; 
    
    function postNuevaEditorial() {
        $document = JFactory::getDocument();
        $document->setMimeEncoding( 'application/json' );
        $modelEditorial = $this->getModel($this->model_editorial);
        $nombre = JRequest::getVar('nombre');
        $siglas = JRequest::getVar('siglas');
        $link = JRequest::getVar('link');
        
        if ($modelEditorial->existsEditorial($nombre)) {
            echo json_encode(array('error' => 'Ya existe una editorial con ese nombre'));
        }else {
            $modelEditorial->insertEditorial($nombre, $siglas, $link);
            echo json_encode(array('result' => 'OK'));
        }
    }

    function display($cachable = false, $urlparams= false)
    {	
        parent::display();
    }
    
}

?>