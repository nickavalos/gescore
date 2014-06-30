<?php
/**
 * This file is part of the project Gestor de Congresos y Revistas en Joomla (GesCORE).
 *
 * @package		GesCORE backend 
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

defined('_JEXEC') or die();
jimport( 'joomla.application.component.modellist' );

class GestorcoreModelEntidadCalidad extends JModelList
{
    var $modoInfoEntidadCalidad = false;
    
    /**
     * Returns a reference to the a Table object, always creating it.
     *
     * @param	type	The table type to instantiate
     * @param	string	A prefix for the table class name. Optional.
     * @param	array	Configuration array for model. Optional.
     * @return	JTable	A database object
     * @since	1.6
     */
    public function getTable($type = 'Gestorcore', $prefix = 'GestorcoreTable', $config = array()) 
    {
            return JTable::getInstance($type, $prefix, $config);
    }
    
    /**
    * Constructor override.
    *
    * @param	array	$config	An optional associative array of configuration settings.
    *
    * @return	GescoreModelGescore
    * @since	1.0
    * @see		JModelList
    */
    public function __construct($config = array()) {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                    'nombre', 
                    'siglas',
                    'criterios',
                    'numeroEdiciones',
                    'numeroIssues',
                    //info Entidad Calidad
                    /*'entidadPublicadora',
                    'categoria',
                    'ordenVolumen',
                    'indice'*/
            );
        }
        parent::__construct($config);
    }
    
    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.

     * @param	string	$ordering	An optional ordering field.
     * @param	string	$direction	An optional direction (asc|desc).
     *
     * @return	void
     * @since	1.0
     */
    protected function populateState($ordering = 'nombre', $direction = 'asc') {
        // Set list state ordering defaults.
        /*if (JRequest::getVar('layout') == 'default_infoEntidad') {
            $ordering = 'categoria';
        }*/
        parent::populateState($ordering, $direction);
    }
    
    /**
    * Method to build an SQL query to load the list data.
    *
    * @return	string	An SQL query
    */
    protected function getListQuery() {
        // Create a new query object.
        $db = JFactory::getDBO();
        
        // Orden y direccion
        $orderCol	= $this->state->get('list.ordering');
        $orderDirn	= $this->state->get('list.direction');
        /*
        if (JRequest::getVar('layout') == 'default_infoEntidad') { //cargamos la información de una entidad de calidad
            $query = 'SELECT CONCAT(ve.ordenEdicion, "%%", ve.nomCongreso) AS id, ve.nomCongreso AS entidadPublicadora, \'Edición\' AS categoria,  ve.ordenEdicion AS ordenVolumen, \'\' AS numero, ve.indice '
                    .'FROM grc_valoracionedicion ve '
                    .'WHERE ve.nomEntidad = ' . $db->quote(JRequest::getVar('id'))
                    .' UNION ALL '
                    .'SELECT CONCAT(vi.volumenIssue, "%%", vi.numeroIssue, "%%", vi.issnRevista), r.nombre AS entidadPublicadora, \'Issue\' AS categoria,  vi.volumenIssue AS ordenVolumen, vi.numeroIssue AS numero, vi.indice '
                    .'FROM grc_valoracionissue vi, grc_revista r '
                    .'where vi.nomEntidad = '. $db->quote(JRequest::getVar('id')) . ' and vi.issnRevista = r.issn '
                    .'ORDER BY '. $orderCol . ' ' . $orderDirn;
            return $query;
        }*/
        //lista de entidades de calidad
        $query = $db->getQuery(true);
        // Select some fields
        $query->select('ec.nombre, ec.siglas, ec.link, ec.criterios, ve.ordenEdicion, ve.nomCongreso, 
                        count(distinct ve.ordenEdicion, ve.nomCongreso) as numeroEdiciones, 
                        count(distinct vi.volumenIssue, vi.numeroIssue, vi.issnRevista) as numeroIssues');
        $query->from(TABLE_ENTIDAD_INDICE_CALIDAD . ' AS ec');
        $query->join('LEFT', TABLE_VALORACION_EDICION . ' AS ve ON ec.nombre = ve.nomEntidad');
        
        $query->join('LEFT', TABLE_VALORACION_ISSUE . ' AS vi ON ec.nombre = vi.nomEntidad');
        $query->group('ec.nombre');
        
        $query->order($db->getEscaped($orderCol.' '.$orderDirn));
        
        /*
         * $query = 'select ec.nombre, ec.siglas, ec.link, ec.criterios, ve.ordenEdicion, ve.nomCongreso, 
                count(distinct ve.ordenEdicion, ve.nomCongreso) as numeroEdiciones, count(distinct vi.volumenIssue, vi.numeroIssue, vi.issnRevista) as numeroIssues
          from grc_entidadindicecalidad ec
          left join grc_valoracionedicion ve
          on ec.nombre = ve.nomEntidad
          left join grc_valoracionissue vi
          on ec.nombre = vi.nomEntidad
          group by ec.nombre';
         */
        return $query;
    }
    
    function getEntidadesIndiceCalidad() {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from(TABLE_ENTIDAD_INDICE_CALIDAD);
        return $this->_getList( $query );
    }
    
    function getEntidadCalidad($nombre) {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from(TABLE_ENTIDAD_INDICE_CALIDAD);
        $query->where('nombre = ' . $db->quote($nombre));
        $db->setQuery($query);
        return $db->loadObject();
    }
    
    function updateEntidadCalidad($nomEntidadCalidadActual, $nombre, $siglas, $link, $criterios) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->update(TABLE_ENTIDAD_INDICE_CALIDAD. ' as e');
        $query->set("e.nombre=".$db->quote($nombre). 
                    ", e.siglas=".$db->quote($siglas).
                    ", e.link=".$db->quote($link).
                    ", e.criterios=".$db->quote($criterios));
        $query->where("e.nombre=" . $db->quote($nomEntidadCalidadActual));        
        $db->setQuery( $query );
        return $db->query()?true:false;
    }
    
    function deleteEntidadCalidad($nombre) {
        //$cids = implode(',', $cid );
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->delete($db->nameQuote(TABLE_ENTIDAD_INDICE_CALIDAD));             
        $query->where($db->nameQuote('nombre') . '=' . $db->quote($nombre) );
        //echo $query;
        $db->setQuery( $query );
        return $db->query()?true:false;
    }
    
    function existsEntidadCalidad($nomEntidadCalidad) {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('count(*) as contador');
        $query->from(TABLE_ENTIDAD_INDICE_CALIDAD);
        $query->where($db->nameQuote('nombre').'='.$db->quote($nomEntidadCalidad));
        $db->setQuery($query);
        return $db->loadObject()->contador > 0? true:false;
    }
    
    /*function getValoracionesEntidad($type, $nomEntidad) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        if ($type == 'edicion') {
            $query->select('ordenEdicion, nomCongreso, indice');
            $query->from(TABLE_VALORACION_EDICION);   
        } else { //issue
            $query->select('volumenIssue, numeroIssue, issnRevista, indice');
            $query->from(TABLE_VALORACION_ISSUE);
        }
        $query->where('nomEntidad = ' . $db->quote($nomEntidad));
        $db->setQuery($query);
        return $db->loadObjectList();
    }*/
    
    function getValoraciones($congreso, $orden) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('ve.nomEntidad, ve.indice');
        $query->from($db->nameQuote(TABLE_VALORACION_EDICION) . ' as ve');
        $query->where('ve.ordenEdicion = ' . $db->quote($orden) . ' and ve.nomCongreso = ' . $db->quote($congreso));
        $db->setQuery($query);
        return $db->loadObjectList();
    }
    
    function getCategoriasEntidad($entidad) {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('categoria');
        $query->from(TABLE_ENTIDAD_CATEGORIA);
        $query->where('nomEntidad = ' . $db->quote($entidad));
        $query->order('valoracionCategoria');
        $db->setQuery($query);
        return $db->loadObjectList();
    }
    
    function insertEntidadCalidad($nombre, $siglas, $link, $criterios, $tipoCategoria) {
        $data = new stdClass();
        $data->nombre = $nombre;
        $data->siglas = $siglas;
        $data->link = $link;
        $data->criterios = $criterios;
        $data->tipoCategoria = $tipoCategoria;
        $db = JFactory::getDbo();
        $db->insertObject(TABLE_ENTIDAD_INDICE_CALIDAD, $data);
        return $db->query()?true:false;
    }
    
    function insertCategoria($categoria, $valoracion) {
        $data = new stdClass();
        $data->categoria = $categoria;
        $data->valoracion = $valoracion;
        $db = JFactory::getDbo();
        $db->insertObject(TABLE_CATEGORIA, $data);
        return $db->query()?true:false;
    }
    
    function insertEntidadCategoria($nombre, $categoria, $valoracion) {
        $data = new stdClass();
        $data->nomEntidad = $nombre;
        $data->categoria = $categoria;
        $data->valoracionCategoria = $valoracion;
        $db = JFactory::getDbo();
        $db->insertObject(TABLE_ENTIDAD_CATEGORIA, $data);
        return $db->query()?true:false;
    }
    
    function insertValoracionEdicion($nomEntidad, $ordenEdicion, $nomCongreso, $valoracion) {
        $data = new stdClass();
        $data->nomEntidad = $nomEntidad;
        $data->ordenEdicion = $ordenEdicion;
        $data->nomCongreso = $nomCongreso;
        $data->indice = $valoracion;
        $db = JFactory::getDbo();
        $db->insertObject(TABLE_VALORACION_EDICION, $data);
        return $db->query()?true:false;
    }
    
    function deleteValoracionEdicion($nomEntidad, $orden, $nomCongreso) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->delete($db->nameQuote(TABLE_VALORACION_EDICION));             
        $query->where($db->nameQuote('nomEntidad').'='.$db->quote($nomEntidad). ' and '.
                      $db->nameQuote('ordenEdicion').'='.$db->quote($orden). ' and '.
                      $db->nameQuote('nomCongreso').'='.$db->quote($nomCongreso));
        $db->setQuery( $query );
        return $db->query()?true:false;
    }
    
    /************************************REVISTA*********************************/
    
    function insertValoracionIssue($nomEntidad, $volumen, $numero, $issnRevista, $valoracion) {
        $data = new stdClass();
        $data->nomEntidad = $nomEntidad;
        $data->volumenIssue = $volumen;
        $data->numeroIssue = $numero;
        $data->issnRevista = $issnRevista;
        $data->indice = $valoracion;
        $db = JFactory::getDbo();
        $db->insertObject(TABLE_VALORACION_ISSUE, $data);
        return $db->query()?true:false;
    }
    
    function getValoracionesIssue($revista, $volumen, $numero) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('vi.nomEntidad, vi.indice');
        $query->from($db->nameQuote(TABLE_VALORACION_ISSUE) . ' as vi');
        $query->where('vi.volumenIssue = ' . $db->quote($volumen) . ' and vi.numeroIssue = ' . $db->quote($numero) . 
                      ' and vi.issnRevista=' . $db->quote($revista));
        $db->setQuery($query);
        return $db->loadObjectList();
    }
    
    function deleteValoracionIssue($nomEntidad, $volumen, $numeroIssue, $issnRevista) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->delete($db->nameQuote(TABLE_VALORACION_ISSUE));             
        $query->where($db->nameQuote('nomEntidad').'='.$db->quote($nomEntidad). ' and '.
                      $db->nameQuote('volumenIssue').'='.$db->quote($volumen). ' and '.
                      $db->nameQuote('numeroIssue').'='.$db->quote($numeroIssue). ' and '.
                      $db->nameQuote('issnRevista').'='.$db->quote($issnRevista));
        $db->setQuery( $query );
        return $db->query()?true:false;
    }
    
    
}

?>