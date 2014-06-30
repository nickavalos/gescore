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
// load tooltip behavior
JHtml::_('behavior.tooltip');

//JHTML::script("jquery-1.9.1.js", "http://code.jquery.com/");
//JHTML::script("jquery-ui.js", "http://code.jquery.com/ui/1.10.3/");
$this->document = JFactory::getDocument();
$this->document->addScript(DIR_SCRIPTS_GESCORE . "jquery-1-9-1.js");
$this->document->addScript(DIR_SCRIPTS_GESCORE . "jquery-ui.js");

$this->document->addScript(DIR_SCRIPTS_GESCORE . "jeditable.js");
$this->document->addScript(DIR_SCRIPTS_GESCORE . "funciones.js");
$this->document->addScript(DIR_SCRIPTS_GESCORE . "submenu.js");
$this->document->addScript(DIR_SCRIPTS_GESCORE . "congreso.js");
//JHTML::script('min.js', "/administrator/components/com_gestorcore/scripts/");

JHTML::stylesheet('jquery-ui.css', "http://code.jquery.com/ui/1.10.3/themes/smoothness/");
$this->document->addStyleSheet(DIR_STYLESHEETS_GESCORE . 'utils.css');



?>
<form action="<?php echo JRoute::_('index.php?option=com_gestorcore&controller=congreso&task=infoCongreso&id=' . $this->nomCongreso); ?>" method="post" name="adminForm">
    <div class="info width-35 fltrt">
        <?php echo JHtml::_('sliders.start', 'infoCongreso', array('useCookie'=>1)); ?>
            <?php echo JHtml::_('sliders.panel', "Información: Doble click para editar", 'infoGeneral'); ?>

            <input id="copyNomCongreso_listEd" value="<?php echo $this->nomCongreso; ?>" hidden />
            <table cellspacing="10" class="table_alignTop gescore_width_100">
                <tr>
                    <td width="35%"><label>Nombre del congreso:</label></td>
                    <td width="65%"><label id="nomCongreso_listEd" class="jeditable_info_textarea"><?php echo $this->nomCongreso;?></label></td>
                </tr><tr>
                    <td><label>Acrónimo:</label></td>
                    <td><label id="acronimoCongreso_listEd" class="jeditable_info"><?php echo $this->congreso->acronimo; ?></label></td>
                </tr><tr>
                    <td><label>link del congreso:</label></td>
                    <td><label id="linkCongreso_listEd" class="jeditable_info_textarea"><?php echo $this->congreso->linkCongreso;?></label></td>
                </tr><tr>
                    <td><label>Periodicidad:</label></td>
                    <td><label id="periodicidadCongreso_listEd" class="jeditable_info"><?php echo $this->congreso->periodicidad; ?></label></td>
                </tr><tr>
                    <td><label>Editorial:<br><a id="buttonNuevaEditorial" href="#">-Nueva editorial-</a></label></td>
                    <td><label id="editorialCongreso_listEd" class="jeditable_info_select"><?php echo $this->congreso->nomEditorial; ?></label></td>
                </tr>
            </table>

            <?php echo JHtml::_('sliders.panel', "Tópicos del congreso", 'topicosCongreso'); ?>  

            <select name="topicosCongreso_listEdiciones" id="topicosCongreso_listEdiciones" class="topicosCongresoRevista gescore_width_100 font_size_105" size="10" multiple>                    
                <?php
                foreach($this->congreso->topicos as $topico): ?>
                    <option value="<?php echo $topico->nomTopico;?>"><?php echo $topico->nomTopico;?> </option>
                <?php endforeach;?>
            </select>
            <div class="center">        
                <label><input id="add_topicoCongreso" class="pointer" type="button" value="Añadir topico" /></label>
                <label><input id="delete_topicoCongreso" type="button" value="Eliminar selecc." disabled /></label>
                <label><input id="similares_topicoCongreso" type="button" value="Mostrar similares" disabled /></label>
            </div>
        <?php echo JHtml::_('sliders.end'); ?>
    </div>
    <div class="width-65 fltlft">
        <fieldset class="adminform">
            <legend>Ediciones del Congreso</legend>
            <table class="adminlist">
                    <thead><?php echo $this->loadTemplate('head');?></thead>
                    <tfoot><?php echo $this->loadTemplate('foot');?></tfoot>
                    <tbody><?php echo $this->loadTemplate('body');?></tbody>
            </table>
        </fieldset>
    </div>
    <div>
            <input type="hidden" name="option" value="com_gestorcore" />
            <!--<input type="hidden" name="view" value="edicion" />-->
            <input type="hidden" name="task" value="infoCongreso" />
            <input type="hidden" name="boxchecked" value="0" />
            <inupt type="hidden" name="controller" value="congreso" />
            <?php echo JHtml::_('form.token'); ?>
    </div>
</form>



<div id="dialog-form_addTopicoEntidad" title="Nuevo Topico" class="dialog-form" hidden>
    <br><p class="validateTips">Por favor seleccione los tópicos que quiere añadir al congreso.</p>
    <form>
        <table id="table_listTopicos" class="adminlist">
            <thead>
                <tr>
                    <th width="2">
                        <input type="checkbox" class="checkall_topicos" />
                    </th>
                    <th width="10"> <?php echo JText::_('Nombre del Tópico'); ?> </th>
                    <th width="5"> <?php echo JText::_('Descripción'); ?> </th>
                </tr>
            </thead>
            <tbody>
                
            </tbody>
        </table>
    </form>
</div>

<div id="dialog_nuevaEditorial" title="Nueva Editorial" class="dialog-form">
    <br><p class="validateTips">Por favor rellene los datos de la nueva editorial.</p>
    <form>
        <fieldset>
            <label for="nomEditorial">Nombre</label>
            <input type="text" name="nomEditorial" id="nomEditorial" class="text ui-widget-content ui-corner-all" />
            <label for="siglasEditorial">Siglas</label>
            <input type="text" name="siglasEditorial" id="siglasEditorial" class="text ui-widget-content ui-corner-all" />
            <label for="linkEditorial">Link</label>
            <input type="text" name="linkEditorial" id="linkEditorial" class="text ui-widget-content ui-corner-all" placeholder="http://"/>
        </fieldset>
    </form>
</div>

<div id="htmlOculto_listE" hidden>
    <div id="rowNuevoTopico">
        <table>
            <tr>
                <td width="1%"> <input type="checkbox" name="toggleTopicos[]" class="checkBox_topicos" /></td>
            </tr>
        </table>
    </div>
</div>   

<div id="module-submenu">
    <?php echo GescoreHelper::getSubmenu(); ?>
</div>