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

class GestorcoreModelRevista extends JModelList
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
    * Method to build an SQL query to load the list data.
    *
    * @return	string	An SQL query
    */
    protected function getListQuery() {
        // Create a new query object.
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        // Select some fields
        $query->select('*');

        // From the hello table
        $query->from(TABLE_REVISTA);
        return $query;
    }
    
    function getRevistas() {
        $query = $this->getListQuery();
        //echo $query;
        $this->datos = $this->_getList( $query );
        return $this->datos;
    }
    
    function getRevista($issnRevista) {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from(TABLE_REVISTA);
        $query->where($db->nameQuote('issn').'='.$db->quote($issnRevista));
        $db->setQuery($query);
        return $db->loadObject();
    }
    
    function getVolumenes($issnRevista) {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('distinct volumen, max(numero) as max');
        $query->from(TABLE_ISSUE);
        $query->where($db->nameQuote('issnRevista').'='.$db->quote($issnRevista));
        $query->group('volumen');
        $db->setQuery($query);
        return $db->loadObjectList();
    }
    
    function getVolumenes_noSpecialIssue($issnRevista) {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('distinct volumen, max(numero) as max');
        $query->from(TABLE_ISSUE);
        $query->where($db->nameQuote('issnRevista').'='.$db->quote($issnRevista) . ' and volumen <> "specialIssue"');
        $query->group('volumen');
        $db->setQuery($query);
        return $db->loadObjectList();
    }
    
    function updateRevista($issnAnt, $issn, $nombre, $acronimo, $linkRevista, $periodicidad, $nomEditorial) {
        $db = JFactory::getDbo();
        $nomEditorial = $nomEditorial == null? 'NULL':$db->quote($nomEditorial);
        $query = $db->getQuery(true);
        $query->update(TABLE_REVISTA . ' as r');
        $query->set("r.issn=".$db->quote($issn) .
                    ", r.nombre=".$db->quote($nombre). 
                    ", r.acronimo=".$db->quote($acronimo).
                    ", r.linkRevista=".$db->quote($linkRevista).
                    ", r.periodicidad=".$db->quote($periodicidad).
                    ", r.nomEditorial=".$nomEditorial);
        $query->where("r.issn=" . $db->quote($issnAnt));        
        $db->setQuery( $query );
        //echo $query;
        return $db->query()?true:false;
    }
    
    function insertRevista($issn, $nomEntidad, $acronimoEntidad, $linkEntidad, $periodicidadEntidad, $nomEditorial) {
        $data = new stdClass();
        $data->issn = $issn;
        $data->nombre = $nomEntidad;
        $data->acronimo = $acronimoEntidad;
        $data->linkRevista = $linkEntidad;
        $data->periodicidad = $periodicidadEntidad;
        $data->nomEditorial = $nomEditorial;
        $db = JFactory::getDbo();
        $db->insertObject(TABLE_REVISTA, $data);
        return $db->query()?true:false;
    }
    
    function existsRevista($issnRevista) {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('count(*) as contador');
        $query->from(TABLE_REVISTA);
        $query->where($db->nameQuote('issn').'='.$db->quote($issnRevista));
        $db->setQuery($query);
        return $db->loadObject()->contador > 0? true:false;
    }
    
    function deleteRevista($issnRevista) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->delete($db->nameQuote(TABLE_REVISTA));             
        $query->where($db->nameQuote('issn').'='.$db->quote($issnRevista));
        $db->setQuery( $query );
        return $db->query()?true:false;
    }
    
    
    /************************TOPICOS****************************/
    function getTopicosFromRevista($issnRevista) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('rt.nomTopico as nomTopico');
        $query->from(TABLE_REVISTA_TOPICO . ' AS rt');
        $query->where('rt.issnRevista =' . $db->quote($issnRevista));
        $db->setQuery($query);
        return $db->loadObjectList();
    }
    
    function insertTopicoRevista($issnRevista, $topico) {
        $db = JFactory::getDbo();
        $data = new stdClass();
        $data->issnRevista = $issnRevista;
        $data->nomTopico = $topico;
        return $db->insertObject(TABLE_REVISTA_TOPICO, $data);
    }
    
    function deleteTopicoRevista($issnRevista, $nomTopico) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->delete($db->nameQuote(TABLE_REVISTA_TOPICO));             
        $query->where($db->nameQuote('issnRevista').'='.$db->quote($issnRevista). ' and '.
                      $db->nameQuote('nomTopico').'='. $db->quote($nomTopico));
        $db->setQuery( $query );
        return $db->query()?true:false;
    }
    
    
    function buscaCadena($cadena) {
        $db = JFactory::getDBO();
        $query = "select nombre from " . TABLE_REVISTA . " where nombre like '%" . $cadena . "%'";
        $db->setQuery($query);
        return $db->loadObjectList();
    }
    
    
    
}

?>