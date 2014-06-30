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

class GestorcoreModelCongreso extends JModelList
{
    var $datos;
    
    protected $msg;

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
    /*public function __construct($config = array()) {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                    'orden', 
                    'lugar',
                    'limRecepcion',
                    'fechaDefinitiva',
                    'fechaInicio',
                    'fechaFin'
            );
        }
        parent::__construct($config);
    }*/
    
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
    /*protected function populateState($ordering = 'orden', $direction = 'asc') {
        // Set list state ordering defaults.
        parent::populateState($ordering, $direction);
    }*/
    
    /**
    * Method to build an SQL query to load the list data.
    *
    * @return	string	An SQL query
    */
    protected function getListQuery() {
        // Create a new query object.
        $db = JFactory::getDBO();
        
        /*// Orden y direccion
        $orderCol	= $this->state->get('list.ordering');
        $orderDirn	= $this->state->get('list.direction');
        */
        $query = $db->getQuery(true);

        // Select some fields
        $query->select('*');

        // From the hello table
        $query->from(TABLE_CONGRESO);
        
        //$query->order($db->getEscaped($orderCol.' '.$orderDirn));
        return $query;
    }
   
//    function buildQuery() {
//    	$query = 'SELECT * FROM #__congreso';
//    	return $query;
//    }
   
    function getCongresos() {
        $query = $this->getListQuery();
        //echo $query;
        $this->datos = $this->_getList( $query );
        return $this->datos;
    }
    
    function getCongreso($nomCongreso) {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from(TABLE_CONGRESO);
        $query->where($db->nameQuote('nombre').'='.$db->quote($nomCongreso));
        $db->setQuery($query);
        return $db->loadObject();
    }
   
    function getTopicosFromCongreso($nomCongreso) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('ct.nomTopico as nomTopico');
        $query->from(TABLE_CONGRESO_TOPICO . ' AS ct');
        $query->where('ct.nomCongreso =' . $db->quote($nomCongreso));
        $db->setQuery($query);
        return $db->loadObjectList();
    }
    
    function getUltimaEdicionCongreso($nomCongreso) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('max(e.orden) as max');
        $query->from(array('#__edicion AS e', '#__congreso AS c'));
        $query->where('e.nomCongreso = c.nombre and c.nombre = ' . $db->quote($nomCongreso));
        $db->setQuery($query);
        return $db->loadObject();
    }
    
    function insertCongreso($nomEntidad, $acronimoEntidad, $linkEntidad, $periodicidadEntidad, $nomEditorial) {
        $data = new stdClass();
        $data->nombre = $nomEntidad;
        $data->acronimo = $acronimoEntidad;
        $data->linkCongreso = $linkEntidad;
        $data->periodicidad = $periodicidadEntidad;
        $data->nomEditorial = $nomEditorial;
        $db = JFactory::getDbo();
        $db->insertObject(TABLE_CONGRESO, $data);
        return $db->query()?true:false;
    }
    
    function updateCongreso($congresoAnt, $nombre, $acronimo, $linkCongreso, $periodicidad, $nomEditorial) {
        $db = JFactory::getDbo();
        $nomEditorial = $nomEditorial == null? 'NULL':$db->quote($nomEditorial);
        $query = $db->getQuery(true);
        $query->update(TABLE_CONGRESO . ' as c');
        $query->set("c.nombre=".$db->quote($nombre). 
                    ", c.acronimo=".$db->quote($acronimo).
                    ", c.linkCongreso=".$db->quote($linkCongreso).
                    ", c.periodicidad=".$db->quote($periodicidad).
                    ", c.nomEditorial=".$nomEditorial);
        $query->where("c.nombre=" . $db->quote($congresoAnt));        
        $db->setQuery( $query );
        return $db->query()?true:false;
    }
    
    
    
    function existsCongreso($nomCongreso) {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('count(*) as contador');
        $query->from(TABLE_CONGRESO);
        $query->where($db->nameQuote('nombre').'='.$db->quote($nomCongreso));
        $db->setQuery($query);
        return $db->loadObject()->contador > 0? true:false;
    }
    
    function deleteCongreso($nomCongreso) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->delete($db->nameQuote(TABLE_CONGRESO));             
        $query->where($db->nameQuote('nombre').'='.$db->quote($nomCongreso));
        $db->setQuery( $query );
        return $db->query()?true:false;
    }
    
    /******************TOPICOS*************/
    
    function insertTopicoCongreso($nomCongreso, $topico) {
        $db = JFactory::getDbo();
        $data = new stdClass();
        $data->nomCongreso = $nomCongreso;
        $data->nomTopico = $topico;
        return $db->insertObject(TABLE_CONGRESO_TOPICO, $data);
    }
    
    function deleteTopicoCongreso($nomCongreso, $nomTopico) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->delete($db->nameQuote(TABLE_CONGRESO_TOPICO));             
        $query->where($db->nameQuote('nomCongreso').'='.$db->quote($nomCongreso). ' and '.
                      $db->nameQuote('nomTopico').'='. $db->quote($nomTopico));
        $db->setQuery( $query );
        return $db->query()?true:false;
    }
    
}

?>