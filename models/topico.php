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

class GestorcoreModelTopico extends JModelList
{
    var $datos;
    
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
                    'descripcion',
                    'similares',
                    'numeroEdiciones',
                    'numeroIssues'
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
        select t.nombre, t.descripcion, count(distinct et.ordenEdicion, et.nomCongreso) as numeroEdiciones, count(distinct it.volumenIssue, it.numeroIssue, it.issnRevista) as numeroIssues
        from jos_topico t

        left join jos_edicionTopico et
        on t.nombre = et.nomTopico
        left join jos_issueTopico it
        on t.nombre = it.nomTopico
        group by t.nombre
         */
        
        
        //lista de entidades de calidad
        $query = $db->getQuery(true);
        // Select some fields
        $query->select('t.nombre, t.descripcion,
                        count(distinct et.ordenEdicion, et.nomCongreso) as numeroEdiciones,
                        count(distinct it.volumenIssue, it.numeroIssue, it.issnRevista) as numeroIssues');
        $query->from(TABLE_TOPICO . ' AS t');
        $query->join('LEFT', TABLE_EDICION_TOPICO . ' AS et ON t.nombre = et.nomTopico');
        
        $query->join('LEFT', TABLE_ISSUE_TOPICO . ' AS it ON t.nombre = it.nomTopico');
        $query->group('t.nombre');
        
        $query->order($db->getEscaped($orderCol.' '.$orderDirn));
        return $query;
    }
   
    function getTopicos() {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from(TABLE_TOPICO);
        return $this->_getList( $query );
    }
    
    function existsTopico($nombre) {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('count(*) as contador');
        $query->from(TABLE_TOPICO);
        $query->where($db->nameQuote('nombre').'='.$db->quote($nombre));
        $db->setQuery($query);
        return $db->loadObject()->contador > 0? true:false;
    }
    
