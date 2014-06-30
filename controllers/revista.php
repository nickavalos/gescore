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

 
class GestorcoreControllerRevista extends JController
{
    var $model_revista = 'revista';
    var $model_issue = 'issueRevista';
    var $model_topico = 'topico';
    var $model_entidadCalidad = 'entidadCalidad';
    var $model_editorial = 'editorial';
    
    var $view_revistas = 'revistas';
    var $view_revista = 'revista';
    var $view_issue = 'issueRevista';
    
    var $type_html = 'html';
    
    var $separadorVolumenNumero = '#';
    var $id_specialIssue = 'specialIssue';
    
    function listRevistas(){
        $view = $this->getView($this->view_revistas, $this->type_html);
        $model= $this->getModel($this->model_revista);
        //$congresos = $model->getCongresos();
        $revistas = $model->getItems();
        $view->assignRef('revistas',$revistas);
        $view->pagination = $model->getPagination();
        $view->display();
    }

    function infoRevista() {  //getRevista
        $view = $this->getView($this->view_revista, $this->type_html);
        $modelIssue= $this->getModel($this->model_issue);
        $issnRevista = JRequest::getVar('id');
        $modelIssue->setRevista($issnRevista);
        $issues = $modelIssue->getItems();
        $modelRevista = $this->getModel($this->model_revista);
        $view->revista = $modelRevista->getRevista($issnRevista);
        $view->revista->topicos = $modelRevista->getTopicosFromRevista($issnRevista);
        $view->assignRef('issues', $issues);
        $view->paginacion = $modelIssue->getPagination();
        $view->state = $modelIssue->getState();
        $view->display();
    }
    
    function infoIssue() {
        $volumen = JRequest::getVar('volumen');
        $numero = JRequest::getVar('numero');
        $revista = JRequest::getVar('revista');
        
        $modelIssue = $this->getModel($this->model_issue);
        $modelIssue->setRevista($revista);
        
        $view = $this->getView($this->view_issue, $this->type_html);
        $view->issue = $modelIssue->getIssue($volumen, $numero);
        $view->issue->topicos = $modelIssue->getTopicosIssue($volumen, $numero);
        $modelValoracion = $this->getModel($this->model_entidadCalidad);
        $view->issue->valoraciones = $modelValoracion->getValoracionesIssue($revista, $volumen, $numero);
        
        $modelRevista = $this->getModel($this->model_revista);
        $view->nomRevista = $modelRevista->getRevista($revista)->nombre;
        $view->display();
    }
    
    function _nuevaIssue($identRevista) { 
        $modelRevista = $this->getModel($this->model_revista);
        $view = $this->getView($this->view_revista, $this->type_html);
        $view->issnRevista = $identRevista;
        $view->volumenes = $modelRevista->getVolumenes($identRevista);
        $view->topicosFromRevista = $modelRevista->getTopicosFromRevista($identRevista);
        //$view->ultimaIssueRevista = $modelRevista->getUltimaIssueRevista($identRevista);
        $view->listaRevistas = $modelRevista->getRevistas();
        $modelCalidad = $this->getModel($this->model_entidadCalidad);
        $view->entidadesCalidad = $modelCalidad->getEntidadesIndiceCalidad();
        $view->display("nuevaIssueRevista");
    }
    
    function newIssueRevista() { //llamada desde revista
        $this->_nuevaIssue(JRequest::getVar('id'));
    }
    
    function nuevaIssue() {
        $this->_nuevaIssue(-1);
    }
    
    function deleteRevista() {
        $cid = JRequest::getVar( 'cid' , array() , '' , 'array' );
        $model = $this->getModel($this->model_revista);
        foreach($cid as $issnRevista) {
            $nomEditorial = $model->getRevista($issnRevista)->nomEditorial;
            $model->deleteRevista($issnRevista);
            if ($nomEditorial != null) {
                $modelEditorial = $this->getModel($this->model_editorial);
                $numeroPublicaciones_tipoCongreso = $modelEditorial->getNumEntidadesPublicadoras($nomEditorial, 'congreso'); 
                $numeroPublicaciones_tipoRevista = $modelEditorial->getNumEntidadesPublicadoras($nomEditorial, 'revista');
                if (($numeroPublicaciones_tipoCongreso + $numeroPublicaciones_tipoRevista) == 0) {
                    $modelEditorial->deleteEditorial($nomEditorial);
                }
            }
            
        }
        $this->setRedirect(JRoute::_('index.php?option=com_gestorcore&controller=revista&task=listRevistas')); 
    }
    
