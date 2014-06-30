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
?>
<?php foreach($this->revistas as $i => $revista): ?>
	<tr class="row<?php echo $i % 2; ?>">
		<td> <?php echo JHtml::_('grid.id', $i, $revista->issn); ?> </td>
                <td> 
                    <a href="<?php echo JRoute::_('index.php?option=com_gestorcore&controller=revista&task=infoRevista&id=' . $revista->issn); ?>">
                        <?php echo $revista->issn; ?>
                    </a>
                </td>
                <td> <?php echo $revista->nombre; ?> </td>
                <td> <?php echo $revista->acronimo; ?> </td>
                <td> <?php echo $revista->linkRevista; ?> </td>
                <td> <?php echo $revista->periodicidad; ?> </td>
                <td> <?php echo $revista->nomEditorial; ?> </td>
                <td> 
                    <a href="<?php echo JRoute::_('index.php?option=com_gestorcore&controller=revista&task=infoRevista&id=' . $revista->issn); ?>">
                        <?php echo "Más info"; ?>
                    </a>
                </td>
	</tr>
<?php endforeach; ?>

