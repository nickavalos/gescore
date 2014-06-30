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

$this->document->addScript(DIR_SCRIPTS_GESCORE . 'jeditable.js');
$this->document->addScript(DIR_SCRIPTS_GESCORE . 'submenu.js');
$this->document->addScript(DIR_SCRIPTS_GESCORE . 'funciones.js');
$this->document->addScript(DIR_SCRIPTS_GESCORE . 'chosen.jquery.js');
$this->document->addScript(DIR_SCRIPTS_GESCORE . 'jeditable-datepicker.js');
$this->document->addScript(DIR_SCRIPTS_GESCORE . 'issueRevista.js');

$this->document->addStyleSheet("http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css");       
$this->document->addStyleSheet(DIR_STYLESHEETS_GESCORE . 'utils.css');
$this->document->addStyleSheet(DIR_STYLESHEETS_GESCORE . 'chosen.css');

function printFormatFromTable($date) {
    if($date != FORMAT_NO_DATE) { 
        echo date('d/m/Y', strtotime($date));
    }
}
?>
<form action="<?php echo JRoute::_('index.php?option=com_gestorcore&controller=revista&task=infoIssue&volumen=' . $this->issue->volumen . '&numero=' . $this->issue->numero .
                                   '&revista='.$this->issue->issnRevista); ?>" method="post" name="adminForm">
        <!--<input type="checkbox" name="boxchecked" hidden/>-->
        <input type="checkbox" name="cid[]" checked hidden/>
        <div class="info"> 
            <fieldset>
                <legend>Información General del Issue (Doble click para editar)</legend>
                <input id="copy_volumenIssue" value="<?php echo $this->issue->volumen; ?>" hidden />
                <input id="copy_numeroIssue" value="<?php echo $this->issue->numero; ?>" hidden />
                <input id="copy_issnRevista" value="<?php echo $this->issue->issnRevista; ?>" hidden />
     
                <table class="adminlist">
                    <tr class="row0">
                        <td> Revista de la issue:</td>
                        <td id="_revistaIssue" name="_revistaIssue" class="jeditable_info_select"><?php echo $this->issue->issnRevista; ?></td>
                    </tr><tr class="row0">
                        <td width="40%"> volumen de la issue:</td>
                        <td width="60%" id="_volumenIssue" name="_volumenIssue" class="jeditable_info"><?php echo $this->issue->volumen; ?></td>
                    </tr><tr class="row0">
                        <td> Numero de la issue:</td>
                        <td id="_numeroIssue" name="_numeroIssue" class="jeditable_info"><?php echo $this->issue->numero; ?></td>
                    <?php if ($this->issue->temaPrincipal != null):?>
                        </tr><tr class="row0">
                            <td> Tema principal:</td>
                            <td id="_temaPrincipal" name="_temaPrincipal" title="Para editar el tema principal hágalo desde el apartado de edición (arriba a la derecha)"><?php echo $this->issue->temaPrincipal; ?></td>
                    <?php endif;?>
                    </tr><tr class="row0">
                        <td> Fecha limite de recepción: </td>
                        <td id="_limRecepcion" class="jeditable_info_date"><?php printFormatFromTable($this->issue->limRecepcion); ?></td>
                    </tr><tr class="row0">
                        <td> Fecha definitiva: </td>
                        <td id="_fechaDefinitiva" class="jeditable_info_date"><?php printFormatFromTable($this->issue->fechaDefinitiva); ?></td>
                    </tr><tr class="row0">
                        <td> Fecha de publicación: </td>
                        <td id="_fechaPublicacion" class="jeditable_info_date"><?php printFormatFromTable($this->issue->fechaPublicacion); ?></td>
                    </tr>
                </table>
            </fieldset>
            
            <!--<input id="copyNomCongreso_listEd" value="<?php //echo $this->issue->issnRevista; ?>" hidden /> -->
            <table width="100%">
                <td width="50%">
                    <fieldset>
                    <legend> Valoraciones de la issue </legend>   
                        <table class="adminlist">
                            <thead>
                                <tr>
                                    <th width="50%"> Entidad de indice de valoración </th>
                                    <th width="50%"> Valoración de la issue</th>
                                </tr>
                            </thead>
                        </table>
                        <div id="valoracion_info">
                            <table id="tableValoracion_info" class="adminlist">
                                <tbody>
                                    <?php foreach($this->issue->valoraciones as $i=>$valoracion): ?>
                                        <tr class="row0" >
                                            <td width="50%"><?php echo $valoracion->nomEntidad; ?></td>
                                            <td width="50%"><?php echo $valoracion->indice; ?></td>
                                        </tr>
                                    <?php endforeach;?>
                                </tbody>
                            </table>
                        </div>
                    </fieldset>
                </td>
                <td width="50%">
                    <fieldset>
                    <legend> Tópicos de la issue </legend> 
                        <table class="adminlist">
                            <td width="1%">
                                <input id="add_topico" class="pointer" type="button" value="Lista de topicos general" />
                                <input id="add_topico_revista" class="pointer" type="button" value="Tópicos de la revista" />
                                <input id="delete_topico" type="button" value="Eliminar seleccionados" disabled />
                                <input id="similares_topico" type="button" value="Mostrar similares" disabled />
                            </td>
                            <td>
                                <select name="topicosEdicion" id="selectTopicosIssue" class="topicosEntidades" size="10" multiple>                    
                                    <?php
                                    foreach($this->issue->topicos as $topico): ?>
                                        <option value="<?php echo $topico->nomTopico;?>"><?php echo $topico->nomTopico;?> </option>
                                    <?php endforeach;?>
                                </select>
                            </td>
                        </table>
                    </fieldset>
                </td>
            </table>
        </div>
        <table class="adminlist">
	</table>
	<div>
            <input type="hidden" name="option" value="com_gestorcore" />
            <input type="hidden" name="view" value="issueRevista" />
            <input type="hidden" name="task" value="infoIssue" />
            <input type="hidden" name="boxchecked" value="0" />
            <inupt type="hidden" name="controller" value="revista" />                
            <?php echo JHtml::_('form.token'); ?>
	</div>
</form>



<div id="dialog-form_addTopicoEntidad" title="Nuevo Topico" class="dialog-form" hidden>
    <br><p class="validateTips">Por favor seleccione los tópicos que quiere añadir a la Issue.</p>
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