<?php
/**
 * Home Congresos, Gestor de congresos y revistas.
 *
 * @version		1.0 chdemko $
 * @package		Topico
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

//JHTML::script("jquery-1.9.1.js", "http://code.jquery.com/");
//JHTML::script("jquery-ui.js", "http://code.jquery.com/ui/1.10.3/");

$this->document = JFactory::getDocument();
$this->document->addScript(DIR_SCRIPTS_GESCORE . "jquery-1-9-1.js");
$this->document->addScript(DIR_SCRIPTS_GESCORE . "jquery-ui.js");

$this->document->addScript(DIR_SCRIPTS_GESCORE . 'submenu.js');
$this->document->addScript(DIR_SCRIPTS_GESCORE . 'jeditable.js');
//$this->document->addScript(DIR_SCRIPTS_GESCORE . 'highcharts.js');
//$this->document->addScript(DIR_SCRIPTS_GESCORE . 'exporting.js');
$this->document->addScript(DIR_SCRIPTS_GESCORE . 'funciones.js');
$this->document->addScript(DIR_SCRIPTS_GESCORE . 'infoTopico.js');

$this->document->addStyleSheet("http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css");       
$this->document->addStyleSheet(DIR_STYLESHEETS_GESCORE . 'utils.css');

$categoriaCongreso = 'Congreso';
$categoriaRevista = 'Revista';
$categoriaEdicion = 'Edición';
$categoriaIssue = 'Issue';

$user		= JFactory::getUser();
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));

?>
<form action="<?php echo JRoute::_('index.php?option=com_gestorcore&view=infotopico&id=' . $this->topico->nombre); ?>" method="post" name="adminForm">
        <div class="width-40 fltrt">
            <?php echo JHtml::_('sliders.start', 'infoTopico', array('useCookie'=>0)); ?>
                <?php echo JHtml::_('sliders.panel', "Información: Doble click para editar", 'infoGeneral'); ?>
                <table cellspacing="10" class="table_alignTop gescore_width_100">
                    <tr>
                        <td width="30%"> <label>Nombre </label> </td>
                        <td width="70%"> <label id="nomTopico" class="jeditable_textArea_topico"><?php echo $this->topico->nombre; ?></label> </td>
                    </tr><tr>
                        <td> <label>Descripcion </label> </td>
                        <td> <label id="descripcionTopico" class="jeditable_textArea_topico"><?php echo $this->topico->descripcion; ?></label> </td>
                    </tr>
                </table>
            
                <?php echo JHtml::_('sliders.panel', "Tópicos Similares", 'topicosSimilares'); ?>
                    <select name="lista_topicosSimilares" id="lista_topicosSimilares" class="topicosSimilares_topico gescore_width_100 font_size_105" size="8" multiple>                    
                        <?php
                        foreach($this->topico->similares as $topico): ?>
                            <option value="<?php echo $topico->nomTopicoSimilar;?>"><?php echo $topico->nomTopicoSimilar;?> </option>
                        <?php endforeach;?>
                    </select>
                    <div class="center">
                        <label><input id="add_topicoSimilar" class="pointer" type="button" value="Añadir topico" /></label>
                        <label><input id="delete_topicoSimilar" type="button" value="Eliminar selecc." disabled /></label>
                    </div>
            
                <?php //echo JHtml::_('sliders.panel', "Tópicos Populares", 'topicosPopulares'); ?>
                    <!--<div id="graphicIssues" class="graphicSection"></div>-->   
            <?php echo JHtml::_('sliders.end'); ?>
        </div>
        <div class="width-60 fltlft">
            <fieldset class="adminform">
            <legend>Apariciones del tópico</legend>
            <table class="adminlist" style="">
                <thead>
                    <tr>
                        <!--<th width="1%">
                            <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
                        </th>-->
                        <th width="15%"> <?php echo JHtml::_('grid.sort', 'Categoria', 'categoria', $listDirn, $listOrder);?> </th>
                        <th> <?php echo JHtml::_('grid.sort', 'Entidad Publicadora', 'entidadPublicadora', $listDirn, $listOrder);?> </th>
                        <th width="15%"> <?php echo JHtml::_('grid.sort', 'Orden/Volumen', 'orden_volumen', $listDirn, $listOrder);?> </th>
                        <th width="10%"> <?php echo JHtml::_('grid.sort', 'Número Issue', 'numero', $listDirn, $listOrder);?> </th>
                        <th width="15%"> <?php echo JText::_('Link'); ?> </th>
                    </tr>
                </thead>
                <tfoot>
                    <tr><td colspan=5"><?php echo $this->pagination->getListFooter(); ?></td></tr>
                </tfoot>
                <tbody>
                    <?php foreach($this->items as $i => $item): ?>
                        <tr class="row<?php echo $i % 2; ?>">
                            <!--<td> <?php echo JHtml::_('grid.id', $i, $item->id); ?> </td>-->
                            <td> <?php echo $item->categoria; ?> </td>
                            <td> <?php echo $item->entidadPublicadora; ?> </td>
                            <td> <?php echo $item->orden_volumen; ?> </td>
                            <td> <?php echo $item->numero; ?> </td>
                            <td>
                                <a href="
                                    <?php 
                                    $uriBase = 'index.php?option=com_gestorcore&';
                                    $ids = explode('%%', $item->id);
                                    $label = '';
                                    switch ($item->categoria) {
                                        case $categoriaCongreso:
                                            $label = 'link Congreso';
                                            echo JRoute::_($uriBase . 'controller=congreso&task=infoCongreso&id='. $ids[0]);
                                            break;
                                        case $categoriaRevista:
                                            $label = 'link Revista';
                                            echo JRoute::_($uriBase . 'controller=revista&task=infoRevista&id='. $ids[0]);
                                            break;
                                        case $categoriaEdicion:
                                            $label = 'link Edición';
                                            echo JRoute::_($uriBase . 'controller=congreso&task=infoEdicion&orden='. $ids[0] .'&congreso='. $ids[1]);
                                            break;
                                        case $categoriaIssue:
                                            $label = 'link Issue';
                                            echo JRoute::_($uriBase . 'controller=revista&task=infoIssue&volumen=' . $ids[0] . '&numero=' . $ids[1] . '&revista=' . $ids[2]);
                                            break;
                                        default:
                                            break;
                                    }?>
                                   "><?php echo $label; ?>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            </fieldset>
        </div>
            
        <div>
            <input type="hidden" name="task" value="" />
            <input type="hidden" name="boxchecked" value="0" />
            <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
            <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
            <?php echo JHtml::_('form.token'); ?>
	</div>
</form>

<div id="dialog-form_addTopicoEntidad" title="Nuevo Topico" class="dialog-form" hidden>
    <br><p class="validateTips">Por favor seleccione los tópicos que quiera añadir a la lista de similares.</p>
    <form>
        <table id="table_listTopicos" class="adminlist">
            <thead>
                <tr>
                    <th width="2">
                        <input type="checkbox" class="checkall_topicos" />
                    </th>
                    <th width="10"> <?php echo JText::_('Nombre del Tópico'); ?> </th>
                    <th width="5"> <?php echo JText::_('Descripción'); ?> </th>
                </tr>
            </thead>
            <tbody>
                
            </tbody>
        </table>
    </form>
</div>

<div id="htmlOculto_listE" hidden>
    <div id="rowNuevoTopico">
        <table>
            <tr>
                <td width="1%"> <input type="checkbox" name="toggleTopicos[]" class="checkBox_topicos" /></td>
            </tr>
        </table>
    </div>
</div>   


<div id="module-submenu">
    <?php echo GescoreHelper::getSubmenu(); ?>
</div>

<div id="htmlOculto" hidden>
    <input type="text" id="nomTopico_copy" value="<?php echo $this->topico->nombre; ?>">
</div>