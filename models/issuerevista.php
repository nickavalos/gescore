<?php

defined('_JEXEC') or die();
jimport( 'joomla.application.component.modellist' );

class GestorcoreModelIssueRevista extends JModelList
{
    var $datos;
    
    protected $msg;
    private $issnRevista;

    /**
     * Returns a reference to the a Table object, always creating it.
     *
     * @param	type	The table type to instantiate
     * @param	string	A prefix for the table class name. Optional.
     * @param	array	Configuration array for model. Optional.
     * @return	JTable	A database object
     * @since	1.6
     */
    public function getTable($type = 'Gestorcore', $prefix = 'issue', $config = array()) 
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
                    'volumen', 
                    'numero',
                    'limRecepcion',
                    'fechaDefinitiva',
                    'fechaPublicacion'
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
    protected function populateState($ordering = 'volumen', $direction = 'asc') {
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
        
        $orderCol	= $this->state->get('list.ordering');
        $orderDirn	= $this->state->get('list.direction');
        
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from(array(TABLE_ISSUE . ' AS i', TABLE_REVISTA . ' AS r'));
        $query->where('i.issnRevista = r.issn and r.issn = ' . $db->quote($this->issnRevista));
        $query->order($db->getEscaped($orderCol.' '.$orderDirn));
        return $query;
    }
    
    function setRevista($revista) {
        $this->issnRevista = $revista;
    }
    
    function getRevista() {
        return $this->issnRevista;
    }
    
    function getIssues() {
        $query = $this->getListQuery();
        //echo $query;
        $this->datos = $this->_getList( $query );
        return $this->datos;
    }
    
    function getIssue($volumen, $numero) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from($db->nameQuote(TABLE_ISSUE) . ' as i');
        $query->where('i.volumen=' . $db->quote($volumen) . ' and i.numero=' . $db->quote($numero) .
                      ' and i.issnRevista = ' . $db->quote($this->issnRevista));
        $db->setQuery($query);
        return $db->loadObject();
    }
    
    function insertIssue($volumen, $numero, $issnRevista, $limRecepcion, $fechaDefinitiva, $fechaPublicacion, $temaPrincipal = null) {
        $data = new stdClass();
        $data->volumen = $volumen;
        $data->numero = $numero;
        $data->issnRevista = $issnRevista;
        $data->limRecepcion = $limRecepcion;
        $data->fechaDefinitiva = $fechaDefinitiva;
        $data->fechaPublicacion = $fechaPublicacion;
        $data->temaPrincipal = $temaPrincipal;
        $db = JFactory::getDbo();
        $db->insertObject(TABLE_ISSUE, $data);
        return $db->query()?true:false;
    }
    
    function updateIssue($volumenAnt, $numeroIssueAnt, $issnAnt, $volumen, $numeroIssue, $issnRevista, $limRecepcion, $fechaDefinitiva, $fechaPublicacion, $temaPrincipal = null) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->update(TABLE_ISSUE . ' as i');
        $tema = $temaPrincipal == null? 'NULL':$db->quote($temaPrincipal);
        $query->set("i.volumen=".$db->quote($volumen). 
                    ", i.numero=".$db->quote($numeroIssue).
                    ", i.issnRevista=".$db->quote($issnRevista).
                    ", i.limRecepcion=".$db->quote($limRecepcion).
                    ", i.fechaDefinitiva=".$db->quote($fechaDefinitiva).
                    ", i.fechaPublicacion=".$db->quote($fechaPublicacion).
                    ", i.temaPrincipal=".$tema);
        $query->where("i.volumen=".$db->quote($volumenAnt). " and i.numero=".$db->quote($numeroIssueAnt) . " and i.issnRevista=".$db->quote($issnAnt));        
        $db->setQuery( $query );
        return $db->query()?true:false;
    }
    
    function existsIssue($volumen, $numero, $issnRevista) {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('count(*) as contador');
        $query->from(TABLE_ISSUE);
        $query->where($db->nameQuote('volumen').'='.$db->quote($volumen) . " and " .
                      $db->nameQuote('numero').'='.$db->quote($numero) . " and " .
                      $db->nameQuote('issnRevista').'='.$db->quote($issnRevista));
        $db->setQuery($query);
        return $db->loadObject()->contador > 0? true:false;
    }
    
    function deleteIssue($volumen, $numero) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->delete($db->nameQuote(TABLE_ISSUE));             
        $query->where($db->nameQuote('volumen').'='.$db->quote($volumen) . " and " .
                      $db->nameQuote('numero').'='.$db->quote($numero) . " and " .
                      $db->nameQuote('issnRevista').'='.$db->quote($this->issnRevista));
	$db->setQuery( $query );
        return $db->query()?true:false;
    }
    
    function getUltimoNumeroVolumenIssue($issnRevista, $volumen) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('max(i.numero) as max');
        $query->from(array(TABLE_ISSUE . ' AS i'));
        $query->where('i.issnRevista=' . $db->quote($issnRevista) . ' and ' . 'i.volumen=' . $db->quote($volumen));
        $db->setQuery($query);
        return $db->loadObject();
    }
    
    /***********************************TOPICO******************************************/
    function getTopicosIssue($volumen, $numero) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('it.nomTopico, t.descripcion');
        $query->from(array(TABLE_ISSUE_TOPICO . ' as it', TABLE_TOPICO . ' as t'));
        $query->where('it.nomTopico=t.nombre and it.issnRevista=' . $db->quote($this->issnRevista). ' and '.
                      'it.volumenIssue=' . $db->quote($volumen) . ' and it.numeroIssue=' . $db->quote($numero));
        $db->setQuery( $query );
        return $db->loadObjectList();
    }
    
    function insertListTopicosIssue($volumen, $numero, $issnRevista, $listTopicos) {
        if ($issnRevista == "") {$issnRevista = $this->issnRevista;}
        //$db = JFactory::getDbo();
        foreach ($listTopicos as $topico) {
            $this->insertTopicoIssue($volumen, $numero, $issnRevista, $topico);
        }
    }
    
    function insertTopicoIssue($volumen, $numero, $issnRevista, $topico) {
        $db = JFactory::getDbo();
        $data = new stdClass();
        $data->volumenIssue = $volumen;
        $data->numeroIssue = $numero;
        $data->issnRevista = $issnRevista;
        $data->nomTopico = $topico;
        $db->insertObject(TABLE_ISSUE_TOPICO, $data);
    }
    
    function insertTopico($nomTopico, $descripcion) {
        $data = new stdClass();
        $data->nombre = $nomTopico;
        $data->descripcion = $descripcion;
        $db = JFactory::getDbo();
        $db->insertObject(TABLE_TOPICO, $data);
        return $db->query()?true:false;
    }
    
    function deleteTopicoIssue($volumen, $numero, $topico) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->delete($db->nameQuote(TABLE_ISSUE_TOPICO));             
        $query->where($db->nameQuote('volumenIssue').'='.$db->quote($volumen). ' and '.
                      $db->nameQuote('numeroIssue').'='.$db->quote($numero). ' and '.
                      $db->nameQuote('issnRevista').'='.$db->quote($this->issnRevista). ' and '.
                      $db->nameQuote('nomTopico').'='. $db->quote($topico));
	//echo $query;
        $db->setQuery( $query );
        return $db->query()?true:false;
    }
}

?>