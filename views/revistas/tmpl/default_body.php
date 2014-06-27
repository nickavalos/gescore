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