    function deleteIssue() {
        $cids = JRequest::getVar( 'cid' , array() , '' , 'array' );
        
        $modelIssue = $this->getModel($this->model_issue);
        $modelIssue->setRevista(JRequest::getVar('id'));
        
        foreach ($cids as $cid) {
            $volumenNumero = explode($this->separadorVolumenNumero, $cid);
            $modelIssue->deleteIssue($volumenNumero[0], $volumenNumero[1]);
        }
        $this->infoRevista();
    }
    
    function _saveNuevaIssue() {
        $issnRevista = JRequest::getVar("selectorRevista");
        $modelIssue = $this->getModel($this->model_issue);
        $modelIssue->setRevista($issnRevista);
        $temaPrincipalEspecialIssue = null;
        $volumen = JRequest::getVar("selectorVolumenRevista");
        $numeroIssue = JRequest::getVar("numeroIssue");
        if ($volumen == '-1') {  //nuevo volumen
            //if (JRequest::getVar('nuevoVolumen') == "") {return;} // si hay nuevo volumen este no puede ser vacio
            $volumen = JRequest::getVar('nuevoVolumen');
        }
        if(JRequest::getVar('radioTipoEdicion') == 'edicionEspecial') { //check special Issue
            $volumen = $this->id_specialIssue;
            $numeroIssue = $modelIssue->getUltimoNumeroVolumenIssue($issnRevista, $volumen)->max + 1; //numero es igual al numero Especial anterior + 1
            $temaPrincipalEspecialIssue = JRequest::getVar("temaPrincipal");
            //si no existe este tópico lo creamos con descripción vacia
            $modelIssue->insertTopico($temaPrincipalEspecialIssue, "");
        }
        $limRecepcion = $this->formatToTable(JRequest::getVar("limRecepcion"));
        $fechaDefinitiva = $this->formatToTable(JRequest::getVar("fechaDefinitiva"));
        $fechaPublicacion = $this->formatToTable(JRequest::getVar("fechaPublicacion"));
        
        $modelIssue->insertIssue($volumen, $numeroIssue, $issnRevista, $limRecepcion, $fechaDefinitiva, $fechaPublicacion, $temaPrincipalEspecialIssue);
        
        /*topicos añadidos de la Revista*/
        $modelIssue->insertListTopicosIssue($volumen, $numeroIssue, $issnRevista, JRequest::getVar("topicosFromRevista", array(), '', 'array'));
        
        /* otros tópicos*/
        //if (JRequest::getVar('checkOtrosTopicos', 'off') == 'on') {
            $listOtrosTopicos = JRequest::getVar("listTopicosSeleccionados", array(), '', 'array');
            $modelIssue->insertListTopicosIssue($volumen, $numeroIssue, $issnRevista, $listOtrosTopicos);
        //}
        
        /*Check de nuevos topicos, e insertamos*/
        $arrayTopicos = JRequest::getVar("nuevoTopico", array(), '', 'array');
        $arrayDescripcionTopicos = JRequest::getVar("descripcionNuevoTopico", array(), '', 'array');
        //if (JRequest::getVar("checkNuevosTopicos", "off") == "on") { //nuevos topicos
            for ($i = 0; $i < count($arrayTopicos); $i++ ) {
                if ($arrayTopicos[$i] != "") {
                    $modelIssue->insertTopico($arrayTopicos[$i], $arrayDescripcionTopicos[$i]);
                    $modelIssue->insertTopicoIssue($volumen, $numeroIssue, $issnRevista, $arrayTopicos[$i]);
                }
            }
        //}
        //valoraciones
        $modelCalidad = $this->getModel($this->model_entidadCalidad);
        
        $listaEntidad = JRequest::getVar('selectorEntidadCalidad', array(), '', 'array');
        $listaValoraciones = JRequest::getVar('selectorCategoriaCalidad', array(), '', 'array');
        for ($i = 0; $i < count($listaEntidad); $i++) {
            if ($listaEntidad[$i] != "") { //vacio
                $modelCalidad->insertValoracionIssue($listaEntidad[$i], $volumen, $numeroIssue, $issnRevista, $listaValoraciones[$i]);
            }
        }
        return array('volumen' => $volumen, 'numero' => $numeroIssue, 'issnRevista' => $issnRevista);
    }
    
    function saveNuevaIssueRevista() {
        $this->_saveNuevaIssue();
        $this->infoRevista();
    }
    
