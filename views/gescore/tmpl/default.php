<?php
/**
 * Home Congresos, Gestor de congresos y revistas.
 *
 * @version		1.0 chdemko $
 * @package		Congreso
 * @subpackage          Components
 * @copyright           Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @author		Christophe Demko
 * @link		http://google.com/+nickavalos 
 * @license		License GNU General Public License version 2 or later
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
// load tooltip behavior
JHtml::_('behavior.tooltip');

//JHTML::script("jquery-1.9.1.js", "http://code.jquery.com/");
//JHTML::script("jquery-ui.js", "http://code.jquery.com/ui/1.10.3/");

$this->document = JFactory::getDocument();
$this->document->addScript(DIR_SCRIPTS_GESCORE . "jquery-1-9-1.js");
$this->document->addScript(DIR_SCRIPTS_GESCORE . "jquery-ui.js");

//$this->document->addScript(DIR_SCRIPTS_GESCORE . 'utils.js');
$this->document->addScript(DIR_SCRIPTS_GESCORE . 'submenu.js');
$this->document->addScript(DIR_SCRIPTS_GESCORE . 'gescore.js');
//$this->document->addScript(DIR_SCRIPTS_GESCORE . 'jeditable-datepicker.js');
//$this->document->addScript(DIR_SCRIPTS_GESCORE . 'edicionCongreso.js');
//$this->document->addScript(DIR_SCRIPTS_GESCORE . 'min.js');

$this->document->addStyleSheet("http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css");       
$this->document->addStyleSheet(DIR_STYLESHEETS_GESCORE . 'utils.css');
$this->document->addStyleSheet(DIR_STYLESHEETS_GESCORE . 'panelSubfilter.css');

/*$typeCongreso = 'Congreso';
$typeRevista = 'Revista';
$typeEdicion = 'Edición';
$typeEditorial = 'Editorial';
$typeEntidadIndiceCalidad = 'Entidad de Calidad';
$typeIssue = 'Issue';*/

$typeCongreso = $this->categories['congreso'];
$typeRevista = $this->categories['revista'];
$typeEdicion = $this->categories['edicion'];
$typeEditorial = $this->categories['editorial'];
$typeEntidadIndiceCalidad = $this->categories['entidadCalidad'];
$typeIssue = $this->categories['issue'];
$typeTopico = $this->categories['topico'];

$this->incluirAcronimo = false;
$this->incluirFechaInicioPublicacion = false;
$this->incluirLugar = false;
$this->incluirLink = false;

//echo (Juri::base());

foreach ($this->items as $i => $item) {
    if ($item->categoria == $typeCongreso || $item->categoria == $typeRevista || $item->categoria == $typeEditorial
        || $item->categoria == $typeEntidadIndiceCalidad ) {
        $this->incluirAcronimo = true;
        $this->incluirLink = true;
    }
    else if($item->categoria == $typeEdicion) {
        $this->incluirFechaInicioPublicacion = true;
        $this->incluirLugar = true;
        $this->incluirLink = true;
    }
    else if($item->categoria == $typeIssue) {
        $this->incluirFechaInicioPublicacion = true;
    }
}

//JSubMenuHelper::addEntry('My Submenu','index.php?option=com_hello&view=hello', true ); 

function printFormatFromTable($date) {
    if($date != "1970-01-01") { 
        echo date('d/m/Y', strtotime($date));
    }
}

function bold($text) {
    return '<b>' . $text . '</b>';
}

