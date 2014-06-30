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

$this->document = JFactory::getDocument();
$this->document->addScript(DIR_SCRIPTS_GESCORE . "jquery-1-9-1.js");
$this->document->addScript(DIR_SCRIPTS_GESCORE . "jquery-ui.js");

$this->document->addScript(DIR_SCRIPTS_GESCORE . 'submenu.js');
$this->document->addScript(DIR_SCRIPTS_GESCORE . 'funciones.js');
$this->document->addScript(DIR_SCRIPTS_GESCORE . 'nuevaRevista.js');

$this->document->addStyleSheet("http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css");       
$this->document->addStyleSheet(DIR_STYLESHEETS_GESCORE . 'utils.css');
?>
<form action="<?php echo JRoute::_('index.php?option=com_gestorcore&controller=revista&task=listRevistas'); ?>" method="post" name="adminForm" id="form_nuevaRevista">
    <div id="dialog-form_addRevista" title="Nueva Revista" class="form_addEntidad">
        <div id="content_addRevista">
            <p class="validateTips">Ingrese los datos de la nueva revista.</p>
            <table><tr>
                <td><fieldset id="fieldRevista">
                    <legend> Entidad Publicadora (EP)</legend>    
                    <label for="issnEntidad">Issn*</label>
                    <input type="text" name="issnEntidad" id="issnEntidad" class="dialog-ok text ui-widget-content ui-corner-all" />
                    <label for="nomEntidad">Nombre</label>
                    <input type="text" name="nomEntidad" id="nomEntidad" class="dialog-ok text ui-widget-content ui-corner-all" />
                    <label for="acronimoEntidad">Acrónimo de la entidad</label>
                    <input type="text" name="acronimoEntidad" id="acronimoEntidad" class="dialog-ok text ui-widget-content ui-corner-all" />
                    <label for="linkEntidad">Link de la entidad</label>
                    <input type="text" name="linkEntidad" id="linkEntidad" class="dialog-ok text ui-widget-content ui-corner-all" placeholder="http://"/>
                    <label for="periodicidadEntidad">Periodicidad (meses)</label>
                    <input type="number" name="periodicidadEntidad" id="periodicidadEntidad" class="selectorSpinnerEdicion dialog-ok text ui-widget-content ui-corner-all" />
                </fieldset></td>
                <td><fieldset id="fieldEditorialRevista">
                    <legend> Editorial de la EP </legend>
                    <table id="tableEscogerEditorial">
                        <tr>
                            <td><input type="radio" name="radioEntidadPublicadora" value="editorialExistente" id="editorialExistente" checked> Editorial Existente </td>
                        </tr>
                        <tr>
                            <td><input type="radio" name="radioEntidadPublicadora" value="nuevaEditorial" id="nuevaEditorial"> Nueva Editorial </td>
                        </tr>
                    </table>
                    <div id="existenteEditorialEntidad">    
                        <select name="editorialEntidad" id="selectEditorialesEntidad" size="14">                    
                        </select>
                    </div>
                    <div id="nuevaEditorialEntidad" hidden>    
                        <label for="nomEditorial">Nombre de la Editorial</label>
                        <input type="text" name="nomEditorial" id="nomEditorial" class="dialog-ok text ui-widget-content ui-corner-all" />
                        <label for="siglasEditorial">Siglas de la editorial</label>
                        <input type="text" name="siglasEditorial" id="siglasEditorial" class="dialog-ok text ui-widget-content ui-corner-all" />
                        <label for="linkEditorial">Link de la editorial</label>
                        <input type="text" name="linkEditorial" id="linkEditorial" class="dialog-ok text ui-widget-content ui-corner-all"  placeholder="http://"/>
                    </div>
                </fieldset></td>    
            </tr></table>
        </div>
    </div>
    <div>
        <input type="hidden" name="option" value="com_gestorcore" />
        <input type="hidden" name="task" value="" />
        <input type="hidden" name="controller" value="revista" />
        <?php echo JHtml::_('form.token'); ?>
    </div>
</form>

<div id="module-submenu">
    <?php echo GescoreHelper::getSubmenu(); ?>
</div>