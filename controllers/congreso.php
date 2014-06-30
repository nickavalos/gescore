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
jimport('joomla.application.component.controller');
require_once( JPATH_COMPONENT.DS.'consoleLog.php' );

 
class GestorcoreControllerCongreso extends JController
{
    function listCongresos(){ //modificado2
        $view = $this->getView('congresos','html');
        $model= $this->getModel("congreso");
        //$congresos = $model->getCongresos();
        $congresos = $model->getItems();
        $view->assignRef('congresos',$congresos);
        $view->pagination = $model->getPagination();
        $view->display();
    }

    function infoCongreso() {  //getCongreso
        $view = $this->getView("congreso",'html');
        $model= $this->getModel("edicionCongreso");
        $nomCongreso = JRequest::getVar('id');
        $model->setCongreso($nomCongreso);
        $ediciones = $model->getItems();
        //$this->setRedirect(JRoute::_('index.php?option=com_gestorcore&controller=congreso&task=listEdiciones&id=Edicion 1'));
        $modelCongreso = $this->getModel("Congreso");
        $view->congreso = $modelCongreso->getCongreso($nomCongreso);
        $view->congreso->topicos = $modelCongreso->getTopicosFromCongreso($nomCongreso);
        $view->nomCongreso = $nomCongreso;
        $view->assignRef('ediciones', $ediciones);
        $view->paginacion = $model->getPagination();
        $view->state = $model->getState();
        $view->display();
    }
    
    function infoEdicion() {
        $orden = JRequest::getVar('orden');
        $congreso = JRequest::getVar('congreso');
        $modelEdicion = $this->getModel('edicionCongreso');
        $modelEdicion->setCongreso($congreso);
        $edicion = $modelEdicion->getEdicion($orden);
        $view = $this->getView("edicionCongreso",'html');
        $view->edicion = $edicion;
        $view->edicion->topicos = $modelEdicion->getTopicosEdicion($orden);
        $modelValoracion = $this->getModel('entidadCalidad');
        $view->edicion->valoraciones = $modelValoracion->getValoraciones($congreso, $orden);
        $view->display();
    }
    
    function _nuevaEdicion($identCongreso) {
        $view = $this->getView('congreso', 'html');
        $view->nomCongreso = $identCongreso;
//        $model = $this->getModel("edicionCongreso");
//        $model->setCongreso($identCongreso);
        $modelCongreso = $this->getModel('congreso');
        $view->listaCongresos = $modelCongreso->getCongresos();
        $view->topicosFromCongreso = $modelCongreso->getTopicosFromCongreso($identCongreso);
        $view->ultimaEdicionCongreso = $modelCongreso->getUltimaEdicionCongreso($identCongreso);
        $modelCalidad = $this->getModel("EntidadCalidad");
        $view->entidadesCalidad = $modelCalidad->getEntidadesIndiceCalidad();
        $view->display("nuevaEdicionCongreso");
    }
    
    function nuevaEdicion() { 
        $this->_nuevaEdicion(-1);
    }
    
    function newEdicionCongreso() { //llamada desde congreso
        $this->_nuevaEdicion(JRequest::getVar('id'));
    }
    
    function formatToTable($dateString) {
        return date('Ymd', strtotime(str_replace('/', '-', $dateString)));    
    }
    