$user		= JFactory::getUser();
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
?>
<form action="<?php echo JRoute::_('index.php?option=com_gestorcore&view=gescore') ?>" method="post" name="adminForm">
      
    <div class="width-100 fltrt">
        <fieldset id="filter-bar">
            <div class="filter-search ">
                <label class="filter-search-lbl" for="filter_search">
                    <?php echo JText::_('COM_GESCORE_FILTER_LABEL'); ?>&nbsp;</label>
                <input size="50" type="text" name="filter_search" id="filter_search"
                        value="<?php echo $this->escape($this->state->get('filter.search')); ?>"
                        title="<?php echo JText::_('COM_GESCORE_MESSAGE_FILTER_SEARCH'); ?>" />

                <button type="submit" class="btn"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
                <button type="button" class="button_clear"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>   
            </div>
            <span class='fltlft checkIncluirTopicos'> 
                <input type="checkbox" id="filter_incluirTopico" <?php echo $this->escape($this->state->get('filter.incluirTopico')); ?> />
                <label class="filter-search-lbl" for="filter_incluirTopico" >Incluir Tópicos en la búsqueda</label>
            </span>

            <div class="filter-select fltrt">
                <label>
                <select name="filter_typeSearch" class="inputbox" onchange="this.form.submit()">
                    <option value=""><?php echo JText::_('COM_GESCORE_SELECT_FILTRAR_BUSQUEDA');?></option>
                    <?php echo JHtml::_('select.options', $this->get('typesFilter'),
                            'value', 'text', $this->state->get('filter.typeSearch'));?>
                </select></label>
            </div>
        </fieldset>
        <div id="toppanel">
            <div id="panel-filter">
                <fielset class="content clearfix">
                    <?php
                    switch ($this->state->get('filter.typeSearch')) {
                        case 'issue': ?>
                            <div id="panel-filter-issue" class="subfilters">
                                <div class="subfilter-row subfilter-title">
                                    <div class="subfilter-cell"><?php echo JText::_('COM_GESCORE_BUSQUEDA_AVANZADA_EDICION_LIM_RECEPCION'); ?></div>
                                    <div class="subfilter-cell"><?php echo JText::_('COM_GESCORE_BUSQUEDA_AVANZADA_EDICION_LIM_DEFINITIVA'); ?></div>
                                    <div class="subfilter-cell"><?php echo JText::_('COM_GESCORE_BUSQUEDA_AVANZADA_ISSUE_FECHA_PUBLICACION'); ?></div>
                                </div>
                                <div class="subfilter-row subfilter-first-row">
                                    <div class="subfilter-cell">
                                        <?php echo JHTML::_('calendar', $this->escape($this->state->get('subfilter.issue.limRecep')), 'subfilter_issue_limRecep', 'subfilter_issue_limRecep'
                                                      ,'%d-%m-%Y', 'size="15" placeholder="' . JText::_('COM_GESCORE_BUSQUEDA_AVANZADA_DESDE') . '"'); ?>
                                    </div>
                                    <div class="subfilter-cell">
                                        <?php echo JHTML::_('calendar', $this->escape($this->state->get('subfilter.issue.limDefinitiva')), 'subfilter_issue_limDefinitiva', 'subfilter_issue_limDefinitiva'
                                                      ,'%d-%m-%Y', 'size="15" placeholder="' . JText::_('COM_GESCORE_BUSQUEDA_AVANZADA_DESDE') . '"'); ?>
                                    </div>
                                    <div class="subfilter-cell">    
                                        <?php echo JHTML::_('calendar', $this->escape($this->state->get('subfilter.edicion.fechaPublicacion_min')), 'subfilter_issue_fechaPublicacionMin', 'subfilter_issue_fechaPublicacionMin'
                                                      ,'%d-%m-%Y', 'size="15" placeholder="' . JText::_('COM_GESCORE_BUSQUEDA_AVANZADA_DESDE') . '"'); ?> 
                                    </div>
                                </div>
                                <div class="subfilter-row">
                                     <div class="subfilter-cell">
                                        <?php echo JHTML::_('calendar', $this->escape($this->state->get('subfilter.issue.limRecep_max')), 'subfilter_issue_limRecep_max', 'subfilter_issue_limRecep_max'
                                                      ,'%d-%m-%Y', 'size="15" placeholder="' . JText::_('COM_GESCORE_BUSQUEDA_AVANZADA_HASTA') . '"'); ?>
                                    </div>
                                    <div class="subfilter-cell">
                                        <?php echo JHTML::_('calendar', $this->escape($this->state->get('subfilter.issue.limDefinitiva_max')), 'subfilter_issue_limDefinitiva_max', 'subfilter_issue_limDefinitiva_max'
                                                      ,'%d-%m-%Y', 'size="15" placeholder="' . JText::_('COM_GESCORE_BUSQUEDA_AVANZADA_HASTA') . '"'); ?>
                                    </div>
                                    <div class="subfilter-cell">
                                        <?php echo JHTML::_('calendar', $this->escape($this->state->get('subfilter.edicion.fechaPublicacion_max')), 'subfilter_issue_fechaPublicacionMax', 'subfilter_issue_fechaPublicacionMax'
                                                      ,'%d-%m-%Y', 'size="15" placeholder="' . JText::_('COM_GESCORE_BUSQUEDA_AVANZADA_HASTA') . '"'); ?>
                                    </div>
                                </div>
                            </div>
                            <?php        
                            break;
                        case 'edicion':
                            //echo '<label>' . JText::_('COM_GESCORE_BUSQUEDA_AVANZADA_LABEL') . '</label>' ?>
                            <div id="panel-filter-edicion" class="subfilters">
                                <div class="subfilter-row subfilter-title">
                                    <div class="subfilter-cell"><?php echo JText::_('COM_GESCORE_BUSQUEDA_AVANZADA_EDICION_LUGAR'); ?></div>
                                    <div class="subfilter-cell"><?php echo JText::_('COM_GESCORE_BUSQUEDA_AVANZADA_EDICION_LIM_RECEPCION'); ?></div>
                                    <div class="subfilter-cell"><?php echo JText::_('COM_GESCORE_BUSQUEDA_AVANZADA_EDICION_LIM_DEFINITIVA'); ?></div>
                                    <div class="subfilter-cell"><?php echo JText::_('COM_GESCORE_BUSQUEDA_AVANZADA_EDICION_INICIO'); ?></div>
                                    <div class="subfilter-cell"><?php echo JText::_('COM_GESCORE_BUSQUEDA_AVANZADA_EDICION_FIN'); ?></div>
                                </div>
                                <div class="subfilter-row subfilter-first-row">
                                    <div class="subfilter-cell">
                                         <input size="17" type="text" name="subfilter_edicion_lugar" placeholder="<?php echo JText::_('COM_GESCORE_BUSQUEDA_AVANZADA_EDICION_LUGAR'); ?>"
                                        value="<?php echo $this->escape($this->state->get('subfilter.edicion.lugar')); ?>" >
                                    </div>
                                    <div class="subfilter-cell">
                                        <?php echo JHTML::_('calendar', $this->escape($this->state->get('subfilter.edicion.limRecep')), 'subfilter_edicion_limRecep', 'subfilter_edicion_limRecep'
                                                      ,'%d-%m-%Y', 'size="15" placeholder="' . JText::_('COM_GESCORE_BUSQUEDA_AVANZADA_DESDE') . '"'); ?>
                                    </div>
                                    <div class="subfilter-cell">
                                        <?php echo JHTML::_('calendar', $this->escape($this->state->get('subfilter.edicion.limDefinitiva')), 'subfilter_edicion_limDefinitiva', 'subfilter_edicion_limDefinitiva'
                                                      ,'%d-%m-%Y', 'size="15" placeholder="' . JText::_('COM_GESCORE_BUSQUEDA_AVANZADA_DESDE') . '"'); ?>
                                    </div>
                                    <div class="subfilter-cell">
                                        <?php echo JHTML::_('calendar', $this->escape($this->state->get('subfilter.edicion.fechaInicio')), 'subfilter_edicion_fechaInicio', 'subfilter_edicion_fechaInicio'
                                                      ,'%d-%m-%Y', 'size="15" placeholder="' . JText::_('COM_GESCORE_BUSQUEDA_AVANZADA_DESDE') . '"'); ?>       
                                    </div>
                                    <div class="subfilter-cell">
                                        <?php echo JHTML::_('calendar', $this->escape($this->state->get('subfilter.edicion.fechaFin')), 'subfilter_edicion_fechaFin', 'subfilter_edicion_fechaFin'
                                                      ,'%d-%m-%Y', 'size="15" placeholder="' . JText::_('COM_GESCORE_BUSQUEDA_AVANZADA_DESDE') . '"'); ?>
                                    </div>
                                </div>
                                <div class="subfilter-row">
                                    <div class="subfilter-cell">
                                    </div>
                                    <div class="subfilter-cell">
                                        <?php echo JHTML::_('calendar', $this->escape($this->state->get('subfilter.edicion.limRecep_max')), 'subfilter_edicion_limRecep_max', 'subfilter_edicion_limRecep_max'
                                                      ,'%d-%m-%Y', 'size="15" placeholder="' . JText::_('COM_GESCORE_BUSQUEDA_AVANZADA_HASTA') . '"'); ?>
                                    </div>
                                    <div class="subfilter-cell">
                                        <?php echo JHTML::_('calendar', $this->escape($this->state->get('subfilter.edicion.limDefinitiva_max')), 'subfilter_edicion_limDefinitiva_max', 'subfilter_edicion_limDefinitiva_max'
                                                      ,'%d-%m-%Y', 'size="15" placeholder="' . JText::_('COM_GESCORE_BUSQUEDA_AVANZADA_HASTA') . '"'); ?>
                                    </div>
                                    <div class="subfilter-cell">
                                        <?php echo JHTML::_('calendar', $this->escape($this->state->get('subfilter.edicion.fechaInicio_max')), 'subfilter_edicion_fechaInicio_max', 'subfilter_edicion_fechaInicio_max'
                                                      ,'%d-%m-%Y', 'size="15" placeholder="' . JText::_('COM_GESCORE_BUSQUEDA_AVANZADA_HASTA') . '"'); ?>       
                                    </div>
                                    <div class="subfilter-cell">
                                        <?php echo JHTML::_('calendar', $this->escape($this->state->get('subfilter.edicion.fechaFin_max')), 'subfilter_edicion_fechaFin_max', 'subfilter_edicion_fechaFin_max'
                                                      ,'%d-%m-%Y', 'size="15" placeholder="' . JText::_('COM_GESCORE_BUSQUEDA_AVANZADA_HASTA') . '"'); ?>
                                    </div>
                                </div>
                            </div>
                            <?php
                            break;
                        default:
                            break;
                    }

                    ?>
                </fielset>
            </div> <!-- /login -->

            <!-- The tab on top -->	
            <div class="tab">
                <ul class="login">
                    <li class="left"></li>
                    <!--<li>Hello Guest!</li>
                    <li class="sep">|</li>-->
                    <li id="toggle">
                        <a id="open_subfilter" class="open" href="#">Búsqueda Avanzada</a>
                        <a id="close_subfilter" style="display: none;" class="close" href="#">Búsqueda Avanzada</a>			
                    </li>
                    <li class="right"></li>
                </ul>
            </div> <!-- / top -->
        </div> <!--panel -->


        <table id="tableResultsSearch" class="adminlist">
            <thead>
                <tr>
                    <th width="1%">
                            <input type="checkbox" name="toggle" value="" onclick="checkAll(this)" />
                    </th>
                    <th>
                        <?php echo JHtml::_('grid.sort', 'COM_GESCORE_SEARCH_LABEL_NOMBRE', 'title', $listDirn, $listOrder); ?>
                    </th>
                    <?php if($this->incluirAcronimo):?>
                        <th width="10%">
                            <?php echo JHtml::_('grid.sort', 'COM_GESCORE_SEARCH_LABEL_ACRONIMO', 'acronimo', $listDirn, $listOrder); ?>
                        </th>
                    <?php endif; ?>
                    <th width="10%">
                        <?php echo JHtml::_('grid.sort', 'COM_GESCORE_SEARCH_LABEL_CATEGORIA', 'categoria', $listDirn, $listOrder); ?>
                    </th>
                    <?php if ($this->incluirFechaInicioPublicacion): ?> 
                        <th width="10%">
                            <?php echo JHtml::_('grid.sort',  'COM_GESCORE_SEARCH_LABEL_FECHA_INICIO_PUBLICACION', 'fecha', $listDirn, $listOrder); ?>
                            <?php //echo JHtml::_('grid.order',  $this->items, 'filesave.png', 'messages.saveorder'); ?>
                        </th>
                    <?php endif; ?>
                    <?php if ($this->incluirLugar): ?>
                        <th width="10%">
                            <?php echo JHtml::_('grid.sort', 'COM_GESCORE_SEARCH_LABEL_LUGAR', 'lugar', $listDirn, $listOrder); ?>
                        </th>
                    <?php endif; ?>
                    <?php if ($this->incluirLink): ?>
                        <th width="10%" class="nowrap">
                            <?php echo JHtml::_('grid.sort',  'COM_GESCORE_SEARCH_LABEL_LINK', 'linkEntidad', $listDirn, $listOrder); ?>
                            <?php //echo JHtml::_('grid.order',  $this->items, 'filesave.png', 'messages.saveorder'); ?>
                        </th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <td colspan="7">
                        <?php echo $this->pagination->getListFooter(); ?>
                    </td>
                </tr>
            </tfoot>
            <tbody>
                <?php foreach ($this->items as $i => $item) :
                    $item->max_ordering = 0; //??
                    ?>
                    <tr class="row<?php echo $i % 2; ?>">
                        <td class="center">
                                <?php echo JHtml::_('grid.id', $i, $item->title); ?>
                        </td>
                        <td class="center">
                            <a href="
                                <?php 
                                $uriBase = 'index.php?option=com_gestorcore&';
                                switch ($item->categoria) {
                                    case $typeCongreso:
                                        echo JRoute::_($uriBase . 'controller=congreso&task=infoCongreso&id=' . $item->id);
                                        break;
                                    case $typeRevista:
                                        echo JRoute::_($uriBase . 'controller=revista&task=infoRevista&id=' . $item->id);
                                        break;
                                    case $typeEdicion:
                                        $ids = explode('%', $item->id);
                                        echo JRoute::_($uriBase . 'controller=congreso&task=infoEdicion&orden=' . $ids[0] . '&congreso=' . $ids[1]);
                                        break;
                                    case $typeIssue:
                                        $ids = explode('%', $item->id);
                                        echo JRoute::_($uriBase . 'controller=revista&task=infoIssue&volumen=' . $ids[0] . '&numero=' . $ids[1] . '&revista=' . $ids[2]);
                                        break;
                                    case $typeEntidadIndiceCalidad:
                                        echo JRoute::_($uriBase . 'view=infoEntidadCalidad&id=' . $item->id);
                                        break;
                                    case $typeTopico:
                                        echo JRoute::_($uriBase . 'view=infoTopico&id=' . $item->id);
                                        break;
                                    default:
                                        break;
                                }?>
                                "> 
                                 <?php
                                 switch ($item->categoria) {
                                    case $typeEdicion:
                                        $tmp = explode('%', $item->title);
                                        echo Jtext::sprintf('COM_GESCORE_SEARCH_EDICION_CONGRESO', bold($tmp[0]), bold($tmp[1])) . '</a>';
                                        if (isset($tmp[2])) { ?>
                                            <p class="smallsub font_size_11px">
                                                <?php echo '('. JText::_('COM_GESCORE_SEARCH_LABEL_APARECE_EN') . bold($tmp[2]) . ')';?>
                                            </p>
                                         <?php 
                                        }
                                        break;
                                    case $typeIssue:
                                        $tmp = explode('%', $item->title);
                                        echo Jtext::sprintf('COM_GESCORE_SEARCH_ISSUE_REVISTA', bold($tmp[0]), bold($tmp[1]), bold($tmp[2])) . '</a>';
                                        if (isset($tmp[3])) { ?>
                                            <p class="smallsub font_size_11px">
                                                <?php echo '('. JText::_('COM_GESCORE_SEARCH_LABEL_APARECE_EN') . bold($tmp[3]) . ')';?>
                                            </p>
                                         <?php 
                                        }
                                        break;
                                    default:
                                        echo $this->escape($item->title) . '</a>'; 
                                        break;
                                }?>

                        </td>
                        <?php if ($this->incluirAcronimo):?>
                            <td class="center">
                                <?php echo $this->escape($item->acronimo); ?>
                            </td>
                        <?php endif; ?>
                        <td class="center">
                            <?php echo $this->escape($item->categoria); ?>
                        </td>
                        <?php if ($this->incluirFechaInicioPublicacion): ?> 
                            <td class="center">
                                <?php 
                                if (!empty($item->fecha)) {
                                    echo JHTML::_('date', $item->fecha, JText::_('COM_GESTORCORE_DATE_FORMAT_LC')); 
                                }?>
                            </td>
                        <?php endif; ?>
                        <?php if ($this->incluirLugar): ?> 
                            <td class="center">
                                <?php echo $this->escape($item->lugar); ?>
                            </td>
                        <?php endif; ?>
                        <?php if ($this->incluirLink): ?>    
                            <td class="center">
                                <?php if (!empty($item->linkEntidad)) { 
                                    $http = strstr($item->linkEntidad, 'http://')? '':'http://';
                                    ?>
                                    <a href="<?php echo $http . $this->escape($item->linkEntidad); ?>">Web de la entidad</a>
                                <?php }?>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div>
        <input type="hidden" name="task" value="" />
        <input type="hidden" name="boxchecked" value="0" />
        <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
        <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
        <input type="hidden" id="filter_incluirTopsID" name="filter_incluirTops" value="<?php echo $this->state->get('filter.incluirTopico');?>"/> 
        <?php echo JHtml::_('form.token'); ?>
	</div>
</form>

<div id="module-submenu">
    <?php echo GescoreHelper::getSubmenu(); ?>
</div>
