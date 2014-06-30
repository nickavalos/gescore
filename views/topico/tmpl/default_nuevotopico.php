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
$this->document->addScript(DIR_SCRIPTS_GESCORE . 'nuevoTopico.js');
$this->document->addScript(DIR_SCRIPTS_GESCORE . 'funciones.js');

$this->document->addStyleSheet("http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css");       
$this->document->addStyleSheet(DIR_STYLESHEETS_GESCORE .  'utils.css');
?>
<form action="<?php echo JRoute::_('index.php?option=com_gestorcore&view=topico'); ?>" method="post" name="adminForm" id="form_nuevoTopico">
    <!--<input type="button" id="add_congreso" value="Add congreso"/>-->
    <div id="dialog-form_addTopico" class="form_addEntidad">
        <div id="content_addTopico">
            <label><p class="validateTips"> Ingrese los datos del nuevo tópico </p></label>
            <table class="table_alignTop"><tr>
                <td><fieldset class="fieldMinSize">
                    <legend> Información Básica</legend>
                    <label for="nomTopico">Nombre</label>
                    <input type="text" name="nomTopico" id="nomTopico" class="text ui-widget-content ui-corner-all" />
                    <label for="descripcionTopico">Descripción</label>
                    <textarea name="descripcionTopico" id="descripcionTopico" class="text ui-widget-content ui-corner-all" rows="5"></textarea>
                </fieldset></td>
                <td><fieldset class="fieldMinSize panelform">
                    <legend> Tópicos Similares </legend>
                    <div class="ui-widget" id="nuevoTopico_similares">
                        <input class="criterioBusqueda ui-corner-all" placeholder=" Buscador de tópicos"/>
                        <input id="checkIncluirDescripcion" type="checkbox" checked> Incluir descripción de los tópicos en la búsqueda
                        <div id="topicosSeleccionados">
                            <br><br><p>Tópicos seleccionados:</p>
                            <select size="8" id="listSimilaresSeleccionados" multiple></select>
                            <select size="8" name="listSimilaresSeleccionados[]" id="copy_listSimilaresSeleccionados" multiple hidden></select> 
                            <div class="topicosSimilares_deleteTopico">
                                <button id="delete_topico" type="button">Eliminar seleccionados</button>                        
                            </div>
                        </div>
                    </div>
                </fieldset></td>    
            </tr></table>
        </div>
    </div>
    <div>
        <input type="hidden" name="option" value="com_gestorcore" />
        <input type="hidden" name="task" value="" />
        <inupt type="hidden" name="controller" value="topico" />                
        <?php echo JHtml::_('form.token'); ?>
    </div>
</form>

<div id="htmlOculto" hidden>
    
</div>

<div id="module-submenu">
    <?php echo GescoreHelper::getSubmenu(); ?>
</div>