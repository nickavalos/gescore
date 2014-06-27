<?php

defined('_JEXEC') or die();
jimport( 'joomla.application.component.modellist' );

class GestorcoreModelInfoTopico extends JModelList
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
                    'entidadPublicadora',
                    'orden_volumen',
                    'numero',
                    'categoria'
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
        
        $query = 'SELECT ct.nomCongreso AS id, ct.nomCongreso AS entidadPublicadora, \'-\' AS orden_volumen, \'-\' AS numero, \'Congreso\' AS categoria '
                .'FROM ' . TABLE_CONGRESO_TOPICO . ' AS ct '
                .'WHERE ct.nomTopico = ' . $db->quote(JRequest::getVar('id'))
                .' UNION ALL '
                .'SELECT rt.issnRevista AS id, r.nombre AS entidadPublicadora, \'-\' AS orden_volumen, \'-\' AS numero, \'Revista\' AS categoria '
                .'FROM ' . TABLE_REVISTA_TOPICO . ' AS rt, ' . TABLE_REVISTA . ' AS r '
                .'WHERE rt.nomTopico = ' . $db->quote(JRequest::getVar('id')) . ' and rt.issnRevista = r.issn '
                .' UNION ALL '
                .'SELECT CONCAT(et.ordenEdicion, "%%", et.nomCongreso) AS id, et.nomCongreso AS entidadPublicadora, et.ordenEdicion AS orden_volumen, \'-\' AS numero, \'Edición\' AS categoria '
                .'FROM ' . TABLE_EDICION_TOPICO . ' AS et '
                .'WHERE et.nomTopico = ' . $db->quote(JRequest::getVar('id'))
                .' UNION ALL '
                .'SELECT CONCAT(it.volumenIssue, "%%", it.numeroIssue, "%%", it.issnRevista) AS id, r.nombre AS entidadPublicadora, it.volumenIssue AS orden_volumen, it.numeroIssue AS numero, \'Issue\' AS categoria '
                .'FROM ' . TABLE_ISSUE_TOPICO . ' AS it, ' . TABLE_REVISTA . ' AS r '
                .'WHERE it.nomTopico = ' . $db->quote(JRequest::getVar('id')) . ' and it.issnRevista = r.issn '
                .'ORDER BY '. $orderCol . ' ' . $orderDirn;
                
        
        return $query;
    }
    
    function getTopico($nombre) {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from(TABLE_TOPICO);
        $query->where('nombre = ' . $db->quote($nombre));
        $db->setQuery($query);
        return $db->loadObject();
    }
    
    function getTopicosSimilares($nomTopico) {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from(TABLE_TOPICO_SIMILAR);
        $query->where($db->nameQuote('nomTopico')."=".$db->quote($nomTopico));
        $db->setQuery($query);
        return $db->loadObjectList();
    }
    
    /*
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
    */
}

?>