    function saveNuevaIssue() { //desde menu
        $res = $this->_saveNuevaIssue();
        $this->setRedirect(JRoute::_('index.php?option=com_gestorcore&controller=revista&task=infoIssue'
                                    . '&volumen=' . $res['volumen'] . '&numero=' . $res['numero'] . '&revista=' . $res['issnRevista']));
    }
    
    
    function editIssue($volumen, $numero, $revista, $uriPOST) {
        $view = $this->getView($this->model_revista, $this->type_html);
        $view->issnRevista = $revista;
        
        $modelRevista = $this->getModel($this->model_revista);
        $view->listaRevistas = $modelRevista->getRevistas();
        $view->volumenes = $modelRevista->getVolumenes_noSpecialIssue($revista);
        $view->topicosFromRevista = $modelRevista->getTopicosFromRevista($revista);
        
        $modelIssue = $this->getModel($this->model_issue);
        $modelIssue->setRevista($revista);
        $view->ultimoNumeroVolumen = $modelIssue->getUltimoNumeroVolumenIssue($revista, $volumen);
        $view->issue = $modelIssue->getIssue($volumen, $numero);
        $view->issue->topicos = $modelIssue->getTopicosIssue($volumen, $numero);
        //$view->assignRef('listaCongresos',$modelCongreso->getCongresos());
        
        $modelCalidad = $this->getModel($this->model_entidadCalidad);
        $view->issue->valoraciones = $modelCalidad->getValoracionesIssue($revista, $volumen, $numero); //poner valoraciones (entidad, criterios, indice)
        $view->entidadesCalidad = $modelCalidad->getEntidadesIndiceCalidad(); //lista de los nombres de las entidades de calidad => con el nombre obtendremos con ajax sus categorias
        foreach ($view->issue->valoraciones as $valoracion) {
            $valoracion->categorias = $modelCalidad->getCategoriasEntidad($valoracion->nomEntidad);
            $valoracion->tipoCategoria = $modelCalidad->getEntidadCalidad($valoracion->nomEntidad)->tipoCategoria;
        }
        $view->uriPOST = $uriPOST;
        $view->display("editar");
    }
    
    function editIssue_infoRevista() {
        $issnRevista = JRequest::getVar('id');
        $cid = JRequest::getVar( 'cid' , array() , '' , 'array' );
        if (count($cid) == 1) {
            $volumenNumero = explode($this->separadorVolumenNumero, $cid[0]);
            $uriPOST = 'index.php?option=com_gestorcore&controller=revista&task=infoRevista&id=' . $issnRevista;
            $this->editIssue($volumenNumero[0], $volumenNumero[1], $issnRevista, $uriPOST);
        }
        else {
            $this->infoRevista();
        }
    }
    
    function editIssue_infoIssue() {
        $revista = JRequest::getVar('revista');
        $volumen = JRequest::getVar('volumen');
        $numero = JRequest::getVar('numero');
        $uriPOST = 'index.php?option=com_gestorcore&controller=revista&task=infoIssue&volumen=' . $volumen . 
                   '&numero=' . $numero . '&revista=' . $revista;
        //echo $uriPOST;
        $this->editIssue($volumen, $numero, $revista, $uriPOST);
    }
    