    function _saveNuevaEdicion($orden, $nomCongreso, $lugar, $linkEdicion, $limRecepcion, $fechaDefinitiva, $fechaInicio, $fechaFin) {
        $model = $this->getModel("edicionCongreso");
        $model->insertEdicion($orden, $nomCongreso, $lugar, $linkEdicion, $limRecepcion, $fechaDefinitiva, $fechaInicio, $fechaFin);
        
        /*topicos añadidos del congreso*/
        $model->insertListTopicosEdicion($orden, $nomCongreso, JRequest::getVar("topicosFromCongreso", array(), '', 'array'));
        
        /* otros tópicos*/
        //if (JRequest::getVar('checkOtrosTopicos', 'off') == 'on') {
            $listOtrosTopicos = JRequest::getVar("listTopicosSeleccionados", array(), '', 'array');
            $model->insertListTopicosEdicion($orden, $nomCongreso, $listOtrosTopicos);
        //}
        
        /*Check de nuevos topicos, e insertamos*/
        $arrayTopicos = JRequest::getVar("nuevoTopico", array(), '', 'array');
        $arrayDescripcionTopicos = JRequest::getVar("descripcionNuevoTopico", array(), '', 'array');
        //if (JRequest::getVar("checkNuevosTopicos", "off") == "on") { //nuevos topicos
            for ($i = 0; $i < count($arrayTopicos); $i++ ) {
                if ($arrayTopicos[$i] != "") {
                    $model->insertTopico($arrayTopicos[$i], $arrayDescripcionTopicos[$i]);
                    $model->insertTopicoEdicion($orden, $nomCongreso, $arrayTopicos[$i]);
                }
            }
        //}
        //valoraciones
        $modelCalidad = $this->getModel("EntidadCalidad");
        
        $listaEntidad = JRequest::getVar('selectorEntidadCalidad', array(), '', 'array');
        $listaValoraciones = JRequest::getVar('selectorCategoriaCalidad', array(), '', 'array');
        for ($i = 0; $i < count($listaEntidad); $i++) {
            if ($listaEntidad[$i] != "") { //vacio
                $modelCalidad->insertValoracionEdicion($listaEntidad[$i], $orden, $nomCongreso, $listaValoraciones[$i]);
            }
        }
    }
    
    function saveNuevaEdicionCongreso() {
        $orden = JRequest::getInt("orden");
        if ($orden <= 0) { //orden vacia
            $this->infoCongreso();
            return;
        }
        $nomCongreso = JRequest::getVar("selectorCongreso");
        $lugar = JRequest::getVar("lugar");
        $linkEdicion = JRequest::getVar("linkEdicion");
        $limRecepcion = $this->formatToTable(JRequest::getVar("limRecepcion"));
        $fechaDefinitiva = $this->formatToTable(JRequest::getVar("fechaDefinitiva"));
        $fechaInicio = $this->formatToTable(JRequest::getVar("fechaInicio"));
        $fechaFin = $this->formatToTable(JRequest::getVar("fechaFin"));
        
        $this->_saveNuevaEdicion($orden, $nomCongreso, $lugar, $linkEdicion, $limRecepcion, $fechaDefinitiva, $fechaInicio, $fechaFin);
        $this->infoCongreso();
    }
    
    function saveNuevaEdicion() {
        $orden = JRequest::getInt("orden");
        $nomCongreso = JRequest::getVar("selectorCongreso");
        $lugar = JRequest::getVar("lugar");
        $linkEdicion = JRequest::getVar("linkEdicion");
        $limRecepcion = $this->formatToTable(JRequest::getVar("limRecepcion"));
        $fechaDefinitiva = $this->formatToTable(JRequest::getVar("fechaDefinitiva"));
        $fechaInicio = $this->formatToTable(JRequest::getVar("fechaInicio"));
        $fechaFin = $this->formatToTable(JRequest::getVar("fechaFin"));
        
        $this->_saveNuevaEdicion($orden, $nomCongreso, $lugar, $linkEdicion, $limRecepcion, $fechaDefinitiva, $fechaInicio, $fechaFin);
        $this->setRedirect(JRoute::_('index.php?option=com_gestorcore&controller=congreso&task=infoEdicion&orden=' . $orden . '&congreso=' . $nomCongreso));
    }
            
