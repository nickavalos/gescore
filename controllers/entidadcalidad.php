<?php
/**
 * This file is part of the project Gestor de Congresos y Revistas en Joomla (GesCORE).
 *
 * @package		GesCORE backend 
 * @subpackage  Controllers 
 * @copyright   Copyright (C) 2014 Open Source Matters, Inc. All rights reserved.
 * @authors		Nick Avalos, Enric Mayol, Mª José Casany
 * @link		https://github.com/nickavalos/gescore
 * @license		License GNU General Public License
 *
 * GesCORE is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * GesCORE is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with GesCORE. If not, see <http://www.gnu.org/licenses/>.
 */

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controlleradmin');
require_once( JPATH_COMPONENT.DS.'consoleLog.php' );

//jimport( 'joomla.html.html.jgrid' );
 
class GestorcoreControllerEntidadCalidad extends JControllerAdmin {
    var $model_entidadCalidad = 'entidadCalidad';
    var $view_entidadCalidad = 'entidadCalidad';
    var $view_infoEntidad = 'infoEntidad';
    var $type_html = 'html'; 
    
    function nuevaEntidadCalidad() {
        $model = $this->getModel($this->model_entidadCalidad);
        $nombre = JRequest::getVar('nomEntidad');
        $siglas = JRequest::getVar('acronimoEntidad');
        $link = JRequest::getVar('linkEntidad');
        $criterios = JRequest::getVar('criteriosEntidad');
        $tipoCategoria = JRequest::getVar('radioTipoCategoria');
        $model->insertEntidadCalidad($nombre, $siglas, $link, $criterios, $tipoCategoria);
        $categorias = JRequest::getVar('categorias', array(), '', 'array');
        foreach ($categorias as $i=>$categoria) {
            if ($categoria != "") {
                $model->insertCategoria($categoria, $i+1);
                $model->insertEntidadCategoria($nombre, $categoria, $i+1);
            }
        }
        $this->setRedirect(JRoute::_('index.php?option=com_gestorcore&view=infoEntidadCalidad&id=' . $nombre));
    }
    
    function deleteEntidadCalidad() {
        $cid = JRequest::getVar( 'cid' , array() , '' , 'array' );
        $model = $this->getModel($this->model_entidadCalidad);
        if (count($cid) > 0) {
            foreach($cid as $entCalidad) {
                $model->deleteEntidadCalidad($entCalidad);
            }
        }
        $this->setRedirect(JRoute::_('index.php?option=com_gestorcore&view=entidadCalidad'));
    }
    
    function infoEntidadCalidad() { //no usado
        $viewCalidad = $this->getView($this->view_entidadCalidad, $this->type_html);
        $modelCalidad = $this->getModel($this->model_entidadCalidad);
        $nombre = JRequest::getVar('id');
        $viewCalidad->entidadCalidad = $modelCalidad->getEntidadCalidad($nombre);
        $viewCalidad->entidadCalidad->categorias = $modelCalidad->getCategoriasEntidad($nombre);
        $viewCalidad->valoracionesEdiciones = $modelCalidad->getValoracionesEntidad('edicion', $nombre);
        $viewCalidad->valoracionesIssues = $modelCalidad->getValoracionesEntidad('issue', $nombre);
        $viewCalidad->display($this->view_infoEntidad);
    }
    
    /*************************AJAX*******************************************/
    function existeEntidadCalidad() {
        $document = JFactory::getDocument();
        $document->setMimeEncoding( 'application/json' );
        $modelEntidadCalidad = $this->getModel($this->model_entidadCalidad);
        $res = $modelEntidadCalidad->getEntidadCalidad(JRequest::getVar('nomEntidad')) !== null;
        echo json_encode(array("existe" => $res));
    }
    
    function put_editEntidadCalidad() {
        $modelCalidad = $this->getModel($this->model_entidadCalidad);
        $nomEntidadCalidadActual = JRequest::getVar('entidadCalidad_copy');
        $entCalidad = $modelCalidad->getEntidadCalidad($nomEntidadCalidadActual);
        $error = false;
        $cambioID = false;
        switch (JRequest::getVar('id')) {
            case "nomEntidadCalidad":  //Ojo: cambiamos el id
                $nuevoID = JRequest::getVar('value');
                if ($modelCalidad->existsEntidadCalidad($nuevoID)) {
                    echo ("Error: Ya existe una entidad con ese nombre");
                    $error = true;
                }else {
                    $entCalidad->nombre = $nuevoID;
                    $cambioID = true;
                }
                break;
            case "siglasEntidadCalidad":
                $entCalidad->siglas = JRequest::getVar('value');
                break;
            case "linkEntidadCalidad":
                $entCalidad->link = JRequest::getVar('value');
                break;
            case "criteriosEntidadCalidad":
                $entCalidad->criterios = JRequest::getVar('value');
                break;
            default:
                echo ("Error en la actualización.");
                $error = true;
        }
        if (!$error) {
            $modelCalidad->updateEntidadCalidad($nomEntidadCalidadActual, $entCalidad->nombre, $entCalidad->siglas, 
                                           $entCalidad->link, $entCalidad->criterios);
            echo JRequest::getVar('value');
        }
        if ($cambioID) {$this->setRedirect(JRoute::_('index.php?option=com_gestorcore&view=infoEntidadCalidad&id='.$entCalidad->nombre));}
    }

    function display($cachable = false, $urlparams= false)
    {	
        parent::display();
    }
    
}

?>