    function saveIssueRevista() {
        $modelIssue = $this->getModel($this->model_issue);
        $issnRevista = JRequest::getVar("selectorRevista");
        $modelIssue->setRevista($issnRevista);
        
        $issnAnterior = JRequest::getVar("issnRevistaActual");
        $volumenAnterior = JRequest::getVar("volumenIssueActual");
        $numeroIssueAnterior = JRequest::getVar("numeroIssueActual");
        
        $volumen = JRequest::getVar("selectorVolumenRevista");
        $numeroIssue = JRequest::getVar("numeroIssue");
        $temaPrincipalEspecialIssue = null;
        if ($volumen == '-1') {  //nuevo volumen
            //if (JRequest::getVar('nuevoVolumen') == "") {return;} // si hay nuevo volumen este no puede ser vacio
            $volumen = JRequest::getVar('nuevoVolumen');
        }
        if(JRequest::getVar('radioTipoEdicion') == 'edicionEspecial') { //check special Issue
            $volumen = $this->id_specialIssue;
            //numero de Issue depende si el volumen anterior era ya un specialIssue, en caso afirmativo, el numero no cambia, en caso contrario es el ultimo + 1
            $numeroIssue = $volumenAnterior == $this->id_specialIssue? $numeroIssueAnterior:$modelIssue->getUltimoNumeroVolumenIssue($issnRevista, $volumen)->max + 1;
            $temaPrincipalEspecialIssue = JRequest::getVar("temaPrincipal");
            //si no existe este tópico lo creamos con descripción vacia
            $modelIssue->insertTopico($temaPrincipalEspecialIssue, "");
        }
        $limRecepcion = $this->formatToTable(JRequest::getVar("limRecepcion"));
        $fechaDefinitiva = $this->formatToTable(JRequest::getVar("fechaDefinitiva"));
        $fechaPublicacion = $this->formatToTable(JRequest::getVar("fechaPublicacion"));
        
        //echo $limRecepcion . " " .$fechaDefinitiva . " " . $fechaPublicacion;
        $modelIssue->updateIssue($volumenAnterior, $numeroIssueAnterior, $issnAnterior, 
                                 $volumen, $numeroIssue, $issnRevista, $limRecepcion, $fechaDefinitiva, $fechaPublicacion, $temaPrincipalEspecialIssue);
        
        //eliminar anteriores topicos de la issue
        foreach ($modelIssue->getTopicosIssue($volumen, $numeroIssue) as $topicoIssue) {
            $modelIssue->deleteTopicoIssue($volumen, $numeroIssue, $topicoIssue->nomTopico);
        }
        
        /*topicos añadidos de la Revista*/
        $modelIssue->insertListTopicosIssue($volumen, $numeroIssue, $issnRevista, JRequest::getVar("topicosFromRevista", array(), '', 'array'));
        
        /* otros tópicos*/
        $modelIssue->insertListTopicosIssue($volumen, $numeroIssue, $issnRevista, JRequest::getVar("listTopicosSeleccionados", array(), '', 'array'));
        
        /*Check de nuevos topicos, e insertamos*/
        $arrayTopicos = JRequest::getVar("nuevoTopico", array(), '', 'array');
        $arrayDescripcionTopicos = JRequest::getVar("descripcionNuevoTopico", array(), '', 'array');
        //if (JRequest::getVar("checkNuevosTopicos", "off") == "on") { //nuevos topicos
            for ($i = 0; $i < count($arrayTopicos); $i++ ) {
                if ($arrayTopicos[$i] != "") {
                    $modelIssue->insertTopico($arrayTopicos[$i], $arrayDescripcionTopicos[$i]);
                    $modelIssue->insertTopicoIssue($volumen, $numeroIssue, $issnRevista, $arrayTopicos[$i]);
                }
            }
        //}
        //valoraciones
        $modelCalidad = $this->getModel($this->model_entidadCalidad);
        foreach($modelCalidad->getValoracionesIssue($issnRevista, $volumen, $numeroIssue) as $valoracion) {
            $modelCalidad->deleteValoracionIssue($valoracion->nomEntidad, $volumen, $numeroIssue, $issnRevista);
        }
        
        $listaEntidad = JRequest::getVar('selectorEntidadCalidad', array(), '', 'array');
        $listaValoraciones = JRequest::getVar('selectorCategoriaCalidad', array(), '', 'array');
        for ($i = 0; $i < count($listaEntidad); $i++) {
            if ($listaEntidad[$i] != "") { //vacio
                $modelCalidad->insertValoracionIssue($listaEntidad[$i], $volumen, $numeroIssue, $issnRevista, $listaValoraciones[$i]);
            }
        }
        return array('volumen' => $volumen, 'numero' => $numeroIssue, 'revista' => $issnRevista);
    }
    
    function saveIssue_showRevista() {
        $this->saveIssueRevista();
        $this->infoRevista(); 
    }
    
    function saveIssue_showIssue() {
        $res = $this->saveIssueRevista();
        $this->setRedirect(JRoute::_('index.php?option=com_gestorcore&controller=revista&task=infoIssue&volumen=' . $res['volumen'] . 
                                     '&numero=' . $res['numero'] . '&revista=' . $res['revista'])); 
    }
    
    function cancelar_showRevista() {
        $this->infoRevista();
    }
    
    function cancelar_showIssue() {
        $this->infoIssue();
    }
    
    function nuevaRevista() {
        $revista = JRequest::getVar('issnEntidad');
        $this->setRedirect(JRoute::_('index.php?option=com_gestorcore&controller=revista&task=infoRevista&id=' . $revista));
    }

    
    function formatToTable($dateString) {
        return date('Ymd', strtotime(str_replace('/', '-', $dateString)));    
    }
    