    function saveEdicionCongreso() { //privada
        $orden = JRequest::getInt("orden");
        if ($orden <= 0) {
            $this->infoCongreso();
            return;
        }
        $ordenAnterior = JRequest::getVar("ordenActual"); //anterior es el ANTERIOR asignado
        $congresoAnterior = JRequest::getVar("congresoActual");//anterior es el ANTERIOR asignado
        $nomCongreso = JRequest::getVar("selectorCongreso", "noSelect");
        $lugar = JRequest::getVar("lugar");
        $linkEdicion = JRequest::getVar("linkEdicion");
        $limRecepcion = $this->formatToTable(JRequest::getVar("limRecepcion"));
        $fechaDefinitiva = $this->formatToTable(JRequest::getVar("fechaDefinitiva"));
        $fechaInicio = $this->formatToTable(JRequest::getVar("fechaInicio"));
        $fechaFin = $this->formatToTable(JRequest::getVar("fechaFin"));
        
        $model = $this->getModel("edicionCongreso");
        $model->updateEdicion($ordenAnterior, $congresoAnterior, $orden, $nomCongreso, $lugar, 
                              $linkEdicion, $limRecepcion, $fechaDefinitiva, $fechaInicio, $fechaFin);
        
        //eliminar anteriores topicos de edicion
        $model->setCongreso($nomCongreso);
        foreach ($model->getTopicosEdicion($orden) as $topicoEdicion) {
            $model->deleteTopicoEdicion($orden, "", $topicoEdicion->nomTopico);
        }
        //insertamos topicos del congreso
        $model->insertListTopicosEdicion($orden, "", JRequest::getVar("topicosFromCongreso", array(), '', 'array'));
        //insertamos los otros topicos
        $listOtrosTopicos = JRequest::getVar("listTopicosSeleccionados", array(), '', 'array');
        $model->insertListTopicosEdicion($orden, $nomCongreso, $listOtrosTopicos);
        
        //insertamos los nuevos tópicos
        $arrayTopicos = JRequest::getVar("nuevoTopico", array(), '', 'array');
        $arrayDescripcionTopicos = JRequest::getVar("descripcionNuevoTopico", array(), '', 'array');
        //if (JRequest::getVar("checkNuevosTopicos", "off") == "on") { //nuevos topicos: checkbox
            for ($i = 0; $i < count($arrayTopicos); $i++ ) {
                if ($arrayTopicos[$i] != "") {
                    $model->insertTopico($arrayTopicos[$i], $arrayDescripcionTopicos[$i]);
                    $model->insertTopicoEdicion($orden, $nomCongreso, $arrayTopicos[$i]);
                }
            }
        //}
        //valoraciones
        $modelCalidad = $this->getModel("EntidadCalidad");
        foreach($modelCalidad->getValoraciones($nomCongreso, $orden) as $valoracion) {
            $modelCalidad->deleteValoracionEdicion($valoracion->nomEntidad, $orden, $nomCongreso);
        }
        
        $listaEntidad = JRequest::getVar('selectorEntidadCalidad', array(), '', 'array');
        $listaValoraciones = JRequest::getVar('selectorCategoriaCalidad', array(), '', 'array');
        for ($i = 0; $i < count($listaEntidad); $i++) {
            if ($listaEntidad[$i] != "") { //vacio
                $modelCalidad->insertValoracionEdicion($listaEntidad[$i], $orden, $nomCongreso, $listaValoraciones[$i]);
            }
        }
    }
    
    function saveEdicion_showCongreso() {
        $this->saveEdicionCongreso();
        $this->infoCongreso(); 
    }
    
    function saveEdicion_showEdicion() {
        $orden = JRequest::getInt("orden");
        if ($orden <= 0) {
            $orden = JRequest::getVar("ordenActual"); //anterior es el ANTERIOR asignado
        }
        $selectCongreso = JRequest::getVar("selectorCongreso", "noSelect");
        $this->saveEdicionCongreso();
        $this->setRedirect(JRoute::_('index.php?option=com_gestorcore&controller=congreso&task=infoEdicion&orden=' . $orden . '&congreso=' . $selectCongreso)); 
    }
    
    function deleteCongreso() {
        $cid = JRequest::getVar( 'cid' , array() , '' , 'array' );
        $model = $this->getModel('congreso');
        foreach($cid as $nomCongreso) {
            $nomEditorial = $model->getCongreso($nomCongreso)->nomEditorial;
            $model->deleteCongreso($nomCongreso);
            if ($nomEditorial != null) {
                $modelEditorial = $this->getModel('editorial');
                $numeroPublicaciones_tipoCongreso = $modelEditorial->getNumEntidadesPublicadoras($nomEditorial, 'congreso'); 
                $numeroPublicaciones_tipoRevista = $modelEditorial->getNumEntidadesPublicadoras($nomEditorial, 'revista');
                if (($numeroPublicaciones_tipoCongreso + $numeroPublicaciones_tipoRevista) == 0) {
                    $modelEditorial->deleteEditorial($nomEditorial);
                }
            }
            
        }
        $this->setRedirect(JRoute::_('index.php?option=com_gestorcore&controller=congreso&task=listCongresos')); 
    }
    
