<?php

defined('_JEXEC') or die();
jimport( 'joomla.application.component.modellist' );

class GestorcoreModelEditorial extends JModelList
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
        $query->from(TABLE_EDITORIAL);
        return $query;
    }
   
    function getEditoriales() {
        $query = $this->getListQuery();
        return $this->_getList( $query );
    }
    
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
    
    function deleteEditorial($nomEditorial) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->delete($db->nameQuote(TABLE_EDITORIAL));             
        $query->where($db->nameQuote('nombre').'='.$db->quote($nomEditorial));
        $db->setQuery( $query );
        return $db->query()?true:false;
    }
    
    function getNumEntidadesPublicadoras($nomEditorial, $tipo) {
        $tabla = $tipo == 'revista'? TABLE_REVISTA:TABLE_CONGRESO;
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('count(*) as contador');
        $query->from(array(TABLE_EDITORIAL . ' as e', $tabla . ' as ep'));
        $query->where('e.nombre=ep.nomEditorial AND e.nombre='.$db->quote($nomEditorial));
        $db->setQuery($query);
        return $db->loadObject()->contador;
        
    }
    
    
}

?>