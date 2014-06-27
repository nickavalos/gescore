<?php

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controlleradmin');
require_once( JPATH_COMPONENT.DS.'consoleLog.php' );

//jimport( 'joomla.html.html.jgrid' );
 
class GestorcoreControllerTopico extends JControllerAdmin {
    var $model_topico = 'topico';
    var $model_info_topico = 'infotopico';
    var $view_topico = 'topico';
    var $view_infoTopico = 'infoTopico';
    var $type_html = 'html'; 
    
    function nuevoTopico() {
        $model = $this->getModel($this->model_topico);
        $nomTopico = JRequest::getVar('nomTopico');
        $descripcion = JRequest::getVar('descripcionTopico');
        $model->insertTopico($nomTopico, $descripcion);
        $similares = JRequest::getVar('listSimilaresSeleccionados', array(), '', 'array');
        foreach ($similares as $topSimilar) {
            $model->insertTopicoSimilar($nomTopico, $topSimilar);
        }
        $this->setRedirect(JRoute::_('index.php?option=com_gestorcore&view=infotopico&id=' . $nomTopico));
    }
    
    function deleteTopico() {
        $cid = JRequest::getVar( 'cid' , array() , '' , 'array' );
        $model = $this->getModel($this->model_topico);
        if (count($cid) > 0) {
            foreach($cid as $topico) {
                $model->deleteTopico($topico);
            }
        }
        $this->setRedirect(JRoute::_('index.php?option=com_gestorcore&view=topico'));
    }
    
    /*************************AJAX*******************************************/
    function existeTopico() {
        $document = JFactory::getDocument();
        $document->setMimeEncoding( 'application/json' );
        $modelTopico = $this->getModel($this->model_topico);
        $res = $modelTopico->existsTopico(JRequest::getVar('nomTopico'));
        echo json_encode(array("existe" => $res));
    }
    
    function put_editTopico() {
        $modelTopico = $this->getModel($this->model_topico);
        $nomTopicoActual = JRequest::getVar('nomTopico_copy');
        $topico = $this->getModel($this->model_info_topico)->getTopico($nomTopicoActual);
        $error = false;
        $cambioID = false;
        switch (JRequest::getVar('id')) {
            case "nomTopico":  //Ojo: cambiamos el id
                $nuevoID = JRequest::getVar('value');
                if ($modelTopico->existsTopico($nuevoID)) {
                    echo ("Error: Ya existe un topico con ese nombre");
                    $error = true;
                }else {
                    $topico->nombre = $nuevoID;
                    $cambioID = true;
                }
                break;
            case "descripcionTopico":
                $topico->descripcion = JRequest::getVar('value');
                break;
            default:
                echo ("Error en la actualización.");
                $error = true;
        }
        if (!$error) {
            $modelTopico->updateTopico($nomTopicoActual, $topico->nombre, $topico->descripcion);
            echo JRequest::getVar('value');
        }
        if ($cambioID) {$this->setRedirect(JRoute::_('index.php?option=com_gestorcore&view=infoTopico&id='.$topico->nombre));}
    }
    
    function put_eliminarTopicos() {
        $document = JFactory::getDocument();
        $document->setMimeEncoding( 'application/json' );
        $listaTopicosEliminar = JRequest::getVar('topicosEliminar', array(), '', 'array');
        $modelTopico = $this->getModel($this->model_topico);
        $topico = JRequest::getVar('nomTopico');
        foreach ($listaTopicosEliminar as $topSimilar) {
            $modelTopico->deleteTopicoSimilar($topico, $topSimilar);
        }
        echo json_encode($listaTopicosEliminar);
    }
    
    function get_topicosFiltrados_ajax() {
        $document = JFactory::getDocument();
        $document->setMimeEncoding( 'application/json' );
        $modelTopico = $this->getModel($this->model_topico);
        $listaTopicos = $modelTopico->getTopicosSimilares_filtrados(JRequest::getVar('nomTopico'));
        echo json_encode($listaTopicos);
    }
    
    function post_nuevosTopicosSimilares() {
        $document = JFactory::getDocument();
        $document->setMimeEncoding( 'application/json' );
        $listaNuevosTopicos = JRequest::getVar('nuevosTopicos', array(), '', 'array');
        $modelTopico = $this->getModel($this->model_topico);
        $topico = JRequest::getVar('nomTopico');
        foreach ($listaNuevosTopicos as $topSimilar) {
            $modelTopico->insertTopicoSimilar($topico, $topSimilar);
        }
        echo json_encode($listaNuevosTopicos);
    }

    function display($cachable = false, $urlparams= false)
    {	
        parent::display();
    }
    
}

?>