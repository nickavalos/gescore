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
<?php foreach($this->congresos as $i => $congreso): ?>
	<tr class="row<?php echo $i % 2; ?>">
		<td> <?php echo JHtml::_('grid.id', $i, $congreso->nombre); ?> </td>
                <td> 
                    <a href="<?php echo JRoute::_('index.php?option=com_gestorcore&controller=congreso&task=infoCongreso&id=' . $congreso->nombre); ?>">
                        <?php echo $congreso->nombre; ?>
                    </a> 
                </td>
                <td> <?php echo $congreso->acronimo; ?> </td>
                <td> <?php echo $congreso->linkCongreso; ?> </td>
                <td> <?php echo $congreso->periodicidad; ?> </td>
                <td> <?php echo $congreso->nomEditorial; ?> </td>
                <td> 
                    <a href="<?php echo JRoute::_('index.php?option=com_gestorcore&controller=congreso&task=infoCongreso&id=' . $congreso->nombre); ?>">
				<?php echo "Mas info"; ?>
                    </a>
                </td>
	</tr>
<?php endforeach; ?>