    function deleteEdicion() {
        $cid = JRequest::getVar( 'cid' , array() , '' , 'array' );
        JArrayHelper::toInteger($cid);
        $model = $this->getModel("edicionCongreso");
        $model->setCongreso(JRequest::getVar('id'));
        if (count($cid) > 0) {
                $model->deleteEdicion($cid);
        }
        $this->infoCongreso();
    }
    
    function editEdicion($orden, $congreso, $uriPOST) {
        $view = $this->getView('congreso', 'html');
        $view->nomCongreso = $congreso;
        $modelEdicion = $this->getModel("edicionCongreso");
        $modelEdicion->setCongreso($view->nomCongreso);
        
        $modelCongreso = $this->getModel('congreso');
        $view->ultimaEdicionCongreso = $modelCongreso->getUltimaEdicionCongreso($congreso);
        $view->edicion = $modelEdicion->getEdicion($orden);
        $view->edicion->topicosFromCongreso_current = $modelCongreso->getTopicosFromCongreso($congreso);
        $view->edicion->topicos = $modelEdicion->getTopicosEdicion($orden);
        $view->listaCongresos = $modelCongreso->getCongresos();
        //$view->assignRef('listaCongresos',$modelCongreso->getCongresos());
        $modelCalidad = $this->getModel("EntidadCalidad");
        $view->edicion->valoraciones = $modelCalidad->getValoraciones($congreso, $orden); //poner valoraciones (entidad, criterios, indice)
        $view->entidadesCalidad = $modelCalidad->getEntidadesIndiceCalidad(); //lista de los nombres de las entidades de calidad => con el nombre obtendremos con ajax sus categorias
        foreach ($view->edicion->valoraciones as $valoracion) {
            $valoracion->categorias = $modelCalidad->getCategoriasEntidad($valoracion->nomEntidad);
            $valoracion->tipoCategoria = $modelCalidad->getEntidadCalidad($valoracion->nomEntidad)->tipoCategoria;
        }
        $view->uriPOST = $uriPOST;
        $view->display("editar");
    }
    
    function editEdicion_infoCongreso() {
        $nomCongreso = JRequest::getVar('id');
        $cid = JRequest::getVar( 'cid' , array() , '' , 'array' );
        JArrayHelper::toInteger($cid);
        if (count($cid) == 1) {
            $uriPOST = 'index.php?option=com_gestorcore&controller=congreso&task=infoCongreso&id=' . $nomCongreso;
            $this->editEdicion($cid[0], $nomCongreso, $uriPOST);
        }
        else {
            $this->infoCongreso();
        }
    }
    
    function editEdicion_infoEdicion() {
        $nomCongreso = JRequest::getVar('congreso');
        $orden = JRequest::getVar('orden');
        $uriPOST = 'index.php?option=com_gestorcore&controller=congreso&task=infoEdicion&orden=' . $orden . '&congreso=' . $nomCongreso;
        //echo $uriPOST;
        $this->editEdicion($orden, $nomCongreso, $uriPOST);
    }
    
    function cancelar_showCongreso() {
        $this->infoCongreso();
    }
    
    function cancelar_showEdicion() {
        $this->infoEdicion();
    }
    
    function nuevoCongreso() {
        $congreso = JRequest::getVar('nomEntidad');
        $this->setRedirect(JRoute::_('index.php?option=com_gestorcore&controller=congreso&task=infoCongreso&id=' . $congreso));
    }

    /********************************************************************************/
    function ver(){
        $view = $this->getView('vermensaje','html');
        $model= $this->getModel("mensaje");
        $mensaje = $model->getMensaje(JRequest::getInt("id"));
        $view->assignRef('mensaje',$mensaje);
        $view->display();
    }
    
