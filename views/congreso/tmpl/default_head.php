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