    /************************************AJAX***************************************/
    function getTopicosRevista_complete() {
        $document = JFactory::getDocument();
        $document->setMimeEncoding( 'application/json' );
        $revistaAnterior = JRequest::getVar('revistaActual');
        $volumenAnterior = JRequest::getVar('volumenActual');
        $numeroAnterior = JRequest::getVar('numeroActual');
        
        $issnRevista = JRequest::getVar('issnRevista');
        $modelRevista = $this->getModel($this->model_revista);
        $topicosFromRevista = $modelRevista->getTopicosFromRevista($issnRevista);
        
        $modelIssue = $this->getModel($this->model_issue);
        $modelIssue->setRevista($revistaAnterior);
        $topicosIssueAnt = $modelIssue->getTopicosIssue($volumenAnterior, $numeroAnterior);
        foreach($topicosFromRevista as $topRevista) {
            $trobat = false;
            for($i = 0; $i < count($topicosIssueAnt) && !$trobat; $i++) {
                if ($topRevista->nomTopico == $topicosIssueAnt[$i]->nomTopico) {
                    $trobat = true;
                    $topicosIssueAnt[$i]->typeData = "pertRevista";
                }
            }
            $topRevista->typeData = $trobat?"si":"no";
        }   
        for($i = 0; $i < count($topicosIssueAnt); $i++) {
            if (!isset($topicosIssueAnt[$i]->typeData)) {
                $topicosIssueAnt[$i]->typeData = "noPertRevista";
                //cargar tb la descripcion en la tabla, haciendo join
                array_push($topicosFromRevista, $topicosIssueAnt[$i]);
            }
        }
        $volumenes = $modelRevista->getVolumenes($issnRevista);
        foreach ($volumenes as $volumen) {
            $volumen->typeData = 'volumen';
            array_push($topicosFromRevista, $volumen);
        }
        
        echo json_encode($topicosFromRevista);
    }
    
    
    function getTopicosRevista() {
        $document = JFactory::getDocument();
        $document->setMimeEncoding( 'application/json' );
        $issnRevista = JRequest::getVar('issnRevista');
        $modelRevista = $this->getModel($this->model_revista);
        $topicosFromRevista = $modelRevista->getTopicosFromRevista($issnRevista);
        $volumenes = $modelRevista->getVolumenes($issnRevista);
        foreach ($volumenes as $volumen) {
            $volumen->typeData = 'volumen';
            array_push($topicosFromRevista, $volumen);
        }
        echo json_encode($topicosFromRevista);
    }
    
    function getUltimoNumeroVolumen() {
        $document = JFactory::getDocument();
        $document->setMimeEncoding( 'application/json' );
        $issnRevista = JRequest::getVar('issnRevista');
        $volumen = JRequest::getVar('volumen');
        $modelIssue = $this->getModel($this->model_issue);
        echo json_encode(array("numero" => $modelIssue->getUltimoNumeroVolumenIssue($issnRevista, $volumen)->max));
    }
    
    function put_editarRevista() {
        $modelRevista = $this->getModel($this->model_revista);
        $issnRevistaActual = JRequest::getVar('revistaActual');
        $revista = $modelRevista->getRevista($issnRevistaActual);
        $error = false;
        $cambioID = false;
        switch (JRequest::getVar('id')) {
            case "_issnRevista":  //Ojo: cambiamos el id
                $nuevoIssn = JRequest::getVar('value');
                if ($modelRevista->existsRevista($nuevoIssn)) {
                    echo ("Error: Ya existe una revista con ese issn");
                    $error = true;
                }else {
                    $revista->issn = $nuevoIssn;
                    $cambioID = true;
                }
                break;
            case "_nombreRevista":
                $revista->nombre = JRequest::getVar('value');
                break;
            case "_acronimoRevista":
                $revista->acronimo = JRequest::getVar('value');
                break;
            case "_linkRevista":
                $revista->linkRevista = JRequest::getVar('value');
                break;
            case "_periodicidadRevista":
                if (!ctype_digit(JRequest::getVar('value'))) {
                    echo ("Error: Sólo numeros enteror permitidos");
                    $error = true;
                }
                $revista->periodicidad = JRequest::getVar('value');
                break;
            case "_editorialRevista":
                $revista->nomEditorial = JRequest::getVar('value');
                break;
            default:
                echo ("Error en la actualización.");
                $error = true;
        }
        if (!$error) {
            $modelRevista->updateRevista($issnRevistaActual, $revista->issn, $revista->nombre, $revista->acronimo, 
                                           $revista->linkRevista, $revista->periodicidad, $revista->nomEditorial);
            echo JRequest::getVar('value');
        }
        if ($cambioID) {$this->setRedirect(JRoute::_('index.php?option=com_gestorcore&controller=revista&task=infoRevista&id='.$revista->issn));}
    }
    
