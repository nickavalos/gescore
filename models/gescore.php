<?php

defined('_JEXEC') or die();
jimport( 'joomla.application.component.modellist' );

class GestorcoreModelGescore extends JModelList
{
    var $datos;
    var $typeEditorial = 'editorial';
    var $typeCongreso = 'congreso';
    var $typeEdicion = 'edicion';
    var $typeRevista = 'revista';
    var $typeIssue = 'issue';
    var $typeEntidadCalidad = 'entidadCalidad';
    var $typeTopico = 'topico';
    var $typeAll = '*';
    
    var $categoryCongreso = 'Congreso';
    var $categoryRevista = 'Revista';
    var $categoryEdicion = 'Edición';
    var $categoryEditorial = 'Editorial';
    var $categoryEntidadIndiceCalidad = 'Entidad de Calidad';
    var $categoryIssue = 'Issue';
    var $categoryTopico = 'Tópico';
    
    var $filterIncluirTopicos_on = 'checked';
    
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
    public function __construct_copy($config = array()) {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                    'id', 'a.id',
                    'title', 'a.title',
                    'alias', 'a.alias',
                    'checked_out', 'a.checked_out',
                    'checked_out_time', 'a.checked_out_time',
                    'catid', 'a.catid', 'category_title',
                    'published', 'a.published',
                    'access', 'a.access', 'access_level',
                    'ordering', 'a.ordering',
                    'language', 'a.language',
                    'created_time', 'a.created_time',
                    'created_user_id', 'a.created_user_id',
                    'modified_time', 'a.modified_time',
                    'modified_user_id', 'a.modified_user_id',
            );
        }

        parent::__construct($config);
    }
    public function __construct($config = array()) {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                    'id', 'a.id',
                    'title', 'a.title',
                    'acronimo', 'a.acronimo',
                    'categoria', 'a.categoria',
                    'fecha', 'a.fecha',
                    'linkEntidad', 'a.linkEntidad',
                    'lugar'
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
    protected function populateState($ordering = 'title', $direction = 'asc')
    {
        // Initialise variables.
        $app = JFactory::getApplication();

        $filterSearch = $app->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
        $this->setState('filter.search', $filterSearch);
        
        $filterTypeSearch = $app->getUserStateFromRequest($this->context.'.filter.typeSearch', 'filter_typeSearch');
        $this->setState('filter.typeSearch', $filterTypeSearch);
        
        //$filterIncluirTopico = $app->getUserStateFromRequest($this->context.'.filter.incluirTopico', 'filter_incluirTopico');
        $filterIncluirTopico = $app->getUserStateFromRequest($this->context.'.filter.typeSearch', 'filter_incluirTops', '');
        $this->setState('filter.incluirTopico', $filterIncluirTopico);
        
        //subfilter ediciones
        $subfilter_edicion_lugar = $app->getUserStateFromRequest($this->context.'.subfilter.edicion.lugar', 'subfilter_edicion_lugar');
        $this->setState('subfilter.edicion.lugar', $subfilter_edicion_lugar);
        $subfilter_edicion_limRecep = $app->getUserStateFromRequest($this->context.'.subfilter.edicion.limRecep', 'subfilter_edicion_limRecep');
        $this->setState('subfilter.edicion.limRecep', $subfilter_edicion_limRecep);
        $subfilter_edicion_limDefinitiva = $app->getUserStateFromRequest($this->context.'.subfilter.edicion.limDefinitiva', 'subfilter_edicion_limDefinitiva');
        $this->setState('subfilter.edicion.limDefinitiva', $subfilter_edicion_limDefinitiva);
        $subfilter_edicion_fechaInicio = $app->getUserStateFromRequest($this->context.'.subfilter.edicion.fechaInicio', 'subfilter_edicion_fechaInicio');
        $this->setState('subfilter.edicion.fechaInicio', $subfilter_edicion_fechaInicio);
        $subfilter_edicion_fechaFin = $app->getUserStateFromRequest($this->context.'.subfilter.edicion.fechaFin', 'subfilter_edicion_fechaFin');
        $this->setState('subfilter.edicion.fechaFin', $subfilter_edicion_fechaFin);
        
        $subfilter_edicion_limRecep_max = $app->getUserStateFromRequest($this->context.'.subfilter.edicion.limRecep_max', 'subfilter_edicion_limRecep_max');
        $this->setState('subfilter.edicion.limRecep_max', $subfilter_edicion_limRecep_max);
        $subfilter_edicion_limDefinitiva_max = $app->getUserStateFromRequest($this->context.'.subfilter.edicion.limDefinitiva_max', 'subfilter_edicion_limDefinitiva_max');
        $this->setState('subfilter.edicion.limDefinitiva_max', $subfilter_edicion_limDefinitiva_max);
        $subfilter_edicion_fechaInicio_max = $app->getUserStateFromRequest($this->context.'.subfilter.edicion.fechaInicio_max', 'subfilter_edicion_fechaInicio_max');
        $this->setState('subfilter.edicion.fechaInicio_max', $subfilter_edicion_fechaInicio_max);
        $subfilter_edicion_fechaFin_max = $app->getUserStateFromRequest($this->context.'.subfilter.edicion.fechaFin_max', 'subfilter_edicion_fechaFin_max');
        $this->setState('subfilter.edicion.fechaFin_max', $subfilter_edicion_fechaFin_max);
        
        // subfilter issues
        $subfilter_issue_limRecep = $app->getUserStateFromRequest($this->context.'.subfilter.issue.limRecep', 'subfilter_issue_limRecep');
        $this->setState('subfilter.issue.limRecep', $subfilter_issue_limRecep);
        $subfilter_issue_limDefinitiva = $app->getUserStateFromRequest($this->context.'.subfilter.issue.limDefinitiva', 'subfilter_issue_limDefinitiva');
        $this->setState('subfilter.issue.limDefinitiva', $subfilter_issue_limDefinitiva);
        $subfilter_issue_fechaPublicacionMin = $app->getUserStateFromRequest($this->context.'.subfilter.edicion.fechaPublicacion_min', 'subfilter_issue_fechaPublicacionMin');
        $this->setState('subfilter.edicion.fechaPublicacion_min', $subfilter_issue_fechaPublicacionMin);
        
        $subfilter_issue_limRecep_max = $app->getUserStateFromRequest($this->context.'.subfilter.issue.limRecep_max', 'subfilter_issue_limRecep_max');
        $this->setState('subfilter.issue.limRecep_max', $subfilter_issue_limRecep_max);
        $subfilter_issue_limDefinitiva_max = $app->getUserStateFromRequest($this->context.'.subfilter.issue.limDefinitiva_max', 'subfilter_issue_limDefinitiva_max');
        $this->setState('subfilter.issue.limDefinitiva_max', $subfilter_issue_limDefinitiva_max);
        $subfilter_issue_fechaPublicacionMax = $app->getUserStateFromRequest($this->context.'.subfilter.edicion.fechaPublicacion_max', 'subfilter_issue_fechaPublicacionMax');
        $this->setState('subfilter.edicion.fechaPublicacion_max', $subfilter_issue_fechaPublicacionMax);
        
        // Set list state ordering defaults.
        parent::populateState($ordering, $direction);
    }

    protected function getListQuery() {
        $db	= $this->getDbo();
        //echo $this->getState('filter.incluirTopico');
        $search = $this->getState('filter.search');
//        if (empty($search)) { //query vacia
//            $query = 'SELECT e.nombre as id, e.nombre as title, e.siglas as acronimo, \'Editorial\' as categoria, \'\' as fecha, e.link as linkEntidad '
//                    . 'FROM ' . TABLE_EDITORIAL . ' AS e '
//                    . 'WHERE e.nombre=null';
//            return $query;
//        }
        //$sql_keyword = "%{$this->getState('filter.search')}%";
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
        $queryEdicion   = 'SELECT CONCAT(ed.orden, "%", ed.nomCongreso) as id, CONCAT(ed.orden, "%", ed.nomCongreso) as title, \'\' as acronimo, \'Edición\' as categoria, ed.fechaInicio as fecha, ed.lugar as lugar, ed.linkEdicion as linkEntidad '
                        . 'FROM ' . TABLE_EDICION . ' AS ed ';
                        //. $where_edicion;
        $queryIssue     = 'SELECT CONCAT(i.volumen, "%", i.numero, "%", i.issnRevista) as id, CONCAT(i.volumen, "%", i.numero, "%", r.nombre) as title, \'\' as acronimo, \'Issue\' as categoria, i.fechaPublicacion as fecha, \'\' as lugar, \'\' as linkEntidad '
                        . 'FROM ' . TABLE_ISSUE . ' AS i, ' . TABLE_REVISTA . ' AS r ';
                        //. 'WHERE i.volumen=null';
        $queryCalidad   = 'SELECT ec.nombre as id, ec.nombre as title, ec.siglas as acronimo, \'Entidad de Calidad\' as categoria, \'\' as fecha, \'\' as lugar, ec.link as linkEntidad '
                        . 'FROM ' . TABLE_ENTIDAD_INDICE_CALIDAD . ' AS ec '
                        . $where_entidad_calidad;
        $queryTopico    = 'SELECT t.nombre as id, t.nombre as title, \'\' as acronimo, \'Tópico\' as categoria, \'\' as fecha, \'\' as lugar, \'\' as linkEntidad '
                        . 'FROM ' . TABLE_TOPICO . ' AS t '
                        . $where_topico;
        $queryEdicionTopico= 'SELECT CONCAT(ed.orden, "%", ed.nomCongreso) as id, CONCAT(ed.orden, "%", ed.nomCongreso, "%", et.nomTopico) as title, \'\' as acronimo, \'Edición\' as categoria, ed.fechaInicio as fecha, ed.lugar as lugar, ed.linkEdicion as linkEntidad '
                        . 'FROM ' . TABLE_EDICION . ' AS ed, ' . TABLE_EDICION_TOPICO . ' AS et '
                        . $where_edicion_topico;
        $queryIssueTopico= 'SELECT CONCAT(i.volumen, "%", i.numero, "%", i.issnRevista) as id, CONCAT(i.volumen, "%", i.numero, "%", r.nombre, "%", it.nomTopico) as title, \'\' as acronimo, \'Issue\' as categoria, i.fechaPublicacion as fecha, \'\' as lugar, \'\' as linkEntidad '
                        . 'FROM ' . TABLE_ISSUE . ' AS i, ' . TABLE_ISSUE_TOPICO . ' AS it, ' . TABLE_REVISTA . ' AS r '
                        . $where_issue_topico;
        
        $where_edicion_sub = array();
        $where_issue_sub = array();
        // Add the list ordering clause.
        $orderCol	= $this->state->get('list.ordering');
        $orderDirn	= $this->state->get('list.direction');
        
        $allowBusquedaTopicos = false; //permite búsqueda en los topicos
        switch($this->state->get('filter.typeSearch')) {
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
                $subfilter_lugar = $this->getState('subfilter.edicion.lugar');
                $subfilter_limRecep = $this->getState('subfilter.edicion.limRecep');
                $subfilter_limDefinitiva = $this->getState('subfilter.edicion.limDefinitiva');
                $subfilter_inicio = $this->getState('subfilter.edicion.fechaInicio');
                $subfilter_fin = $this->getState('subfilter.edicion.fechaFin');
                $subfilter_limRecep_max = $this->getState('subfilter.edicion.limRecep_max');
                $subfilter_limDefinitiva_max = $this->getState('subfilter.edicion.limDefinitiva_max');
                $subfilter_inicio_max = $this->getState('subfilter.edicion.fechaInicio_max');
                $subfilter_fin_max = $this->getState('subfilter.edicion.fechaFin_max');
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
                $subfilter_issue_limRecep = $this->getState('subfilter.issue.limRecep');
                $subfilter_issue_fechaDef = $this->getState('subfilter.issue.limDefinitiva');
                $subfilter_issue_publicacionMin = $this->getState('subfilter.edicion.fechaPublicacion_min');
                $subfilter_issue_limRecep_max = $this->getState('subfilter.issue.limRecep_max');
                $subfilter_issue_fechaDef_max = $this->getState('subfilter.issue.limDefinitiva_max');
                $subfilter_issue_publicacionMax = $this->getState('subfilter.edicion.fechaPublicacion_max');
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
                . ' UNION ALL '
                . $queryCongreso
                . ' UNION ALL '
                . $queryRevista
                . ' UNION ALL '
                . $queryEdicion
                . ' UNION ALL '
                . $queryCalidad
                . ' UNION ALL '
                . $queryTopico
                ;
                $allowBusquedaTopicos = true;
                break;   
        }
        if ($this->state->get('filter.incluirTopico') == $this->filterIncluirTopicos_on && $allowBusquedaTopicos) {
            switch($this->state->get('filter.typeSearch')) {
                case $this->typeEdicion:
                    if (!empty($where_edicion_sub)) { // hay subfiltros
                        $query = $queryEdicionTopico; //dejamos de tener en cuenta lo que habiamos buscado antes
                        $query .= ' AND ' . implode( ' AND ', $where_edicion_sub );
                    } else {
                        $query .= ' UNION ALL '
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
                    $query .= ' UNION ALL '
                    . $queryEdicionTopico
                    . ' UNION ALL '
                    . $queryIssueTopico;
                    break;
            }
            
        }
        $query .= ' ORDER BY '. $orderCol . ' ' . $orderDirn;
        /* // añadir otro campo 'encontrado en' que indique que ha sido encontrado en el topico -> solo si buscas topicos o ediciones, no en la busqueda general
         . 'SELECT ed.orden as id, ed.nomCongreso as title, \'\' as acronimo, \'Edición\' as categoria, \'ed.fechaInicio\' as fecha, \'\' as linkEntidad '
                . 'FROM ' . TABLE_EDICION . ' AS ed, ' . TABLE_EDICION_TOPICO . ' AS et'
                . $where_edicion_topico
         */
        //echo nl2br(str_replace('#__','jos_',$query));
        return $query;
    }
    
    function formatToTable($dateString) {
        return date('Ymd', strtotime(str_replace('/', '-', $dateString)));    
    }
    
    /**
     * Method to get a store id based on model configuration state.
     *
     * This is necessary because the model is used by the component and
     * different modules that might need different sets of data or different
     * ordering requirements.
     *
     * @param	string		$id	A prefix for the store id.
     *
     * @return	string		A store id.
     * @since	1.6
     */
    protected function getStoreId($id = '')
    {
        // Compile the store id.
        $id	.= ':'.$this->getState('filter.search');
        $id     .= ':'.$this->getState('filter.access');
        $id	.= ':'.$this->getState('filter.published');
        $id	.= ':'.$this->getState('filter.category_id');
        $id	.= ':'.$this->getState('filter.language');

        return parent::getStoreId($id);
    }
    
    function getTypesFilter () {
        // Build the active state filter options.
        $options        = array();
        $options[]      = JHtml::_('select.option', $this->typeEditorial, JText::_('COM_GESCORE_TABLE_EDITORIAL_NAME'));
        $options[]      = JHtml::_('select.option', $this->typeCongreso, JText::_('COM_GESCORE_TABLE_CONGRESO_NAME'));
        $options[]      = JHtml::_('select.option', $this->typeEdicion, '--- '.JText::_('COM_GESCORE_TABLE_EDICION_NAME'));
        $options[]      = JHtml::_('select.option', $this->typeRevista, JText::_('COM_GESCORE_TABLE_REVISTA_NAME'));
        $options[]      = JHtml::_('select.option', $this->typeIssue, '--- '.JText::_('COM_GESCORE_TABLE_ISSUE_NAME'));
        $options[]      = JHtml::_('select.option', $this->typeEntidadCalidad, JText::_('COM_GESCORE_TABLE_ENTIDAD_CALIDAD_NAME'));
        $options[]      = JHtml::_('select.option', $this->typeTopico, JText::_('COM_GESCORE_TABLE_TOPICO_NAME'));
        $options[]      = JHtml::_('select.option', $this->typeAll, JText::_('COM_GESCORE_TABLE_ALL_NAME'));

        return $options;
    }
    
    function getTypesCategory() {
        return array('congreso' => $this->categoryCongreso, 'revista' => $this->categoryRevista, 'edicion' => $this->categoryEdicion,
                     'editorial' => $this->categoryEditorial, 'entidadCalidad' => $this->categoryEntidadIndiceCalidad,
                     'issue' => $this->categoryIssue, 'topico' =>$this->categoryTopico);
    }
    
    function getResults_rapidSearch($filter) {
        $db = JFactory::getDBO();
        $sql_keyword =  $db->Quote('%'.$filter.'%');
        
        $where_editorial[] = 'e.nombre LIKE ' . $sql_keyword;
        //$where_editorial[] = 'e.siglas LIKE ' . $sql_keyword;
        
        $where_congreso[] = 'c.nombre LIKE '  . $sql_keyword;
        //$where_congreso[] = 'c.acronimo LIKE '  . $sql_keyword;
        
        $where_revista[] = 'r.nombre LIKE ' . $sql_keyword;
        //$where_revista[] = 'r.acronimo LIKE ' . $sql_keyword;
        $where_revista[] = 'r.issn LIKE ' . $sql_keyword;
        
        $where_edicion[] = 'ed.lugar LIKE ' . $sql_keyword;
        
        $where_entidad_calidad[] = 'ec.nombre LIKE ' . $sql_keyword;
        //$where_entidad_calidad[] = 'ec.siglas LIKE ' . $sql_keyword;
        
        $where_topico[] = 't.nombre LIKE ' . $sql_keyword;
        //$where_topico[] = 't.descripcion LIKE ' . $sql_keyword;
        
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
        $queryEdicion   = 'SELECT CONCAT(ed.orden, "%%", ed.nomCongreso) as id, CONCAT(ed.nomCongreso, " - edición ", ed.orden , " en ", ed.lugar) as title, \'\' as acronimo, \'Edición\' as categoria, ed.fechaInicio as fecha, ed.lugar as lugar, ed.linkEdicion as linkEntidad '
                        . 'FROM ' . TABLE_EDICION . ' AS ed '
                        . $where_edicion;
        $queryIssue     = 'SELECT CONCAT(i.volumen, "%%", i.numero, "%%", i.issnRevista) as id, CONCAT(i.volumen, "%%", i.numero, "%%", r.nombre) as title, \'\' as acronimo, \'Issue\' as categoria, i.fechaPublicacion as fecha, \'\' as lugar, \'\' as linkEntidad '
                        . 'FROM ' . TABLE_ISSUE . ' AS i, ' . TABLE_REVISTA . ' AS r ';
                        //. 'WHERE i.volumen=null';
        $queryCalidad   = 'SELECT ec.nombre as id, ec.nombre as title, ec.siglas as acronimo, \'Entidad de Calidad\' as categoria, \'\' as fecha, \'\' as lugar, ec.link as linkEntidad '
                        . 'FROM ' . TABLE_ENTIDAD_INDICE_CALIDAD . ' AS ec '
                        . $where_entidad_calidad;
        $queryTopico    = 'SELECT t.nombre as id, t.nombre as title, \'\' as acronimo, \'Topico\' as categoria, \'\' as fecha, \'\' as lugar, \'\' as linkEntidad '
                        . 'FROM ' . TABLE_TOPICO . ' AS t '
                        . $where_topico;
        $queryEdicionTopico= 'SELECT CONCAT(ed.orden, "%%", ed.nomCongreso) as id, CONCAT(ed.orden, "%%", ed.nomCongreso, "%%", et.nomTopico) as title, \'\' as acronimo, \'Edición\' as categoria, ed.fechaInicio as fecha, ed.lugar as lugar, ed.linkEdicion as linkEntidad '
                        . 'FROM ' . TABLE_EDICION . ' AS ed, ' . TABLE_EDICION_TOPICO . ' AS et '
                        . $where_edicion_topico;
        $queryIssueTopico= 'SELECT CONCAT(i.volumen, "%%", i.numero, "%%", i.issnRevista) as id, CONCAT(i.volumen, "%%", i.numero, "%%", r.nombre, "%%", it.nomTopico) as title, \'\' as acronimo, \'Issue\' as categoria, i.fechaPublicacion as fecha, \'\' as lugar, \'\' as linkEntidad '
                        . 'FROM ' . TABLE_ISSUE . ' AS i, ' . TABLE_ISSUE_TOPICO . ' AS it, ' . TABLE_REVISTA . ' AS r '
                        . $where_issue_topico;
        $query = $queryCongreso
                . ' UNION ALL '
                . $queryRevista
                . ' UNION ALL '
                . $queryEdicion
                . ' UNION ALL '
                . $queryTopico
                . ' UNION ALL '
                . $queryCalidad
                . ' UNION ALL '
                . $queryEditorial
                ;
        $db->setQuery($query);
        return $db->loadObjectList();
    }
    
}

?>