    function updateTopico($nomTopicoActual, $nombre, $descripcion) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->update(TABLE_TOPICO. ' as e');
        $query->set("e.nombre = ".$db->quote($nombre). 
                    ", e.descripcion = ".$db->quote($descripcion));
        $query->where("e.nombre = " . $db->quote($nomTopicoActual));        
        $db->setQuery( $query );
        return $db->query()?true:false;
    }
    
    function getAllTopicosSimilares() {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from(TABLE_TOPICO_SIMILAR);
        return $this->_getList( $query );
    }
    
    function insertTopico($nomTopico, $descripcion) {
        $data = new stdClass();
        $data->nombre = $nomTopico;
        $data->descripcion = $descripcion;
        $db = JFactory::getDbo();
        $db->insertObject(TABLE_TOPICO, $data);
        return $db->query()?true:false;
    }
    
    function deleteTopico($nombre) {
        //$cids = implode(',', $cid );
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->delete($db->nameQuote(TABLE_TOPICO));             
        $query->where($db->nameQuote('nombre') . '=' . $db->quote($nombre) );
        //echo $query;
        $db->setQuery( $query );
        return $db->query()?true:false;
    }
    
    function insertTopicoSimilar($topico, $topSimilar) { //insert bidireccional, propiedad reflexiva
        $data = new stdClass();
        $data->nomTopico = $topico;
        $data->nomTopicoSimilar = $topSimilar;
        
        $data2 = new stdClass();
        $data2->nomTopico = $topSimilar;
        $data2->nomTopicoSimilar = $topico;
        
        $db = JFactory::getDbo();
        $db->insertObject(TABLE_TOPICO_SIMILAR, $data);
        $db->insertObject(TABLE_TOPICO_SIMILAR, $data2);
        return $db->query()?true:false;
    }
    
    function deleteTopicoSimilar($topico, $topSimilar) {//delete bidireccional, propiedad reflexiva
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->delete($db->nameQuote(TABLE_TOPICO_SIMILAR));             
        $query->where('(' . $db->nameQuote('nomTopico').'='.$db->quote($topico) . ' and ' . $db->nameQuote('nomTopicoSimilar').'='. $db->quote($topSimilar) . ') or ('
                     . $db->nameQuote('nomTopico').'='.$db->quote($topSimilar) . ' and ' . $db->nameQuote('nomTopicoSimilar').'='. $db->quote($topico) . ')');
        $db->setQuery( $query );
        return $db->query()?true:false;
    }
    
    function getTopicosSimilares_filtrados($excepcionTopico) {
        $db = JFactory::getDBO();
        $query = "select * from " . TABLE_TOPICO . " as t
                  where t.nombre not in (select ts.nomTopicoSimilar 
                                         from " . TABLE_TOPICO_SIMILAR ." as ts
                                         where ts.nomTopico =" . $db->quote($excepcionTopico) . ")";
        $db->setQuery($query);
        return $db->loadObjectList();
    }
    
    function getTopicosFiltrados($excepcionCongreso) {
        $db = JFactory::getDBO();
        $query = "select * from " . TABLE_TOPICO . " as t
                  where t.nombre not in (select ct.nomTopico 
                                         from " . TABLE_CONGRESO_TOPICO ." as ct
                                         where ct.nomCongreso =" . $db->quote($excepcionCongreso) . ")";
        $db->setQuery($query);
        return $db->loadObjectList();
    }
    
    function getTopicosFiltradosEdicion($excepcionCongreso, $excepcionOrden) {
        $db = JFactory::getDBO();
        $query = "select * from " . TABLE_TOPICO . " as t
                  where t.nombre not in (select et.nomTopico 
                                         from " . TABLE_EDICION_TOPICO ." as et
                                         where et.ordenEdicion =" . $db->quote($excepcionOrden) . " and et.nomCongreso =" . $db->quote($excepcionCongreso) . ")";
        $db->setQuery($query);
        return $db->loadObjectList();
    }
    
    
    /**
     * La similitud de un topico es reflexivo, por tanto en la tabla no hay dos tuplas "a"=>"b" y "b" => "a"
     */
    function getTopicosSimilares($nomTopico) {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('nomTopicoSimilar');
        $query->from(TABLE_TOPICO_SIMILAR);
        $query->where($db->nameQuote('nomTopico')."=".$db->quote($nomTopico));
        $db->setQuery($query);
        return $db->loadObjectList();
    }
    
    function getTopicosSimilares_NoCongreso ($nomTopico, $nomCongreso) {
        $db = JFactory::getDbo();
        $query = "select ts.nomTopicoSimilar, t.descripcion
                  from " . TABLE_TOPICO_SIMILAR . " as ts, " . TABLE_TOPICO . " as t
                  where ts.nomTopicoSimilar=t.nombre and ts.nomTopico=" . $db->quote($nomTopico) . " and 
                  ts.nomTopicoSimilar not in (select ct.nomTopico 
                                              from " . TABLE_CONGRESO_TOPICO . " as ct
                                              where ct.nomCongreso=" . $db->quote($nomCongreso) . ")";
        $db->setQuery($query);
        return $db->loadObjectList();
    }
    
    function getTopicosSimilares_NoEdicion ($nomTopico, $nomCongreso, $orden) {
        $db = JFactory::getDbo();
        $query = "select ts.nomTopicoSimilar, t.descripcion
                  from " . TABLE_TOPICO_SIMILAR . " as ts, " . TABLE_TOPICO . " as t
                  where ts.nomTopicoSimilar=t.nombre and ts.nomTopico=" . $db->quote($nomTopico) . " and 
                  ts.nomTopicoSimilar not in (select et.nomTopico 
                                              from " . TABLE_EDICION_TOPICO . " as et
                                              where et.ordenEdicion=" . $db->quote($orden) . " and et.nomCongreso=" . $db->quote($nomCongreso) . ")";
        $db->setQuery($query);
        return $db->loadObjectList();
    }
    
    function getTopicosCongreso_NoEdicion ($nomCongreso, $excepcionOrden) {
        $db = JFactory::getDBO();
        $query = "select ct.nomTopico, t.descripcion
                 from " . TABLE_CONGRESO_TOPICO . " as ct, " . TABLE_TOPICO . " as t
                 where ct.nomTopico=t.nombre and ct.nomCongreso=" . $db->quote($nomCongreso) . " and 
                       ct.nomTopico not in (select et.nomTopico 
                                            from " . TABLE_EDICION_TOPICO . " as et
                                            where et.nomCongreso=ct.nomCongreso and et.ordenEdicion=" . $db->quote($excepcionOrden) . ")";
        $db->setQuery($query);
        return $db->loadObjectList();
    }
    
   
    
    /**************************************REVISTAS****************************************************/
    
    function getTopicosFiltrados_revista($excepcionRevista) {
        $db = JFactory::getDBO();
        $query = "select * from " . TABLE_TOPICO . " as t
                  where t.nombre not in (select rt.nomTopico 
                                         from " . TABLE_REVISTA_TOPICO ." as rt
                                         where rt.issnRevista =" . $db->quote($excepcionRevista) . ")";
        $db->setQuery($query);
        return $db->loadObjectList();
    }
    
    function getTopicosFiltradosIssue($excepcionRevista, $excepcionVolumen, $excepcionNumero) {
        $db = JFactory::getDBO();
        $query = "select * from " . TABLE_TOPICO . " as t
                  where t.nombre not in (select it.nomTopico 
                                         from " . TABLE_ISSUE_TOPICO ." as it
                                         where it.volumenIssue =" . $db->quote($excepcionVolumen) .
                                         " and it.numeroIssue =" . $db->quote($excepcionNumero) .
                                         " and it.issnRevista =" . $db->quote($excepcionRevista) . ")";
        $db->setQuery($query);
        return $db->loadObjectList();
    }
    
    function getTopicosSimilares_NoRevista ($nomTopico, $issnRevista) {
        $db = JFactory::getDbo();
        $query = "select ts.nomTopicoSimilar, t.descripcion
                  from " . TABLE_TOPICO_SIMILAR . " as ts, " . TABLE_TOPICO . " as t
                  where ts.nomTopicoSimilar=t.nombre and ts.nomTopico=" . $db->quote($nomTopico) . " and 
                  ts.nomTopicoSimilar not in (select rt.nomTopico 
                                              from " . TABLE_REVISTA_TOPICO . " as rt
                                              where rt.issnRevista=" . $db->quote($issnRevista) . ")";
        $db->setQuery($query);
        return $db->loadObjectList();
    }
    
    function getTopicosSimilares_NoIssue($nomTopico, $issnRevista, $volumen, $numero) {
        $db = JFactory::getDbo();
        $query = "select ts.nomTopicoSimilar, t.descripcion
                  from " . TABLE_TOPICO_SIMILAR . " as ts, " . TABLE_TOPICO . " as t
                  where ts.nomTopicoSimilar=t.nombre and ts.nomTopico=" . $db->quote($nomTopico) . " and 
                  ts.nomTopicoSimilar not in (select it.nomTopico 
                                              from " . TABLE_ISSUE_TOPICO . " as it
                                              where it.volumenIssue=" . $db->quote($volumen) . " and it.numeroIssue=" . $db->quote($numero) . 
                                                    " and it.issnRevista=" . $db->quote($issnRevista) . ")";
        //new ConsoleLog("log", $query);
        $db->setQuery($query);
        return $db->loadObjectList();
    }
    
    function getTopicosRevista_noIssue ($issnRevista, $excepcionVolumen, $excepcionNumero) {
        $db = JFactory::getDBO();
        $query = "select rt.nomTopico, t.descripcion
                 from " . TABLE_REVISTA_TOPICO . " as rt, " . TABLE_TOPICO . " as t
                 where rt.nomTopico=t.nombre and rt.issnRevista=" . $db->quote($issnRevista) . " and 
                       rt.nomTopico not in (select it.nomTopico 
                                            from " . TABLE_ISSUE_TOPICO . " as it
                                            where it.issnRevista=rt.issnRevista and it.volumenIssue=" . $db->quote($excepcionVolumen) . 
                                                  " and it.numeroIssue=" . $db->quote($excepcionNumero) . ")";
        $db->setQuery($query);
        return $db->loadObjectList();
    }
    
    /*
    function insertEditorial($nomEditorial, $siglasEditorial, $linkEditorial) {
        $data = new stdClass();
        $data->nombre = $nomEditorial;
        $data->siglas = $siglasEditorial;
        $data->link = $linkEditorial;
        $db = JFactory::getDbo();
        $db->insertObject(TABLE_EDITORIAL, $data);
        return $db->query()?true:false;
    }
    
    function existsEditorial($nomEditorial) {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('count(*) as contador');
        $query->from(TABLE_EDITORIAL);
        $query->where($db->nameQuote('nombre').'='.$db->quote($nomEditorial));
        $db->setQuery($query);
        return $db->loadObject()->contador > 0? true:false;
    } 
     */
    
    function buscaCadenaNombre($cadena) {
        $db = JFactory::getDBO();
        $query = "select nombre from " . TABLE_TOPICO . " where nombre like '%" . $cadena . "%'";
        $db->setQuery($query);
        return $db->loadObjectList();
    }
    
    function buscaCadenaDescripcion($cadena) {
        $db = JFactory::getDBO();
        $query = "select descripcion as nombre, nombre as nomTopico from " . TABLE_TOPICO . " where descripcion like '%" . $cadena . "%'";
        $db->setQuery($query);
        return $db->loadObjectList();
    }
}

?>