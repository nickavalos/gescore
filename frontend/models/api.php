<?php
/**
 * This file is part of the project Gestor de Congresos y Revistas en Joomla (GesCORE).
 *
 * @package		GesCORE frontend 
 * @subpackage  Models 
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

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla modelitem library
jimport( 'joomla.application.component.modellist' );


class GestorcoreModelApi extends JModelList {
    var $typeEditorial = 'editorial';
    var $typeCongreso = 'congress';
    var $typeEdicion = 'edition';
    var $typeRevista = 'journal';
    var $typeIssue = 'issue';
    var $typeEntidadCalidad = 'qualityEntity';
    var $typeTopico = 'topic';
    var $typeAll = '*';
    
    function formatToTable($dateString) {
        return date('Ymd', strtotime(str_replace('/', '-', $dateString)));    
    }
    
    function searchAPI($objSearch, $objEdition, $objIssue) {
        $db	= $this->getDbo();
        $search = $objSearch->filter;

        $sql_keyword =  $db->Quote('%'.$db->getEscaped($search, true).'%');
        
        $where_editorial[] = 'e.nombre LIKE ' . $sql_keyword;
        $where_editorial[] = 'e.siglas LIKE ' . $sql_keyword;
        
        $where_congreso[] = 'c.nombre LIKE '  . $sql_keyword;
        $where_congreso[] = 'c.acronimo LIKE '  . $sql_keyword;
        
        $where_revista[] = 'r.nombre LIKE ' . $sql_keyword;
        $where_revista[] = 'r.acronimo LIKE ' . $sql_keyword;
        $where_revista[] = 'r.issn LIKE ' . $sql_keyword;
        
        $where_edicion[] = 'ed.lugar LIKE ' . $sql_keyword;
        
        $where_entidad_calidad[] = 'ec.nombre LIKE ' . $sql_keyword;
        $where_entidad_calidad[] = 'ec.siglas LIKE ' . $sql_keyword;
        
        $where_topico[] = 't.nombre LIKE ' . $sql_keyword;
        $where_topico[] = 't.descripcion LIKE ' . $sql_keyword;
        
        $where_edicion_topico[] = 'et.nomTopico LIKE ' . $sql_keyword;
        
        $where_issue_topico[] = 'it.nomTopico LIKE ' . $sql_keyword;
        
        $where_editorial        = 'WHERE (' . implode( ' OR ', $where_editorial ) . ')';
        $where_congreso         = 'WHERE (' . implode( ' OR ', $where_congreso ) . ')';
        $where_revista          = 'WHERE (' . implode( ' OR ', $where_revista ) . ')';
        $where_edicion          = 'WHERE (' . implode( ' OR ', $where_edicion ) . ')';
        $where_entidad_calidad  = 'WHERE (' . implode( ' OR ', $where_entidad_calidad ) . ')';
        $where_topico           = 'WHERE (' . implode( ' OR ', $where_topico ) . ')';
        $where_edicion_topico   = 'WHERE ed.orden = et.ordenEdicion and ed.nomCongreso = et.nomCongreso and ' . $where_edicion_topico[0];
        $where_issue_topico     = 'WHERE i.volumen=it.volumenIssue and i.numero=it.numeroIssue and i.issnRevista = it.issnRevista and ' 
                                  . 'it.issnRevista=r.issn and ' . $where_issue_topico[0];
        
        $queryEditorial = 'SELECT e.nombre as id, e.nombre as title, e.siglas as acronimo, \'Editorial\' as categoria, \'\' as fecha, \'\' as lugar, e.link as linkEntidad '
                        . 'FROM ' . TABLE_EDITORIAL . ' AS e '
                        . $where_editorial;
        $queryCongreso  = 'SELECT c.nombre as id, c.nombre as title, c.acronimo as acronimo, \'Congreso\' as categoria, \'\' as fecha, \'\' as lugar, c.linkCongreso as linkEntidad '
                        . 'FROM ' . TABLE_CONGRESO . ' AS c '
                        . $where_congreso;
        $queryRevista   = 'SELECT r.issn as id, CONCAT(r.nombre, " (issn: ", r.issn, ")") as title, r.acronimo as acronimo, \'Revista\' as categoria, \'\' as fecha, \'\' as lugar, r.linkRevista as linkEntidad '
                        . 'FROM ' . TABLE_REVISTA . ' AS r '
                        . $where_revista;
        $queryEdicion   = 'SELECT CONCAT(ed.orden, "%%", ed.nomCongreso) as id, CONCAT(ed.orden, "%%", ed.nomCongreso) as title, \'\' as acronimo, \'Edición\' as categoria, ed.fechaInicio as fecha, ed.lugar as lugar, ed.linkEdicion as linkEntidad '
                        . 'FROM ' . TABLE_EDICION . ' AS ed ';
                        //. $where_edicion;
        $queryIssue     = 'SELECT CONCAT(i.volumen, "%%", i.numero, "%%", i.issnRevista) as id, CONCAT(i.volumen, "%%", i.numero, "%%", r.nombre) as title, \'\' as acronimo, \'Issue\' as categoria, i.fechaPublicacion as fecha, \'\' as lugar, \'\' as linkEntidad '
                        . 'FROM ' . TABLE_ISSUE . ' AS i, ' . TABLE_REVISTA . ' AS r ';
                        //. 'WHERE i.volumen=null';
        $queryCalidad   = 'SELECT ec.nombre as id, ec.nombre as title, ec.siglas as acronimo, \'Entidad de Calidad\' as categoria, \'\' as fecha, \'\' as lugar, ec.link as linkEntidad '
                        . 'FROM ' . TABLE_ENTIDAD_INDICE_CALIDAD . ' AS ec '
                        . $where_entidad_calidad;
        $queryTopico    = 'SELECT t.nombre as id, t.nombre as title, \'\' as acronimo, \'Tópico\' as categoria, \'\' as fecha, \'\' as lugar, \'\' as linkEntidad '
                        . 'FROM ' . TABLE_TOPICO . ' AS t '
                        . $where_topico;
        $queryEdicionTopico= 'SELECT CONCAT(ed.orden, "%%", ed.nomCongreso) as id, CONCAT(ed.orden, "%%", ed.nomCongreso, "%%", et.nomTopico) as title, \'\' as acronimo, \'Edición\' as categoria, ed.fechaInicio as fecha, ed.lugar as lugar, ed.linkEdicion as linkEntidad '
                        . 'FROM ' . TABLE_EDICION . ' AS ed, ' . TABLE_EDICION_TOPICO . ' AS et '
                        . $where_edicion_topico;
        $queryIssueTopico= 'SELECT CONCAT(i.volumen, "%%", i.numero, "%%", i.issnRevista) as id, CONCAT(i.volumen, "%%", i.numero, "%%", r.nombre, "%%", it.nomTopico) as title, \'\' as acronimo, \'Issue\' as categoria, i.fechaPublicacion as fecha, \'\' as lugar, \'\' as linkEntidad '
                        . 'FROM ' . TABLE_ISSUE . ' AS i, ' . TABLE_ISSUE_TOPICO . ' AS it, ' . TABLE_REVISTA . ' AS r '
                        . $where_issue_topico;
        
        $where_edicion_sub = array();
        $where_issue_sub = array();
        // Add the list ordering clause.
        
        $orderCol	= $objSearch->sort;
        $orderDirn	= $objSearch->sortDirection;
        
        $allowBusquedaTopicos = false; //permite búsqueda en los topicos
        switch($objSearch->type) {
            case $this->typeEditorial:
                $query = $queryEditorial;
                break;
            case $this->typeCongreso:
                $query = $queryCongreso;
                break;
            case $this->typeRevista:
                $query = $queryRevista;
                break;
            case $this->typeEdicion:
                $query = $queryEdicion;
                $subfilter_lugar = $objEdition->place;
                $subfilter_limRecep = $objEdition->limReception_min;
                $subfilter_limDefinitiva = $objEdition->definitiveDate_min;
                $subfilter_inicio = $objEdition->startDate_min;
                $subfilter_fin = $objEdition->endDate_min;
                $subfilter_limRecep_max = $objEdition->limReception_max;
                $subfilter_limDefinitiva_max = $objEdition->definitiveDate_max;
                $subfilter_inicio_max = $objEdition->startDate_max;
                $subfilter_fin_max = $objEdition->endDate_max;
                if (!empty($subfilter_lugar)) {
                    $where_edicion_sub[] = 'ed.lugar = ' . $db->quote($subfilter_lugar);
                } 
                if (!empty($subfilter_limRecep)) {
                    $where_edicion_sub[] = 'ed.limRecepcion >= ' . $this->formatToTable($subfilter_limRecep);
                }
                if (!empty($subfilter_limDefinitiva)) {
                    $where_edicion_sub[] = 'ed.fechaDefinitiva >= ' . $this->formatToTable($subfilter_limDefinitiva);
                }
                if (!empty($subfilter_inicio)) {
                    $where_edicion_sub[] = 'ed.fechaInicio >= ' . $this->formatToTable($subfilter_inicio);
                }
                if (!empty($subfilter_fin)) {
                    $where_edicion_sub[] = 'ed.fechaFin >= ' . $this->formatToTable($subfilter_fin);
                }
                if (!empty($subfilter_limRecep_max)) {
                    $where_edicion_sub[] = 'ed.limRecepcion <= ' . $this->formatToTable($subfilter_limRecep_max);
                }
                if (!empty($subfilter_limDefinitiva_max)) {
                    $where_edicion_sub[] = 'ed.fechaDefinitiva <= ' . $this->formatToTable($subfilter_limDefinitiva_max);
                }
                if (!empty($subfilter_inicio_max)) {
                    $where_edicion_sub[] = 'ed.fechaInicio <= ' . $this->formatToTable($subfilter_inicio_max);
                }
                if (!empty($subfilter_fin_max)) {
                    $where_edicion_sub[] = 'ed.fechaFin <= ' . $this->formatToTable($subfilter_fin_max);
                }
                if (!empty($where_edicion_sub)) { //hay subfiltros
                    $where_final_edicion = 'WHERE (' . implode( ' AND ', $where_edicion_sub ) . ')';
                }else { // no hay filtros: buscamos sólo en la edición
                    $where_final_edicion = $where_edicion;
                }
                $query .= $where_final_edicion;
                $allowBusquedaTopicos = true;
                break;
            case $this->typeIssue:
                $query = $queryIssue;
                $subfilter_issue_limRecep = $objIssue->limReception_min;
                $subfilter_issue_fechaDef = $objIssue->definitiveDate_min;
                $subfilter_issue_publicacionMin = $objIssue->publicationDate_min;
                $subfilter_issue_limRecep_max = $objIssue->limReception_max;
                $subfilter_issue_fechaDef_max = $objIssue->definitiveDate_max;
                $subfilter_issue_publicacionMax = $objIssue->publicationDate_max;
                if (!empty($subfilter_issue_limRecep)) {
                    $where_issue_sub[] = 'i.limRecepcion >= ' . $this->formatToTable($subfilter_issue_limRecep);
                }
                if (!empty($subfilter_issue_fechaDef)) {
                    $where_issue_sub[] = 'i.fechaDefinitiva >= ' . $this->formatToTable($subfilter_issue_fechaDef);
                }
                if (!empty($subfilter_issue_publicacionMin)) {
                    $where_issue_sub[] = 'i.fechaPublicacion >= ' . $this->formatToTable($subfilter_issue_publicacionMin);
                }
                if (!empty($subfilter_issue_limRecep_max)) {
                    $where_issue_sub[] = 'i.limRecepcion <= ' . $this->formatToTable($subfilter_issue_limRecep_max);
                }
                if (!empty($subfilter_issue_fechaDef_max)) {
                    $where_issue_sub[] = 'i.fechaDefinitiva <= ' . $this->formatToTable($subfilter_issue_fechaDef_max);
                }
                if (!empty($subfilter_issue_publicacionMax)) {
                    $where_issue_sub[] = 'i.fechaPublicacion <= ' . $this->formatToTable($subfilter_issue_publicacionMax);
                }
                $where_final_issue = '';
                if (empty($where_issue_sub)) { // no hay subfiltros
                    $where_final_issue = 'WHERE i.issnRevista = r.issn';
                } else {
                    $where_final_issue = 'WHERE (' . implode( ' AND ', $where_issue_sub ) . ')';
                }
                $query .= $where_final_issue;
                $allowBusquedaTopicos = true;
                break;
            case $this->typeEntidadCalidad:
                $query = $queryCalidad;
                break;
            case $this->typeTopico:
                $query = $queryTopico;
                //$allowBusquedaTopicos = true;
                break;
            default:
                $queryEdicion .= $where_edicion;
                $query = $queryEditorial
                . ' UNION '
                . $queryCongreso
                . ' UNION '
                . $queryRevista
                . ' UNION '
                . $queryEdicion
                . ' UNION '
                . $queryCalidad
                . ' UNION '
                . $queryTopico
                ;
                $allowBusquedaTopicos = true;
                break;   
        }
        if ($objSearch->searchInTopics && $allowBusquedaTopicos) {
            switch($objSearch->type) {
                case $this->typeEdicion:
                    if (!empty($where_edicion_sub)) { // hay subfiltros
                        $query = $queryEdicionTopico; //dejamos de tener en cuenta lo que habiamos buscado antes
                        $query .= ' AND ' . implode( ' AND ', $where_edicion_sub );
                    } else {
                        $query .= ' UNION '
                                . $queryEdicionTopico;
                    }
                    break;
                case $this->typeIssue:
                    if (!empty($where_issue_sub)) { //hay subfiltros
                        $query = $queryIssueTopico . ' AND ' . implode( ' AND ', $where_issue_sub);
                    } else { // si no hay subfiltros solo tenemos en cuenta lo que busque el filtro principal
                        $query = $queryIssueTopico;
                    }
//                    $query .= ' UNION ALL '
//                            . $queryIssueTopico;
                    break;
                default:
                    $query .= ' UNION '
                    . $queryEdicionTopico
                    . ' UNION '
                    . $queryIssueTopico;
                    break;
            }
            
        }
        $query .= ' ORDER BY '. $orderCol . ' ' . $orderDirn;
        //$query .= ' LIMIT ' . $objSearch->numResults; 
        //$query .= ' OFFSET ' . $objSearch->offset; 
        
        //echo nl2br(str_replace('#__','jos_',$query));
        $db->setQuery($query);
        
        //$db->query();
        $results = $db->loadObjectList();
        /*
        if (is_null($results)) {
            return array("ERROR" => $db->getErrorMsg());
        }
        else {
            return array("OK" => $results);
        }
        //mysql_error();
        */
        return $results;
    }
}
