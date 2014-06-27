<?php
/**
 * Home Congresos, Gestor de congresos y revistas.
 *
 * @version		1.0 chdemko $
 * @package		Congreso
 * @subpackage          Components
 * @copyright           Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @author		Nick Avalos
 * @link		http://google.com/+nickavalos 
 * @license		License GNU General Public License version 2 or later
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
// load tooltip behavior
JHtml::_('behavior.tooltip');

$this->document = JFactory::getDocument();
$this->document->addScript(DIR_SCRIPTS_GESCORE . "jquery-1-9-1.js");
$this->document->addScript(DIR_SCRIPTS_GESCORE . "jquery-ui.js");

//JHTML::script('utils.js');
$this->document->addScript(DIR_SCRIPTS_GESCORE . 'submenu.js');
$this->document->addScript(DIR_SCRIPTS_GESCORE . 'gescore.js');
//$this->document->addScript(DIR_SCRIPTS_GESCORE . 'jeditable-datepicker.js');
//$this->document->addScript(DIR_SCRIPTS_GESCORE . 'edicionCongreso.js');
//$this->document->addScript(DIR_SCRIPTS_GESCORE . 'min.js');

JHTML::stylesheet('jquery-ui.css', "http://code.jquery.com/ui/1.10.3/themes/smoothness/");       
$this->document->addStyleSheet(DIR_STYLESHEETS_GESCORE . 'utils.css');

function printFormatFromTable($date) {
    if($date != FORMAT_NO_DATE) { 
        echo date('d/m/Y', strtotime($date));
    }
}

function bold($text) {
    return '<b>' . $text . '</b>';
}

$user		= JFactory::getUser();
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
?>
<form action="<?php echo JRoute::_('index.php?option=com_gestorcore&view=entidadCalidad') ?>" method="post" name="adminForm">
      
        <!--
        <div class="width-30 fltlft">
            <?php echo JHtml::_('sliders.start', 'PanelID', array('useCookie'=>1)); ?>
                <?php echo JHtml::_('sliders.panel', "Coches", 'CochesID'); ?>
                <ul>
                    <li>Coche 1</li>
                    <li>Coche 2</li>
                    <li>Coche 3</li>
                    <li>Coche 4</li>
                    <li>Coche 5</li>
                </ul>    
                <?php echo JHtml::_('sliders.panel', "Motos", 'MotosID'); ?>
                <ul>
                    <li><?php echo JText::_('COM_GESCORE_N_ITEMS_DELETED_MORE_2'); ?></li>
                    <li>Moto 2</li>
                    <li>Moto 3</li>
                    <li>Moto 4</li>
                    <li>Moto 5</li>
                </ul>    
            <?php echo JHtml::_('sliders.end'); ?>
        </div>-->
        <div class="width-100 fltrt">
        <table class="adminlist">
            <thead>
                <tr>
                    <th width="1%">
                        <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
                    </th>
                    <th width="10"> <?php echo JHtml::_('grid.sort', 'Nombre', 'nombre', $listDirn, $listOrder);?> </th>
                    <th width="5"> <?php echo JHtml::_('grid.sort', 'Siglas', 'siglas', $listDirn, $listOrder);?> </th>
                    <th width="5"> <?php echo JText::_('Link')?> </th>
                    <th width="5"> <?php echo JHtml::_('grid.sort', 'Criterios', 'criterios', $listDirn, $listOrder);?> </th>
                    <th width="5"> <?php echo JHtml::_('grid.sort', 'Número de Ediciones evaluadas', 'numeroEdiciones', $listDirn, $listOrder);?> </th>
                    <th width="5"> <?php echo JHtml::_('grid.sort', 'Número de Issues evaluadas', 'numeroIssues', $listDirn, $listOrder);?>  </th>
                    <th width="5">
                        <?php echo JText::_('Más info'); ?>
                    </th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <td colspan="8"><?php echo $this->pagination->getListFooter(); ?></td>
                </tr>
            </tfoot>
            <tbody>
                <?php foreach($this->items as $i => $congreso): ?>
                    <tr class="row<?php echo $i % 2; ?>">
                        <td> <?php echo JHtml::_('grid.id', $i, $congreso->nombre); ?> </td>
                        <td> <a href="<?php echo JRoute::_('index.php?option=com_gestorcore&view=infoEntidadCalidad&id=' . $congreso->nombre); ?>"><?php echo $congreso->nombre; ?></a> </td>
                        <td> <?php echo $congreso->siglas; ?> </td>
                        <td> <?php echo $congreso->link; ?> </td>
                        <td> <?php echo $congreso->criterios; ?> </td>
                        <td> <?php echo $congreso->numeroEdiciones; ?> </td>
                        <td> <?php echo $congreso->numeroIssues; ?> </td>
                        <td> 
                            <a href="<?php echo JRoute::_('index.php?option=com_gestorcore&view=infoEntidadCalidad&id=' . $congreso->nombre); ?>">
                                        <?php echo "Más info" ?>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
	</table>
        </div>
            
        <div>
            <input type="hidden" name="task" value="" />
            <input type="hidden" name="boxchecked" value="0" />
            <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
            <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
            <input type="hidden" id="filter_incluirTopsID" name="filter_incluirTops" value="<?php echo $this->state->get('filter.incluirTopico');?>"/> 
            <?php echo JHtml::_('form.token'); ?>
	</div>
</form>

<div id="module-submenu">
    <?php echo GescoreHelper::getSubmenu(); ?>
</div>


