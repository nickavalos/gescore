<?php
/**
 * default body template file for HelloWorlds view of HelloWorld component
 *
 * @version		$Id: default_body.php 46 2010-11-21 17:27:33Z chdemko $
 * @package		Joomla16.Tutorials
 * @subpackage	Components
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @author		Christophe Demko
 * @link		http://joomlacode.org/gf/project/helloworld_1_6/
 * @license		License GNU General Public License version 2 or later
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
