<?php
/**
 * Home Congresos, Gestor de congresos y revistas.
 *
 * @version		1.0 chdemko $
 * @package		Congreso
 * @subpackage          Components
 * @copyright           Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @author		Nick Avalos
 * @link		http://google.com/+nickavalos 
 * @license		License GNU General Public License version 2 or later
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
// load tooltip behavior
JHtml::_('behavior.tooltip');

$this->document = JFactory::getDocument();
$this->document->addScript(DIR_SCRIPTS_GESCORE . "jquery-1-9-1.js");
$this->document->addScript(DIR_SCRIPTS_GESCORE . "jquery-ui.js");

//JHTML::script('utils.js');
$this->document->addScript(DIR_SCRIPTS_GESCORE . 'jquery.jqpagination.js');
$this->document->addScript(DIR_SCRIPTS_GESCORE . 'jquery.jui_alert.min.js');
$this->document->addScript(DIR_SCRIPTS_GESCORE . 'jquery.simple_expand.js');
$this->document->addScript(DIR_SCRIPTS_GESCORE . 'scopussearch.jsp');
//$this->document->addScript('http://searchapi.scopus.com/scripts/scapi.jsp');
$this->document->addScript(DIR_SCRIPTS_GESCORE . 'submenu.js');
$this->document->addScript(DIR_SCRIPTS_GESCORE . 'funciones.js');
$this->document->addScript(DIR_SCRIPTS_GESCORE . 'import.js');

JHTML::stylesheet('jquery-ui.css', "http://code.jquery.com/ui/1.10.3/themes/smoothness/");       
$this->document->addStyleSheet(DIR_STYLESHEETS_GESCORE . 'utils.css');
$this->document->addStyleSheet(DIR_STYLESHEETS_GESCORE . 'jqpagination.css');
$this->document->addStyleSheet(DIR_STYLESHEETS_GESCORE . 'jquery.jui_alert.css');
//$this->document->addStyleSheet(DIR_STYLESHEETS_GESCORE . 'sciverse_list_hilight.css');

$user		= JFactory::getUser();
//$listOrder	= $this->escape($this->state->get('list.ordering'));
//$listDirn	= $this->escape($this->state->get('list.direction'));
?>


<form action="<?php echo JRoute::_('index.php?option=com_gestorcore&view=import') ?>" method="post" name="sciverseForm">
    <div id="sciverseDebugArea"></div>
        
    <div id='import_log_correctos' hidden></div>
    <div id='import_log_incorrectos' hidden></div>
    <!--
    <input type="text" name="searchString"/>
    <button onClick="runSearch(0)" name="searchButton"/>SEARCH</button>-->


    <div class="width-100 fltrt">
        <fieldset id="filter-bar" style="height: 140px;">
            <div class="filter-search ">
                <label class="filter-search-lbl" for="filter_search">
                    <?php echo JText::_('COM_GESCORE_FILTER_LABEL'); ?>&nbsp;</label>
                <input type="text" name="filter_search" id="filter_search" style="width: 25%;"
                        value="<?php //echo $this->escape($this->state->get('filter.search')); ?>"
                        title="<?php echo JText::_('COM_GESCORE_MESSAGE_FILTER_SEARCH'); ?>" />

                <button id='import_search' type="button" class="btn"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
            </div>

            <div class="filter-select fltrt">
                <label>
                    <select class="import_sourceType">
                        <option value="*"><?php echo '- ' . JText::_('COM_GESCORE_IMPORT_SOURCE_TYPE') . ' -';?></option>
                        <?php echo JHtml::_('select.options', $this->get('SourceTypes'), 'value', 'text', '*');?>
                    </select>
                </label>
                <label>
                    <select class="import_documentType">
                        <option value="*"><?php echo '- ' . JText::_('COM_GESCORE_IMPORT_TIPO_DOCUMENTO') . ' -';?></option>
                        <?php echo JHtml::_('select.options', $this->get('TiposDocumento'), 'value', 'text', '*');?>
                    </select>
                </label>
                <label>
                    <select class="import_subarea">
                        <option value="*"><?php echo '- ' . JText::_('COM_GESCORE_IMPORT_AREA') . ' -';?></option>
                        <?php echo JHtml::_('select.options', $this->get('Subareas'), 'value', 'text', '*');?>
                    </select>
                </label>
            </div>
            
            <br><br><br>
            <table class='table_alignTop gescore_width_100'>
                <td>
                <fieldset id='import_checkboxes'class="fltleft">
                    <legend><input type="checkbox" class="seleccionar_todo" checked />Select All | Buscar en:</legend>
                    <div class='content'>
                        <div class='import_row'>
                            <div class="import-cell">
                                <input type='checkbox' value='SRCTITLE' checked/><label>Título - Entidad</label>
                            </div>
                            <div class="import-cell">
                                <input type='checkbox' value='KEY' checked/><label>Palabra clave</label>
                            </div>
                            <div class="import-cell">
                                <input type='checkbox' value='AUTH' checked/><label title='Nombre y apellidos'>Autor</label>
                            </div>
                            <div class="import-cell">
                                <input type='checkbox' value='ISSN' checked/><label title='International Standard Serial Number'>ISSN</label>
                            </div>
                        </div>
                        <div class='import_row'>
                            <div class="import-cell">
                                <input type='checkbox' value='TITLE' checked/><label>Título - Publicación</label>
                            </div>
                            <div class="import-cell">
                                <input type='checkbox' value='REF' checked/><label>Referencias</label>
                            </div>
                            <div class="import-cell">
                                <input type='checkbox' value='EDITOR' checked/><label>Editor</label>
                            </div>
                            <div class="import-cell">
                                <input type='checkbox' value='ISBN' checked/><label title='International Standard Book Number'>ISBN</label>
                            </div>
                        </div>
                        <div class='import_row'>
                            <div class="import-cell">
                                <input type='checkbox' value='ABS' checked/><label>Extracto</label>
                            </div>
                            <div class="import-cell">
                                <input type='checkbox' value='AFFIL' checked/><label title='Ciudad, país, organización'>Afiliación</label>
                            </div>
                            <div class="import-cell">
                                <input type='checkbox' value='CONF' checked/><label title='Nombre, Patrocinadores y localización'>Info de la conferencia</label>
                            </div>
                            <div class="import-cell">
                                <input type='checkbox' value='DOI' checked/><label title='Digital Object Identifier '>DOI</label>
                            </div>
                        </div>
                    </div>
                </fieldset>
                </td><td>
                <fieldset id="busquedaFecha" style="width: 310px; height: 81px; background-color: #efffef;" class="fltrt panelform">
                    <legend>Búsqueda por fecha:</legend>
                    <table class="gescore_width_100">
                        <label>Tipo de búsqueda:</label>
                        <fieldset id="escogerTipoBusquedaFecha" class="radio" style="background-color: #efffef;">
                            <input type="radio" name='tipoBusqueda' value="monthyear" id="categoriaLista" checked><label>Mes/Año</label>
                            <input type="radio" name='tipoBusqueda' value="intervalo" id="categoriaNumerico"><label>Intervalo</label>
                        </fieldset>
                    </table>
                    <div class='toggle'>
                        <div>
                            <select class='select_month'>
                                <option value="*"><?php echo '- ' . JText::_('COM_GESCORE_MES') . ' -';?></option>
                                <?php echo JHtml::_('select.options', $this->get('MesesAnyo'), 'value', 'text', '*');?>
                            </select>
                            <input type="text" class="input_year" placeholder=" Año de publicación"/>
                        </div>
                        <div style="display: none;">
                            <input type="text" class="year_ini" placeholder=" Año inicial"/>
                            <input type="text" class="year_end" placeholder=" Año final"/>
                        </div>
                    </div>
                    
                </fieldset>
                </td>
            </table>
        </fieldset>
        
        <fieldset id='filter_inferior' style="height: 15px;">
            <div class='importar_seleccionados'>
                <button type='button' class="btn" style='background-color: #ABC6DD;' hidden>Importar Seleccionados</button>
            </div>
            <div class='txt_numResults' style='float: left; margin-left: 40px;' hidden>
                <label>-Se ha encontrado <span class='numResults'></span> resultados-</label>
            </div>
            <div class='fltrt'>
                <label>Ordenar por: </label>
                <select class='sort_by'>
                    <?php echo JHtml::_('select.options', $this->get('SortValues'), 'value', 'text', 'Date');?>
                </select>
                <select class='sort_direction'>
                    <option value='Ascending'>ASC</option>
                    <option value='Descending' selected>DES</option>
                </select>
                <select class='results_per_page' title='Resultados por página'>
                    <option value='10'>10</option>
                    <option value='15'>15</option>
                    <option value='20'>20</option>
                    <option value='25' selected>25</option>
                </select>
            </div>
        </fieldset>

        <table id="tableResultsSearch_import" class="adminlist">
            <thead>
                <th width="1%">
                    <input type="checkbox" onclick="checkAll(this)" />
                </th>
                <th width='20%'> Título - Entidad Publicadora</th>
                <th width="7%"><?php echo JText::_('COM_GESCORE_IMPORT_SOURCE_TYPE');?></th>
                <th>Título - Publicación</th>
                <th width="7%">ISSN</th>
                <th width="7%">Volumen</th>
                <th width="7%">Nro Issue</th>
                <th width="7%">Fecha Publicación</th>
                <th width="10%">Primer Autor</th>
                <th width="7%">Link Scopus</th>
            </thead>
            <tbody></tbody>
        </table>
        
        <div id='import_pagination'>
            
            <!--<div class="clearfix"></div> --><!-- should probably have a shower after that -->
        </div>
        
        
        <!--
        <ul class="log"></ul>
        <div id="sciverse">
            None.
        </div>
        -->
        <!--<div id="output">Hello World!</div>-->
    
    
    </div>
    <div>         
        <?php echo JHtml::_('form.token'); ?>
    </div>
</form>

<div id="module-submenu">
    <?php echo GescoreHelper::getSubmenu(); ?>
</div>

<div id="htmlOculto" hidden>
    <table>
        <tr class="filaResultados">
            <td class="center">
                <?php //echo JHtml::_('grid.id', 0, "issn"); ?>
                <input type="checkbox" name="cid[]">
            </td>
            <td class="tituloEntidadPublicadora"></td>
            <td class="sourceType"></td>
            <td class="tituloPublicacion"></td>
            <td class="issnRevista"></td>
            <td class="volumen"></td>
            <td class="numero"></td>
            <td class="fechaPublicacion"></td>
            <td class="autor"></td>
            <td class="linkScopus">
                <a href="">link Scopus</a>
            </td>
        </tr>
    </table>
    <div name='import_message'></div>
    <div class="accordion_imports">
        <a class="expander" href="#">Ver todos</a>
        <div class="content" style="display: none;">
            <ul>
                
            </ul>
        </div>
    </div>
    <div class="pagination">
        <a href="#" class="first" data-action="first">&laquo;</a>
        <a href="#" class="previous" data-action="previous">&lsaquo;</a>
        <input type="text" readonly="readonly" />
        <a href="#" class="next" data-action="next">&rsaquo;</a>
        <a href="#" class="last" data-action="last">&raquo;</a>
    </div>
</div>

<input id="token_user" type='hidden' value='<?php //echo JUtility::getToken();?>'>
<input name="secureAuthtoken" type='hidden'>