    function put_editarIssue() {
        $modelIssue = $this->getModel($this->model_issue);
        $issnRevistaActual = JRequest::getVar('revistaActual');
        $volumenActual = JRequest::getVar('volumenActual');
        $numeroActual = JRequest::getVar('numeroActual');
        $modelIssue->setRevista($issnRevistaActual);
        $issue = $modelIssue->getIssue($volumenActual, $numeroActual);
        $error = false;
        $cambioID = false;
        switch (JRequest::getVar('id')) {
            case "_revistaIssue":  //Ojo: cambiamos el id
                $nuevoIssnRev = JRequest::getVar('value');
                if ($modelIssue->existsIssue($volumenActual, $numeroActual, $nuevoIssnRev)) {
                    echo ("Error: Ya existe una issue con este volumen y numero de la revista");
                    $error = true;
                }else {
                    $issue->issnRevista = $nuevoIssnRev;
                    $cambioID = true;
                }
                break;
            case "_volumenIssue":  //Ojo: cambiamos el id
                $nuevoVolumen = JRequest::getVar('value');
                if ($modelIssue->existsIssue($nuevoVolumen, $numeroActual, $issnRevistaActual)) {
                    echo ("Error: Ya existe una issue con este volumen y número, cambia el número antes");
                    $error = true;
                }else {
                    $issue->volumen = $nuevoVolumen;
                    $cambioID = true;
                }
                break;
            case "_numeroIssue":  //Ojo: cambiamos el id
                $nuevoNumero = JRequest::getVar('value');
                if ($modelIssue->existsIssue($volumenActual, $nuevoNumero, $issnRevistaActual)) {
                    echo ("Error: Ya existe una issue con este volumen y número");
                    $error = true;
                }else {
                    $issue->numero = $nuevoNumero;
                    $cambioID = true;
                }
                break;
            case "_limRecepcion":
                $issue->limRecepcion = $this->formatToTable(JRequest::getVar('value'));
                break;
            case "_fechaDefinitiva":
                $issue->fechaDefinitiva = $this->formatToTable(JRequest::getVar('value'));
                break;
            case "_fechaPublicacion":
                $issue->fechaPublicacion = $this->formatToTable(JRequest::getVar('value'));
                break;
            default:
                echo ("Error en la actualización.");
                $error = true;
        }
        if (!$error) {
            $modelIssue->updateIssue($volumenActual, $numeroActual, $issnRevistaActual, $issue->volumen, $issue->numero, $issue->issnRevista, 
                                     $issue->limRecepcion, $issue->fechaDefinitiva, $issue->fechaPublicacion);
            echo JRequest::getVar('value');
        }
        if ($cambioID) {$this->setRedirect(JRoute::_('index.php?option=com_gestorcore&controller=revista&task=infoIssue&volumen='.
                                                     $issue->volumen.'&numero='.$issue->numero.'&revista='.$issue->issnRevista));}
    }
    
    function getTopicosFiltrado_revista() {
        $document = JFactory::getDocument();
        $document->setMimeEncoding( 'application/json' );
        $modelTopico = $this->getModel($this->model_topico);
        $listaTopicos = $modelTopico->getTopicosFiltrados_revista(JRequest::getVar('issnRevista'));
        echo json_encode($listaTopicos);
    }
    
    function getTopicosFiltradoIssue() {
        $document = JFactory::getDocument();
        $document->setMimeEncoding( 'application/json' );
        $modelTopico = $this->getModel($this->model_topico);
        $listaTopicos = $modelTopico->getTopicosFiltradosIssue(JRequest::getVar('issnRevista'), JRequest::getVar('volumen'), JRequest::getVar('numero'));
        echo json_encode($listaTopicos);
    }
    
    function get_topicosSimilaresRevista() {
        $document = JFactory::getDocument();
        $document->setMimeEncoding( 'application/json' );
        $modelTopico = $this->getModel($this->model_topico);
        $topicosSimilares = $modelTopico->getTopicosSimilares_NoRevista(JRequest::getVar('topicoSimilar'), 
                                                                         JRequest::getVar('issnRevista'));
        echo json_encode($topicosSimilares);
    }
    
