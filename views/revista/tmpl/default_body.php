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
    if($date != FORMAT_NO_DATE) { 
        echo date('d/m/Y', strtotime($date));
    }
}

?>
<?php foreach($this->issues as $i => $issue): ?>
    <tr class="row<?php echo $i % 2; ?>">
        <td> <center> <?php echo JHtml::_('grid.id', $i, $issue->volumen . '#' . $issue->numero); ?> </center></td>
        <td> <?php echo $issue->volumen; ?> </td>
        <td> <?php echo $issue->numero; ?> </td>
        <td class="center"> <?php printFormatFromTable($issue->limRecepcion); ?> </td>
        <td class="center"> <?php printFormatFromTable($issue->fechaDefinitiva); ?> </td>
        <td class="center"> <?php printFormatFromTable($issue->fechaPublicacion); ?> </td>
        <td class="center"> 
            <a href="<?php echo JRoute::_('index.php?option=com_gestorcore&controller=revista&task=infoIssue&volumen=' . 
                                           $issue->volumen . '&numero=' . $issue->numero . '&revista='.$issue->issnRevista); ?>">
            <?php echo "Más info" ?>
            </a>
        </td>

    </tr>
<?php endforeach; ?>
