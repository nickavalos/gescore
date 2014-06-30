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

class GestorcoreModelEdicionCongreso extends JModelList
{
    var $datos;
    
    protected $msg;
    private $nomCongreso;

    /**
     * Returns a reference to the a Table object, always creating it.
     *
     * @param	type	The table type to instantiate
     * @param	string	A prefix for the table class name. Optional.
     * @param	array	Configuration array for model. Optional.
     * @return	JTable	A database object
     * @since	1.6
     */
    public function getTable($type = 'Gestorcore', $prefix = 'edicion', $config = array()) 
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
                    'orden', 
                    'lugar',
                    'limRecepcion',
                    'fechaDefinitiva',
                    'fechaInicio',
                    'fechaFin'
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
    protected function populateState($ordering = 'orden', $direction = 'asc') {
        // Set list state ordering defaults.
        parent::populateState($ordering, $direction);
    }
    
    /**
    * Method to build an SQL query to load the list data.
    *
    * @return	string	An SQL query
    */
    protected function getListQuery() {
        $db = JFactory::getDbo();
        // Orden y direccion
        $orderCol	= $this->state->get('list.ordering');
        $orderDirn	= $this->state->get('list.direction');
        
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from(array('#__edicion AS e', '#__congreso AS c'));
        $query->where('e.nomCongreso = c.nombre and c.nombre = ' . $db->quote($this->nomCongreso));
        $query->order($db->getEscaped($orderCol.' '.$orderDirn));
        return $query;
    }
    
    function setCongreso($congreso) {
        $this->nomCongreso = $congreso;
    }
    
    function getCongreso() {
        return $this->nomCongreso;
    }
    
    function getEdiciones() {
        $query = $this->getListQuery();
        //echo $query;
        $this->datos = $this->_getList( $query );
        return $this->datos;
    }
    
    /******************************Ediciones****************************************/
    
