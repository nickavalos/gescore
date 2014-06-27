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
    <th width="5%">
        <center><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->issues); ?>);" /></center>
    </th>
    <th width="10%"> <?php echo JHtml::_('grid.sort', 'Volumen', 'volumen', $listDirn, $listOrder);?> </th>
    <th width="10%"> <?php echo JHtml::_('grid.sort', 'Número', 'numero', $listDirn, $listOrder);?> </th>
    <th width="10%"> <?php echo JHtml::_('grid.sort', 'Límite de Recepción', 'limRecepcion', $listDirn, $listOrder);?> </th>
    <th width="10%"> <?php echo JHtml::_('grid.sort', 'Fecha Definitiva', 'fechaDefinitiva', $listDirn, $listOrder);?> </th>
    <th width="10%"> <?php echo JHtml::_('grid.sort', 'Fecha de Publicación', 'fechaPublicacion', $listDirn, $listOrder);?> </th>
    <th width="10%">
        <?php echo JText::_('Más info'); ?>
    </th>
</tr>
<div>
    <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
    <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
</div>