    function get_topicosSimilaresIssue() {
        $document = JFactory::getDocument();
        $document->setMimeEncoding( 'application/json' );
        $modelTopico = $this->getModel($this->model_topico);
        $topicosSimilares = $modelTopico->getTopicosSimilares_NoIssue(JRequest::getVar('topicoSimilar'), JRequest::getVar('issnRevista'),
                                                                      JRequest::getVar('volumen'), JRequest::getVar('numero'));
        echo json_encode($topicosSimilares);
    }
    
    function getNomTopicos_jsonp() {
        $document = JFactory::getDocument();
        $document->setMimeEncoding( 'application/json' );
        $modelTopico = $this->getModel($this->model_topico);
        $coincidenciasTopico = $modelTopico->buscaCadenaNombre(JRequest::getVar('name_startsWith'));
        foreach ($coincidenciasTopico as $c) {
            $c->category = "Nombre";
        }
        if (JRequest::getVar('incluirDescripcion') == 'true') {
            $coincidenciasDescripcion = $modelTopico->buscaCadenaDescripcion(JRequest::getVar('name_startsWith'));
            foreach ($coincidenciasDescripcion as $c) {
                $c->category = "Descripcion";
                array_push($coincidenciasTopico, $c);
            }
        }
        //echo $_GET['callback'] . '(' . json_encode($array) . ')';
        echo json_encode($coincidenciasTopico);
        
    }
    
    function put_eliminarTopicosRevista() {
        $document = JFactory::getDocument();
        $document->setMimeEncoding( 'application/json' );
        $listaTopicosEliminar = JRequest::getVar('topicosEliminar', array(), '', 'array');
        $modelRevista = $this->getModel($this->model_revista);
        $issnRevista = JRequest::getVar('issnRevista');
        foreach ($listaTopicosEliminar as $topico) {
            $modelRevista->deleteTopicoRevista($issnRevista, $topico);
        }
        echo json_encode($listaTopicosEliminar);
    }
    
    function put_eliminarTopicosIssue() {
        $document = JFactory::getDocument();
        $document->setMimeEncoding( 'application/json' );
        $listaTopicosEliminar = JRequest::getVar('topicosEliminar', array(), '', 'array');
        
        $modelIssue = $this->getModel($this->model_issue);
        $modelIssue->setRevista(JRequest::getVar('issnRevista'));
        $volumen = JRequest::getVar('volumen');
        $numero = JRequest::getVar('numero');
        foreach ($listaTopicosEliminar as $topico) {
            $modelIssue->deleteTopicoIssue($volumen, $numero, $topico);
        }
        echo json_encode($listaTopicosEliminar);
    }
    
    function postNuevosTopicosRevista() {
        $document = JFactory::getDocument();
        $document->setMimeEncoding( 'application/json' );
        $listaNuevosTopicos = JRequest::getVar('nuevosTopicos', array(), '', 'array');
        $modelRevista = $this->getModel($this->model_revista);
        $revista = JRequest::getVar('issnRevista');
        foreach ($listaNuevosTopicos as $topico) {
            $modelRevista->insertTopicoRevista($revista, $topico);
        }
        echo json_encode($listaNuevosTopicos);
    }
    