    function editar(){
        //$this->setRedirect(JRoute::_('index.php?option=com_gestorcore&controller=gestorcore&task=edit&id=1', false));
        //JRequest::setVar('id', 1);
        $cid = JRequest::getVar( 'cid' , array() , 'post' , 'array' );
        $view = $this->getView('vermensaje','html');
        $model= $this->getModel("mensaje");
        //$mensaje = $model->getMensaje((JRequest::getVar("id")));
        $mensaje = $model->getMensaje($cid[0]);
        $view->assignRef('mensaje', $mensaje);
        //$view->mensaje = $mensaje;
        //$mensaje->titulo = "hola";
        //$mensaje->mensaje = "cuerpo";

        //$mesj = new ConsoleLog("log",$mensaje->);

        $view->display();

    }
    function nuevo(){
        //$mesj= new ConsoleLog("log","este es el mensaje");
        $view = $this->getView('vermensaje','html');
        $view->display("nuevo");
    }

    function guardar() {
        //$view =& $this->getView('mensaje','html');
        $model= $this->getModel("mensaje");
        $mensaje = $model->insertMensaje(JRequest::getVar("titulo"),JRequest::getVar("mensaje"));
        //$view->display();
        $this->listado();
    }

    function eliminar()
    {
        $cid = JRequest::getVar( 'cid' , array() , '' , 'array' );
        JArrayHelper::toInteger($cid);
        $model= $this->getModel("mensaje");
        if (count( $cid )) {
                $model->deleteMensaje($cid);
                $this->listado();
        }else{
                $this->listado();
        }  

    }
    
    
    /*******************AJAX************************/
    function getTopicosCongreso_ajax() {
        $document = JFactory::getDocument();
        $document->setMimeEncoding( 'application/json' );
        //print JRequest::getVar('format');
        $modelCongreso = $this->getModel("Congreso");
        $nomCongreso = JRequest::getVar('nomCongreso');
        $topicosFromCongreso = $modelCongreso->getTopicosFromCongreso($nomCongreso);
//        if ($nomCongreso == JRequest::getVar('congresoActual')) {
            $modelEdicion = $this->getModel("edicionCongreso");
            $modelEdicion->setCongreso(JRequest::getVar('congresoActual'));
            $topicosEdicion = $modelEdicion->getTopicosEdicion(JRequest::getVar('ordenEdicion'));
            foreach($topicosFromCongreso as $topCongreso) {
                $trobat = false;
                for($i = 0; $i < count($topicosEdicion) && !$trobat; $i++) {
                    if ($topCongreso->nomTopico == $topicosEdicion[$i]->nomTopico) {
                        $trobat = true;
                        $topicosEdicion[$i]->select = "pertCongreso";
                    }
                }
                $topCongreso->select = $trobat?"si":"no";
            }
//        }
//        else {
//            foreach($topicosFromCongreso as $element) {
//                $element->select = "no";
//            }
//        }
            
        for($i = 0; $i < count($topicosEdicion); $i++) {
            if (!isset($topicosEdicion[$i]->select)) {
                $topicosEdicion[$i]->select = "noPertCongreso";
                //cargar tb la descripcion en la tabla, haciendo join
                array_push($topicosFromCongreso, $topicosEdicion[$i]);
            }
        }
        $ultimaEdicion = $modelCongreso->getUltimaEdicionCongreso($nomCongreso);
        $ultimaEdicion->select = "ultimaEdicionC";
        array_push($topicosFromCongreso, $ultimaEdicion);
        echo json_encode($topicosFromCongreso);
    }
    
    function getTopicosCongreso() {
        $document = JFactory::getDocument();
        $document->setMimeEncoding( 'application/json' );
        $nomCongreso = JRequest::getVar('nomCongreso');
        $modelCongreso = $this->getModel('congreso');
        $topicosFromCongreso = $modelCongreso->getTopicosFromCongreso($nomCongreso);
        $ultimaEdicion = $modelCongreso->getUltimaEdicionCongreso($nomCongreso);
        $ultimaEdicion->select = "ultimaEdicionC";
        array_push($topicosFromCongreso, $ultimaEdicion);
        echo json_encode($topicosFromCongreso);
    }
    