    function getEdicion($orden) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from($db->nameQuote(TABLE_EDICION) . ' as e');
        $query->where('e.orden = ' . $db->quote($orden) . ' and e.nomCongreso = ' . $db->quote($this->nomCongreso));
        $db->setQuery($query);
        return $db->loadObject();
    }       
    
    function insertEdicion($orden, $nomCongreso, $lugar, $linkEdicion, $limRecepcion, $fechaDefinitiva, $fechaInicio, $fechaFin) {
        $data = new stdClass();
        $data->orden = $orden;
        $data->nomCongreso = $nomCongreso;
        $data->lugar = $lugar;
        $data->linkEdicion = $linkEdicion;
        $data->limRecepcion = $limRecepcion;
        $data->fechaDefinitiva = $fechaDefinitiva;
        $data->fechaInicio = $fechaInicio;
        $data->fechaFin = $fechaFin;
        $db = JFactory::getDbo();
        $db->insertObject(TABLE_EDICION, $data);
        return $db->query()?true:false;
    }
    
    function deleteEdicion($cid) {
        $cids = implode(',', $cid );
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->delete($db->nameQuote(TABLE_EDICION));             
        $query->where($db->nameQuote('nomCongreso').'='.$db->quote($this->nomCongreso). ' and '.$db->nameQuote('orden').' IN (' . $cids . ')');
	$db->setQuery( $query );
        return $db->query()?true:false;
    }
    
    function updateEdicion($ordenAnt, $congresoAnt, $orden, $nomCongreso, $lugar, $linkEdicion, $limRecepcion, $fechaDefinitiva, $fechaInicio, $fechaFin) {
        $db = JFactory::getDbo();
        //$db->updateObject(TABLE_EDICION, $data, array('orden', 'nomCongreso'));
        $query = $db->getQuery(true);
        $query->update(TABLE_EDICION . ' as e');
        $query->set("e.orden=".$db->quote($orden). 
                    ", e.nomCongreso=".$db->quote($nomCongreso).
                    ", e.lugar=".$db->quote($lugar).
                    ", e.linkEdicion=".$db->quote($linkEdicion).
                    ", e.limRecepcion=".$db->quote($limRecepcion).
                    ", e.fechaDefinitiva=".$db->quote($fechaDefinitiva).
                    ", e.fechaInicio=".$db->quote($fechaInicio).
                    ", e.fechaFin=".$db->quote($fechaFin));
        $query->where("e.orden=".$db->quote($ordenAnt). " and e.nomCongreso=".$db->quote($congresoAnt));        
        $db->setQuery( $query );
        return $db->query()?true:false;
    }
    
    function existsEdicion($orden, $nomCongreso) {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('count(*) as contador');
        $query->from(TABLE_EDICION);
        $query->where($db->nameQuote('orden').'='.$db->quote($orden) . " and " .
                      $db->nameQuote('nomCongreso').'='.$db->quote($nomCongreso));
        $db->setQuery($query);
        return $db->loadObject()->contador > 0? true:false;
    }
    
    
    /***********************topicos de edicion************************/
    
    function getTopicosEdicion($orden) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('et.nomTopico, t.descripcion');
        $query->from(array(TABLE_EDICION_TOPICO . ' as et', TABLE_TOPICO . ' as t'));
        $query->where('et.nomTopico=t.nombre and '.$db->nameQuote('nomCongreso').'='.$db->quote($this->nomCongreso). ' and '.$db->nameQuote('ordenEdicion'). '=' . $orden);
        $db->setQuery( $query );
        return $db->loadObjectList();
    }
    
    function insertListTopicosEdicion($ordenEdicion, $nomCongreso, $listTopicos) {
        if ($nomCongreso == "") {$nomCongreso = $this->nomCongreso;}
        $db = JFactory::getDbo();
        foreach ($listTopicos as $topico) {
            $data = new stdClass();
            $data->ordenEdicion = $ordenEdicion;
            $data->nomCongreso = $nomCongreso;
            $data->nomTopico = $topico;
            $db->insertObject(TABLE_EDICION_TOPICO, $data);
        }
    }
    
    function insertTopicoEdicion($ordenEdicion, $nomCongreso, $topico) {
        $db = JFactory::getDbo();
        $data = new stdClass();
        $data->ordenEdicion = $ordenEdicion;
        $data->nomCongreso = $nomCongreso;
        $data->nomTopico = $topico;
        $db->insertObject(TABLE_EDICION_TOPICO, $data);
    }
    
    function insertTopico($nomTopico, $descripcion) {
        $data = new stdClass();
        $data->nombre = $nomTopico;
        $data->descripcion = $descripcion;
        $db = JFactory::getDbo();
        $db->insertObject(TABLE_TOPICO, $data);
        return $db->query()?true:false;
    }
    
    function deleteTopicoEdicion($orden, $nomCongreso, $topico) {
        if ($nomCongreso == "") {$nomCongreso = $this->nomCongreso;}
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->delete($db->nameQuote(TABLE_EDICION_TOPICO));             
        $query->where($db->nameQuote('ordenEdicion').'='.$db->quote($orden). ' and '.
                      $db->nameQuote('nomCongreso').'='.$db->quote($nomCongreso). ' and '.
                      $db->nameQuote('nomTopico').'='. $db->quote($topico));
	//echo $query;
        $db->setQuery( $query );
        return $db->query()?true:false;
    }
    
    
    
    /************************************************************/
    function deleteMensaje($cid) {
    	$db = JFactory::getDBO();
    	$cids = implode( ',', $cid );
	$query = 'DELETE FROM #__mensaje  WHERE id IN ( '. $cids .' )';
	$db->setQuery( $query );
	if (!$db->query()) {
		return 0;
	}
	return 1;
    }
    
    function insertMensaje($titulo, $mensaje){
    	$db = JFactory::getDBO();
    	$sql = "INSERT INTO #__mensaje (`titulo` ,`mensaje`) VALUES (". $db->quote($titulo) .", ".$db->quote($mensaje) .")";
    	$db->setQuery($sql);
    	if($db->query()){
    		return 1;
    	}else{
    		return 0;
    	}
    }
 
}

?>