    function postNuevaRevista() {
        $document = JFactory::getDocument();
        $document->setMimeEncoding( 'application/json' );
        
        $issnRevista = JRequest::getVar('issnEntidad');
        $nomEntidad = JRequest::getVar('nomEntidad');
        $modelRevista = $this->getModel($this->model_revista);
        if ($modelRevista->existsRevista($issnRevista)) { //ya existe una revista con el mismo nombre
            echo json_encode(array("error" => "Error: Ya existe la revista"));
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
        $modelRevista->insertRevista($issnRevista, $nomEntidad, $acronimoEntidad, $linkEntidad, $periodicidadEntidad, $nomEditorial);
        
        //echo json_encode($modelCongreso->getCongresos());
        echo json_encode(array('nuevaEntidad' => $issnRevista));
    }
    
    function postNuevosTopicosIssue() {
        $document = JFactory::getDocument();
        $document->setMimeEncoding( 'application/json' );
        $listaNuevosTopicos = JRequest::getVar('nuevosTopicos', array(), '', 'array');
        $modelIssue = $this->getModel($this->model_issue);
        $revista = JRequest::getVar('issnRevista');
        $volumen = JRequest::getVar('volumen');
        $numero = JRequest::getVar('numero');
        foreach ($listaNuevosTopicos as $topico) {
            $modelIssue->insertTopicoIssue($volumen, $numero, $revista, $topico);
        }
        echo json_encode($listaNuevosTopicos);
    }
    
    function getRevistas_autocomplete() {
        $document = JFactory::getDocument();
        $document->setMimeEncoding( 'application/json' );
        $modelRevista = $this->getModel($this->model_revista);
        echo json_encode($modelRevista->buscaCadena(JRequest::getVar('term')));
    }
   
    function get_TopicosRevista_noIssue() {
        $document = JFactory::getDocument();
        $document->setMimeEncoding( 'application/json' );
        $modelTopico = $this->getModel($this->model_topico);
        $topicosRevista = $modelTopico->getTopicosRevista_noIssue(JRequest::getVar('issnRevista'), JRequest::getVar('volumen'), 
                                                                   JRequest::getVar('numero'));
        echo json_encode($topicosRevista);
    }
    
    function getNomRevistas_ajax() {
        $document = JFactory::getDocument();
        $document->setMimeEncoding( 'application/json' );
        $modelRevista = $this->getModel($this->model_revista);
        $array = array();
        foreach($modelRevista->getRevistas() as $revista) {
            $array[$revista->issn] = $revista->issn;
        }
        echo json_encode($array);
    }
    
    /***************************************************/
    
    function getCategoriasEntidadCalidad() { //TODO
        $document = JFactory::getDocument();
        $document->setMimeEncoding( 'application/json' );
        $modelCalidad = $this->getModel("EntidadCalidad");
        $categorias = $modelCalidad->getCategoriasEntidad(JRequest::getVar('entidad'));
        echo json_encode($categorias);
    }
    
    /******************************IMPORT**********************************/
    
    function isValidDateTime($dateTime) {
        if (preg_match("/^(\d{4})-(\d{2})-(\d{2}) ([01][0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])$/", $dateTime, $matches)) {
            if (checkdate($matches[2], $matches[3], $matches[1])) {
                return true;
            }
        }
        return false;
    }
    
    function postFromImport() {
        $document = JFactory::getDocument();
        $document->setMimeEncoding( 'application/json' );
        
        $tituloEntidadPublicadora = JRequest::getVar('tituloEntidadPublicadora');
        //$sourceType = JRequest::getVar('sourceType');
        $tituloPublicacion = JRequest::getVar('tituloPublicacion');
        $issnRevista = JRequest::getVar('issnRevista');
        $volumen = JRequest::getVar('volumen');
        $numero = JRequest::getVar('numero');
        $fechaPublicacion = $this->formatToTable(JRequest::getVar('fechaPublicacion'));
        //$autor = JRequest::getVar('tituloPublicacion');
        
        $error_id_empty = empty($issnRevista) || empty($volumen) || empty($numero);     // (issn || volumen || numero) vacio
        $error_issue_not_number = !ctype_digit($numero);                                // "numero" not a number 
        $error_exists_issue = false;         //existe ya una issue con esa clave

        if ($error_id_empty) {
            echo json_encode(array('tituloPublicacion' => $tituloPublicacion, "error" => 'Issn, volumen y número no pueden ser vacios'));
        }
        else if ($error_issue_not_number) {
            echo json_encode(array('tituloPublicacion' => $tituloPublicacion, "error" => 'El campo "numero" debe se de tipo numérico'));
        }
        else {
            /* Revista */
            $modelRevista = $this->getModel($this->model_revista);
            //comprobamos si existe -> si no existe la creamos
            if (!$modelRevista->existsRevista($issnRevista)) {
                $modelRevista->insertRevista($issnRevista, $tituloEntidadPublicadora, '', '', -1, null);
            }
            
            $modelIssue = $this->getModel($this->model_issue);
            //comprobamos si ya existe
            $modelIssue->setRevista($issnRevista);
            if ($modelIssue->existsIssue($volumen, $numero, $issnRevista)) {
                echo json_encode(array('tituloPublicacion' => $tituloPublicacion, "error" => 'Ya existe la issue'));
            }else {
                $limRecepcion = $this->formatToTable('no_date');
                $fechaDefinitiva = $this->formatToTable('no_date');
                $modelIssue->insertIssue($volumen, $numero, $issnRevista, $limRecepcion, $fechaDefinitiva, $fechaPublicacion);
                echo json_encode(array('tituloPublicacion' => $tituloPublicacion, 'issnRevista' => $issnRevista, 'volumen' => $volumen, 'numero' => $numero));
            }
        }
    }
    
    
    function display($cachable = false, $urlparams= false)
    {	
        parent::display();
    }
}

?>