    function getEditoriales_ajax() {
        $document = JFactory::getDocument();
        $document->setMimeEncoding( 'application/json' );
        $modelEditorial = $this->getModel("Editorial");
        echo json_encode($modelEditorial->getEditoriales());
    }
    
    function getNomEditoriales_ajax() {
        $document = JFactory::getDocument();
        $document->setMimeEncoding( 'application/json' );
        $modelEditorial = $this->getModel("Editorial");
        $array = array();
        foreach($modelEditorial->getEditoriales() as $editorial) {
            $array[$editorial->nombre] = $editorial->nombre;
        }
        echo json_encode($array);
    }
    
    function getNomCongresos_ajax() {
        $document = JFactory::getDocument();
        $document->setMimeEncoding( 'application/json' );
        $modelCongreso = $this->getModel("congreso");
        $array = array();
        foreach($modelCongreso->getCongresos() as $congreso) {
            $array[$congreso->nombre] = $congreso->nombre;
        }
        echo json_encode($array);
    }
    
    function postNuevoCongreso() {
        $document = JFactory::getDocument();
        $document->setMimeEncoding( 'application/json' );
        
        $nomEntidad = JRequest::getVar('nomEntidad');
        $modelCongreso = $this->getModel("Congreso");
        if ($modelCongreso->existsCongreso($nomEntidad)) { //ya existe un congreso con el mismo nombre
            echo json_encode(array("error" => "Error: Ya existe el congreso"));
            return;
        }
        
        $nomEditorial = JRequest::getVar('nomEditorial');
        if (JRequest::getVar('tipoEditorial') == "nuevaEditorial") { //damos de alta la editorial
            $modelEditorial = $this->getModel("Editorial");
            $siglasEditorial = JRequest::getVar('siglasEditorial');
            $linkEditorial = JRequest::getVar('linkEditorial');
            if ($modelEditorial->existsEditorial($nomEditorial)) {
                echo json_encode(array("error" => "Error: Ya existe una editorial con ese nombre"));
                return;
            }
            $modelEditorial->insertEditorial($nomEditorial, $siglasEditorial, $linkEditorial);
        }
        $acronimoEntidad = JRequest::getVar('acronimoEntidad');
        $linkEntidad = JRequest::getVar('linkEntidad');
        $periodicidadEntidad = JRequest::getVar('periodicidadEntidad');
        $modelCongreso->insertCongreso($nomEntidad, $acronimoEntidad, $linkEntidad, $periodicidadEntidad, $nomEditorial);
        
        //echo json_encode($modelCongreso->getCongresos());
        echo json_encode(array('nuevaEntidad' => $nomEntidad));
    }

    function postNuevosTopicosCongreso() {
        $document = JFactory::getDocument();
        $document->setMimeEncoding( 'application/json' );
        $listaNuevosTopicos = JRequest::getVar('nuevosTopicos', array(), '', 'array');
        $modelCongreso = $this->getModel("Congreso");
        $congreso = JRequest::getVar('nomCongreso');
        foreach ($listaNuevosTopicos as $topico) {
            $modelCongreso->insertTopicoCongreso($congreso, $topico);
        }
        echo json_encode($listaNuevosTopicos);
    }
    
    function postNuevosTopicosEdicion() {
        $document = JFactory::getDocument();
        $document->setMimeEncoding( 'application/json' );
        $listaNuevosTopicos = JRequest::getVar('nuevosTopicos', array(), '', 'array');
        $modelEdicion = $this->getModel('edicionCongreso');
        $congreso = JRequest::getVar('nomCongreso');
        $orden = JRequest::getVar('orden');
        foreach ($listaNuevosTopicos as $topico) {
            $modelEdicion->insertTopicoEdicion($orden, $congreso, $topico);
        }
        echo json_encode($listaNuevosTopicos);
    }
    
