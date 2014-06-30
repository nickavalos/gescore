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

$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));

?>
<tr>
    <th width="1%">
        <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->ediciones); ?>);" />
    </th>
    <th width="10%"> <?php echo JHtml::_('grid.sort', 'Edición', 'orden', $listDirn, $listOrder);?> </th>
    <th width="30%"> <?php echo JHtml::_('grid.sort', 'Lugar', 'lugar', $listDirn, $listOrder);?> </th>
    <th width="10%"> <?php echo JHtml::_('grid.sort', 'Límite de Recepción', 'limRecepcion', $listDirn, $listOrder);?> </th>
    <th width="10%"> <?php echo JHtml::_('grid.sort', 'Fecha Definitiva', 'fechaDefinitiva', $listDirn, $listOrder);?> </th>
    <th width="10%"> <?php echo JHtml::_('grid.sort', 'Fecha de Inicio', 'fechaInicio', $listDirn, $listOrder);?> </th>
    <th width="10%"> <?php echo JHtml::_('grid.sort', 'Límite de Fin', 'fechaFin', $listDirn, $listOrder);?> </th>
    <th width="10%"> <?php echo JText::_('Link Edición'); ?> </th>
    <th width="9%">
        <?php echo JText::_('Más info'); ?>
    </th>
</tr>
<div>
    <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
    <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
</div>