<?php
/**
 * Home Congresos, Gestor de congresos y revistas.
 *
 * @version		1.0 chdemko $
 * @package		Congreso
 * @subpackage          Components
 * @copyright           Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @author		Christophe Demko
 * @link		http://google.com/+nickavalos 
 * @license		License GNU General Public License version 2 or later
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<tr>
    <th width="1%">
        <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->revistas); ?>);" />
    </th>
    <th width="10%"> <?php echo JText::_('issn'); ?> </th>
    <th> <?php echo JText::_('Nombre'); ?> </th>
    <th width="10%"> <?php echo JText::_('Acronimo'); ?> </th>
    <th width="20%"> <?php echo JText::_('Link'); ?> </th>
    <th width="10%"> <?php echo JText::_('Periodicidad'); ?> </th>
    <th width="10%"> <?php echo JText::_('Nombre de Editorial'); ?> </th>
    <th width="10%">
        <?php echo JText::_('Más info'); ?>
    </th>
</tr>

