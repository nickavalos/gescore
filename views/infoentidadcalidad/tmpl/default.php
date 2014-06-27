<?php
/**
 * Home Congresos, Gestor de congresos y revistas.
 *
 * @version		1.0 chdemko $
 * @package		Entidad Calidad
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
$this->document->addScript(DIR_SCRIPTS_GESCORE . 'highcharts.js');
$this->document->addScript(DIR_SCRIPTS_GESCORE . 'exporting.js');
$this->document->addScript(DIR_SCRIPTS_GESCORE . 'funciones.js');
$this->document->addScript(DIR_SCRIPTS_GESCORE . 'infoEntidadCalidad.js');

$this->document->addStyleSheet("http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css");       
$this->document->addStyleSheet(DIR_STYLESHEETS_GESCORE . 'utils.css');

$categoriaEdicion = 'Edición';
$categoriaIssue = 'Issue';

function bold($text) {
    return '<b>' . $text . '</b>';
}

$user		= JFactory::getUser();
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));

?>
<form action="<?php echo JRoute::_('index.php?option=com_gestorcore&view=infoEntidadCalidad&id=' . $this->entidadCalidad->nombre); ?>" method="post" name="adminForm">
        <div class="width-40 fltrt">
            <?php echo JHtml::_('sliders.start', 'infoEntidadCalidad', array('useCookie'=>1)); ?>
                <?php echo JHtml::_('sliders.panel', "Información: Doble click para editar", 'infoGeneral'); ?>
                <table cellspacing="10" class="table_alignTop gescore_width_100">
                    <tr>
                        <td width="30%"> <label>Nombre </label> </td>
                        <td width="70%"> <label id="nomEntidadCalidad" class="jeditable_textArea_entidadCalidad"><?php echo $this->entidadCalidad->nombre; ?></label> </td>
                    </tr><tr>
                        <td> <label>Siglas </label> </td>
                        <td> <label id="siglasEntidadCalidad" class="jeditable_info_entidadCalidad"><?php echo $this->entidadCalidad->siglas; ?></label> </td>
                    </tr><tr>
                        <td> <label>Sitio web </label> </td>
                        <td> <label id="linkEntidadCalidad" class="jeditable_textArea_entidadCalidad"><?php echo $this->entidadCalidad->link; ?></label> </td>
                    </tr><tr>
                        <td> <label>Criterios </label> </td>
                        <td> <label id="criteriosEntidadCalidad" class="jeditable_textArea_entidadCalidad"><?php echo $this->entidadCalidad->criterios; ?></label> </td>
                    </tr><tr>
                        <td> <label>Tipo de calificación</label> </td>
                        <td> <label id="tipoCategoria"><?php echo $this->entidadCalidad->tipoCategoria; ?></label> </td>
                    </tr>
                    <?php if ($this->entidadCalidad->tipoCategoria == GESCORE_TIPO_CATEGORIA_LISTA): ?>
                        <tr>
                            <td> <label>Categorías </label> </td>
                            <td>
                                <ul id="categoriasEntidadCalidad" class="adminformlist">
                                    <?php foreach($this->entidadCalidad->categorias as $categoria): ?>
                                        <li>- <label><?php echo $categoria->categoria;?></label></li>
                                    <?php endforeach; ?>
                                </ul>
                            </td>
                        </tr>
                    <?php endif; ?>
                </table>
                <?php echo JHtml::_('sliders.panel', "Estadísticas Ediciones", 'estadisticasEdicion'); ?>
                <div id="graphicEdiciones" class="graphicSection"></div>
                <?php echo JHtml::_('sliders.panel', "Estadísticas Issues", 'estadisticasIssue'); ?>
                <div id="graphicIssues" class="graphicSection"></div>   
            <?php echo JHtml::_('sliders.end'); ?>
        </div>
        <div class="width-60 fltlft">
            <fieldset class="adminform">
            <legend>Publicaciones valoradas por la entidad</legend>
            <table class="adminlist" style="">
                <thead>
                    <tr>
                        <!--<th width="1%">
                            <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
                        </th>-->
                        <th width="10%"> <?php echo JHtml::_('grid.sort', 'Categoria', 'categoria', $listDirn, $listOrder);?> </th>
                        <th> <?php echo JHtml::_('grid.sort', 'Entidad Publicadora', 'entidadPublicadora', $listDirn, $listOrder);?> </th>
                        <th width="10%"> <?php echo JHtml::_('grid.sort', 'Orden/Volumen', 'ordenVolumen', $listDirn, $listOrder);?> </th>
                        <th width="10%"> <?php echo JHtml::_('grid.sort', 'Número Issue', 'numero', $listDirn, $listOrder);?> </th>
                        <th width="20%"> <?php echo JHtml::_('grid.sort', 'Valoración', 'indice', $listDirn, $listOrder);?> </th>
                        <th width="10%"> <?php echo JText::_('Link'); ?> </th>
                    </tr>
                </thead>
                <tfoot>
                    <tr><td colspan="7"><?php echo $this->pagination->getListFooter(); ?></td></tr>
                </tfoot>
                <tbody>
                    <?php foreach($this->items as $i => $item): ?>
                        <tr class="row<?php echo $i % 2; ?>">
                            <!--<td> <?php echo JHtml::_('grid.id', $i, $item->id); ?> </td>-->
                            <td> <?php echo $item->categoria; ?> </td>
                            <td> <?php echo $item->entidadPublicadora; ?> </td>
                            <td> <?php echo $item->ordenVolumen; ?> </td>
                            <td> <?php echo $item->numero; ?> </td>
                            <td> <?php echo $item->indice; ?> </td>
                            <td>
                                <a href="
                                    <?php 
                                    $uriBase = 'index.php?option=com_gestorcore&';
                                    $ids = explode('%%', $item->id);
                                    $label = '';
                                    switch ($item->categoria) {
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

<div id="module-submenu">
    <?php echo GescoreHelper::getSubmenu(); ?>
</div>

<div id="htmlOculto" hidden>
    <input type="text" id="nomEntidadCalidad_copy" value="<?php echo $this->entidadCalidad->nombre; ?>">
    <select class="valoracionesEdiciones">
        <?php foreach($this->valoracionesEdiciones as $i => $valoracion): ?>
            <option value="<?php echo $valoracion->indice; ?>"><?php echo $valoracion->indice; ?></option>
        <?php endforeach;?>
    </select>
    <select class="valoracionesIssues">
        <?php foreach($this->valoracionesIssues as $i => $valoracion): ?>
            <option value="<?php echo $valoracion->indice; ?>"><?php echo $valoracion->indice; ?></option>
        <?php endforeach;?>
    </select>
</div>