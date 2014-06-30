<?php
/**
 * This file is part of the project Gestor de Congresos y Revistas en Joomla (GesCORE).
 *
 * @package		GesCORE backend 
 * @subpackage  Views
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

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
// load tooltip behavior
JHtml::_('behavior.tooltip');

$this->document = JFactory::getDocument();
$this->document->addScript(DIR_SCRIPTS_GESCORE . "jquery-1-9-1.js");
$this->document->addScript(DIR_SCRIPTS_GESCORE . "jquery-ui.js");

//$this->document->addScript(DIR_SCRIPTS_GESCORE . 'utils.js');
$this->document->addScript(DIR_SCRIPTS_GESCORE . 'submenu.js');
$this->document->addScript(DIR_SCRIPTS_GESCORE . 'hightcharts.js');
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

echo $this->escape($this->state->get('list.ordering'));
?>
<form action="<?php echo JRoute::_('index.php?option=com_gestorcore&view=entidadCalidad&layout=default_infoEntidad&id=' . $this->entidadCalidad->nombre); ?>" method="post" name="adminForm">
        <div class="width-40 fltrt">
            <?php echo JHtml::_('sliders.start', 'infoEntidadCalidad', array('useCookie'=>1)); ?>
                <?php echo JHtml::_('sliders.panel', "Información", 'infoGeneral'); ?>
                <table cellspacing="10" width="100%">
                    <tr>
                        <td width="30%"> <label>Nombre </label> </td>
                        <td width="70%"> <label> <?php echo $this->entidadCalidad->nombre; ?> </label> </td>
                    </tr><tr>
                        <td> <label>Siglas </label> </td>
                        <td> <label><?php echo $this->entidadCalidad->siglas; ?> </label> </td>
                    </tr><tr>
                        <td> <label>Sitio web </label> </td>
                        <td> <label><?php echo $this->entidadCalidad->link; ?></label> </td>
                    </tr><tr>
                        <td> <label>Criterios </label> </td>
                        <td> <label><?php echo $this->entidadCalidad->criterios; ?></label> </td>
                    </tr><tr>
                        <td> <label>Categorias </label> </td>
                        <td>
                            <ul>
                                <?php foreach($this->entidadCalidad->categorias as $categoria): ?>
                                    <li><label> <?php echo $categoria->categoria;?> </label></li>
                                <?php endforeach; ?>
                            </ul>
                        </td>
                    </tr>
                </table>
                <?php echo JHtml::_('sliders.panel', "Estadísticas Ediciones", 'MotosID'); ?>
                <div id="graphicEdiciones"></div>
                <?php echo JHtml::_('sliders.panel', "Estadisticas Issues", 'estadisticasIssue'); ?>
                <div id="graphicIssues"></div>    
            <?php echo JHtml::_('sliders.end'); ?>
        </div>
        <div class="width-60 fltlft">
        <table class="adminlist" style="padding-top: 1.5em;">
            <thead>
                <tr>
                    <th width="1%">
                        <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
                    </th>
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
                        <td> <?php echo JHtml::_('grid.id', $i, $item->id); ?> </td>
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
                        </td>
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
            <?php echo JHtml::_('form.token'); ?>
	</div>
</form>

<div id="module-submenu">
    <?php echo GescoreHelper::getSubmenu(); ?>
</div>