    function put_editarCongreso() {
        $modelCongreso = $this->getModel("Congreso");
        $nomCongresoActual = JRequest::getVar('congresoActual');
        $congreso = $modelCongreso->getCongreso($nomCongresoActual);
        $error = false;
        $cambioID = false;
        switch (JRequest::getVar('id')) {
            case "nomCongreso_listEd":  //Ojo: cambiamos el id
                $nuevoID = JRequest::getVar('value');
                if ($modelCongreso->existsCongreso($nuevoID)) {
                    echo ("Error: Ya existe una entidad con ese nombre");
                    $error = true;
                }else {
                    $congreso->nombre = $nuevoID;
                    $cambioID = true;
                }
                break;
            case "acronimoCongreso_listEd":
                $congreso->acronimo = JRequest::getVar('value');
                break;
            case "linkCongreso_listEd":
                $congreso->linkCongreso = JRequest::getVar('value');
                break;
            case "periodicidadCongreso_listEd":
                if (!ctype_digit(JRequest::getVar('value'))) {
                    echo ("Error: Sólo numeros enteror permitidos");
                    $error = true;
                }
                $congreso->periodicidad = JRequest::getVar('value');
                break;
            case "editorialCongreso_listEd":
                $congreso->nomEditorial = JRequest::getVar('value');
                break;
            default:
                echo ("Error en la actualización.");
                $error = true;
        }
        if (!$error) {
            $modelCongreso->updateCongreso($nomCongresoActual, $congreso->nombre, $congreso->acronimo, 
                                           $congreso->linkCongreso, $congreso->periodicidad, $congreso->nomEditorial);
            echo JRequest::getVar('value');
        }
        if ($cambioID) {$this->setRedirect(JRoute::_('index.php?option=com_gestorcore&controller=congreso&task=infoCongreso&id='.$congreso->nombre));}
    }
    
    function put_editarEdicion() {
        $modelEdicion = $this->getModel("edicionCongreso");
        $nomCongresoActual = JRequest::getVar('congresoActual');
        $ordenActual = JRequest::getVar('ordenActual');
        $modelEdicion->setCongreso($nomCongresoActual);
        $edicion = $modelEdicion->getEdicion($ordenActual);
        $error = false;
        $cambioID = false;
        switch (JRequest::getVar('id')) {
            case "_ordenEdicion":  //Ojo: cambiamos el id
                $nuevoOrden = JRequest::getVar('value');
                if ($modelEdicion->existsEdicion($nuevoOrden, $nomCongresoActual)) {
                    echo ("Error: Ya existe esta edición del congreso");
                    $error = true;
                }else {
                    $edicion->orden = $nuevoOrden;
                    $cambioID = true;
                }
                break;
            case "_congresoEdicion":  //Ojo: cambiamos el id
                $nuevoCongreso = JRequest::getVar('value');
                if ($modelEdicion->existsEdicion($ordenActual, $nuevoCongreso)) {
                    echo ("Error: Ya existe una edición de este congreso, cambia la edición antes");
                    $error = true;
                }else {
                    $edicion->nomCongreso = $nuevoCongreso;
                    $cambioID = true;
                }
                break;
            case "_lugarEdicion":
                $edicion->lugar = JRequest::getVar('value');
                break;
            case "_linkEdicion":
                $edicion->linkEdicion = JRequest::getVar('value');
                break;
            case "_limRecepcion":
                $edicion->limRecepcion = $this->formatToTable(JRequest::getVar('value'));
                break;
            case "_fechaDefinitiva":
                $edicion->fechaDefinitiva = $this->formatToTable(JRequest::getVar('value'));
                break;
            case "_fechaInicio":
                $edicion->fechaInicio = $this->formatToTable(JRequest::getVar('value'));
                break;
            case "_fechaFin":
                $edicion->fechaFin = $this->formatToTable(JRequest::getVar('value'));
                break;
            default:
                echo ("Error en la actualización.");
                $error = true;
        }
        if (!$error) {
            $modelEdicion->updateEdicion($ordenActual, $nomCongresoActual, $edicion->orden, $edicion->nomCongreso, $edicion->lugar, 
                                         $edicion->linkEdicion, $edicion->limRecepcion, $edicion->fechaDefinitiva, $edicion->fechaInicio, $edicion->fechaFin);
            echo JRequest::getVar('value');
        }
        if ($cambioID) {$this->setRedirect(JRoute::_('index.php?option=com_gestorcore&controller=congreso&task=infoEdicion&orden='.
                                                     $edicion->orden.'&congreso='.$edicion->nomCongreso));}
    }
    
    
    function put_EliminarTopicosCongreso() {
        $document = JFactory::getDocument();
        $document->setMimeEncoding( 'application/json' );
        $listaTopicosEliminar = JRequest::getVar('topicosEliminar', array(), '', 'array');
        $modelCongreso = $this->getModel("Congreso");
        $congreso = JRequest::getVar('nomCongreso');
        foreach ($listaTopicosEliminar as $topico) {
            $modelCongreso->deleteTopicoCongreso($congreso, $topico);
        }
        echo json_encode($listaTopicosEliminar);
    }
    
