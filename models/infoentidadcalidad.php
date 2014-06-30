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

class GestorcoreModelInfoEntidadCalidad extends JModelList
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
                    //info Entidad Calidad
                    'entidadPublicadora',
                    'categoria',
                    'ordenVolumen',
                    'numero',
                    'indice'
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
    protected function populateState($ordering = 'categoria', $direction = 'asc') {
        // Set list state ordering defaults.
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
        $query = 'SELECT CONCAT(ve.ordenEdicion, "%%", ve.nomCongreso) AS id, ve.nomCongreso AS entidadPublicadora, \'Edición\' AS categoria,  ve.ordenEdicion AS ordenVolumen, \'-\' AS numero, ve.indice '
                .'FROM ' . TABLE_VALORACION_EDICION . ' AS ve '
                .'WHERE ve.nomEntidad = ' . $db->quote(JRequest::getVar('id'))
                .' UNION ALL '
                .'SELECT CONCAT(vi.volumenIssue, "%%", vi.numeroIssue, "%%", vi.issnRevista), r.nombre AS entidadPublicadora, \'Issue\' AS categoria,  vi.volumenIssue AS ordenVolumen, vi.numeroIssue AS numero, vi.indice '
                .'FROM ' . TABLE_VALORACION_ISSUE . ' AS vi, ' . TABLE_REVISTA . ' AS r '
                .'where vi.nomEntidad = '. $db->quote(JRequest::getVar('id')) . ' and vi.issnRevista = r.issn '
                .'ORDER BY '. $orderCol . ' ' . $orderDirn;
        return $query;
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
    
    function getValoracionesEntidad($type, $nomEntidad) {
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
    }
    
}

?>