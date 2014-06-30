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

function printFormatFromTable($date) {
    if($date != "1970-01-01") { 
        echo date('d/m/Y', strtotime($date));
    }
}

?>
<?php foreach($this->ediciones as $i => $edicion): ?>
    <tr class="row<?php echo $i % 2; ?>">
        <td> <center> <?php echo JHtml::_('grid.id', $i, $edicion->orden); ?> </center></td>
        <td> 
            <a href="<?php echo JRoute::_('index.php?option=com_gestorcore&controller=congreso&task=infoEdicion&orden=' . $edicion->orden .
                                          '&congreso='.$this->nomCongreso); ?>"><?php echo $edicion->orden; ?></a>
        </td>
        <td> <?php echo $edicion->lugar; ?> </td>
        <td> <?php printFormatFromTable($edicion->limRecepcion); ?> </td>
        <td> <?php printFormatFromTable($edicion->fechaDefinitiva); ?> </td>
        <td> <?php printFormatFromTable($edicion->fechaInicio); ?> </td>
        <td> <?php printFormatFromTable($edicion->fechaFin); ?> </td>
        <td class="center">
            <?php if (!empty($edicion->linkEdicion)) { 
                $http = strstr($edicion->linkEdicion, 'http://')? '':'http://';
                ?>
                <a href="<?php echo $http . $this->escape($edicion->linkEdicion); ?>">Sitio web</a>
            <?php }?>
        </td>
        <td> 
            <a href="<?php echo JRoute::_('index.php?option=com_gestorcore&controller=congreso&task=infoEdicion&orden=' . $edicion->orden .
                                          '&congreso='.$this->nomCongreso); ?>"> Más Info</a>
        </td>

    </tr>
<?php endforeach; ?>