    function put_EliminarTopicosEdicion() {
        $document = JFactory::getDocument();
        $document->setMimeEncoding( 'application/json' );
        $listaTopicosEliminar = JRequest::getVar('topicosEliminar', array(), '', 'array');
        $modelEdicion = $this->getModel('edicionCongreso');
        $congreso = JRequest::getVar('nomCongreso');
        $orden = JRequest::getVar('orden');
        foreach ($listaTopicosEliminar as $topico) {
            $modelEdicion->deleteTopicoEdicion($orden, $congreso, $topico);
        }
        echo json_encode($listaTopicosEliminar);
    }
    
    function getTopicosFiltrado_ajax() {
        $document = JFactory::getDocument();
        $document->setMimeEncoding( 'application/json' );
        $modelTopico = $this->getModel('Topico');
        $listaTopicos = $modelTopico->getTopicosFiltrados(JRequest::getVar('nomCongreso'));
        echo json_encode($listaTopicos);
    }
    
    function getTopicosFiltradoEdicion() {
        $document = JFactory::getDocument();
        $document->setMimeEncoding( 'application/json' );
        $modelTopico = $this->getModel('Topico');
        $listaTopicos = $modelTopico->getTopicosFiltradosEdicion(JRequest::getVar('nomCongreso'), JRequest::getVar('orden'));
        echo json_encode($listaTopicos);
    }
    
    function get_TopicosSimilaresCongreso() {
        $document = JFactory::getDocument();
        $document->setMimeEncoding( 'application/json' );
        $modelTopico = $this->getModel('Topico');
        $topicosSimilares = $modelTopico->getTopicosSimilares_NoCongreso(JRequest::getVar('topicoSimilar'), 
                                                                         JRequest::getVar('nomCongreso'));
        echo json_encode($topicosSimilares);
    }
    
    function get_TopicosSimilaresEdicion() {
        $document = JFactory::getDocument();
        $document->setMimeEncoding( 'application/json' );
        $modelTopico = $this->getModel('Topico');
        $topicosSimilares = $modelTopico->getTopicosSimilares_NoEdicion(JRequest::getVar('topicoSimilar'), 
                                                                         JRequest::getVar('nomCongreso'), JRequest::getVar('orden'));
        echo json_encode($topicosSimilares);
    }
    
    function get_TopicosCongreso_noEdicion() {
        $document = JFactory::getDocument();
        $document->setMimeEncoding( 'application/json' );
        $modelTopico = $this->getModel('Topico');
        $topicosCongreso = $modelTopico->getTopicosCongreso_NoEdicion(JRequest::getVar('nomCongreso'), JRequest::getVar('orden'));
        echo json_encode($topicosCongreso);
    }
    
    function getCategoriasEntidadCalidad() {
        $document = JFactory::getDocument();
        $document->setMimeEncoding( 'application/json' );
        $modelCalidad = $this->getModel("EntidadCalidad");
        $categorias = $modelCalidad->getCategoriasEntidad(JRequest::getVar('entidad'));
        $res = array("tipo" => $modelCalidad->getEntidadCalidad(JRequest::getVar('entidad'))->tipoCategoria);
        $res["lista"] = $categorias;
        echo json_encode($res);
    }
    
    function display($cachable = false, $urlparams= false)
    {	
        // Load the component helper.
//        require_once JPATH_COMPONENT.'/helpers/gescore.php';

        // Load the submenu.
//        $view = JRequest::getCmd('task');
//        HelloHelper::addSubmenu($view);
        parent::display();
    }
}

?>