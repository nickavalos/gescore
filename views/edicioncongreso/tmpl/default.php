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

//$this->document->addScript(DIR_SCRIPTS_GESCORE . 'utils.js');
$this->document->addScript(DIR_SCRIPTS_GESCORE . 'submenu.js');
$this->document->addScript(DIR_SCRIPTS_GESCORE . 'funciones.js');
$this->document->addScript(DIR_SCRIPTS_GESCORE . 'jeditable.js');
$this->document->addScript(DIR_SCRIPTS_GESCORE . 'jeditable-datepicker.js');
$this->document->addScript(DIR_SCRIPTS_GESCORE . 'edicionCongreso.js');
//$this->document->addScript(DIR_SCRIPTS_GESCORE . 'min.js');

$this->document->addStyleSheet("http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css");       
$this->document->addStyleSheet(DIR_STYLESHEETS_GESCORE . 'utils.css');

function printFormatFromTable($date) {
    if($date != FORMAT_NO_DATE) { 
        echo date('d/m/Y', strtotime($date));
    }
}

?>
<form action="<?php echo JRoute::_('index.php?option=com_gestorcore&controller=congreso&task=infoEdicion&orden=' . $this->edicion->orden .
                                   '&congreso='.$this->edicion->nomCongreso); ?>" method="post" name="adminForm">
        <input type="checkbox" name="cid[]" checked hidden/>
        <div class="info"> 
            <fieldset>
                <legend>Información General de la edición (Doble click para editar)</legend>
                <input id="copyNomCongreso" value="<?php echo $this->edicion->nomCongreso; ?>" hidden />
                <input id="copyOrden_edicion" value="<?php echo $this->edicion->orden; ?>" hidden />
     
                <table class="adminlist">
                    <tr class="row0">
                        <td width="40%"> Orden de la edición:</td>
                        <td width="60%" id="_ordenEdicion" name="_ordenEdicion" class="jeditable_info"><?php echo $this->edicion->orden; ?></td>
                    </tr><tr class="row0">
                        <td> Congreso de la edición:</td>
                        <td id="_congresoEdicion" name="_congresoEdicion" class="jeditable_info_select"><?php echo $this->edicion->nomCongreso; ?></td>
                    </tr><tr class="row0">
                        <td> lugar: </td>
                        <td id="_lugarEdicion" class="jeditable_info"><?php echo $this->edicion->lugar; ?></td>
                    </tr><tr class="row0">
                        <td> link de la edición: </td>
                        <td id="_linkEdicion" class="jeditable_info"><?php echo $this->edicion->linkEdicion; ?></td>
                    </tr><tr class="row0">
                        <td> Fecha limite de recepción: </td>
                        <td id="_limRecepcion" class="jeditable_info_date"><?php printFormatFromTable($this->edicion->limRecepcion); ?></td>
                    </tr><tr class="row0">
                        <td> Fecha definitiva: </td>
                        <td id="_fechaDefinitiva" class="jeditable_info_date"><?php printFormatFromTable($this->edicion->fechaDefinitiva); ?></td>
                    </tr><tr class="row0">
                        <td> Fecha inicio de la edición: </td>
                        <td id="_fechaInicio" class="jeditable_info_date"><?php printFormatFromTable($this->edicion->fechaInicio); ?></td>
                    </tr><tr class="row0">
                        <td> Fecha final de la edición: </td>
                        <td id="_fechaFin" class="jeditable_info_date"><?php printFormatFromTable($this->edicion->fechaFin); ?></td>
                    </tr>
                </table>
            </fieldset>
            
            <input id="copyNomCongreso_listEd" value="<?php echo $this->edicion->nomCongreso; ?>" hidden />
            <table width="100%">
                <td width="50%">
                    <fieldset>
                    <legend> Valoraciones de la edición </legend>   
                        <table class="adminlist">
                            <thead>
                                <tr>
                                    <th width="50%"> Entidad de indice de valoración </th>
                                    <th width="50%"> Valoración de la edición</th>
                                </tr>
                            </thead>
                        </table>
                        <div id="valoracion_info">
                            <table id="tableValoracion_info" class="adminlist">
                                <tbody>
                                    <?php foreach($this->edicion->valoraciones as $i=>$valoracion): ?>
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
                    <legend> Tópicos de la edición </legend> 
                        <table class="adminlist">
                            <td width="1%">
                                <input id="add_topico" class="pointer" type="button" value="Lista de topicos" />
                                <input id="add_topico_congreso" class="pointer" type="button" value="Topicos del congreso" />
                                <input id="delete_topico" type="button" value="Eliminar selecc." disabled />
                                <input id="similares_topico" type="button" value="Mostrar similares" disabled />
                            </td>
                            <td>
                                <select name="topicosEdicion" id="selectTopicosEdicion" class="topicosEntidades" size="10" multiple>                    
                                    <?php
                                    foreach($this->edicion->topicos as $topico): ?>
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
            <input type="hidden" name="view" value="edicion" />
            <input type="hidden" name="task" value="infoCongreso" />
            <input type="hidden" name="boxchecked" value="0" />
            <inupt type="hidden" name="controller" value="congreso" />                
            <?php echo JHtml::_('form.token'); ?>
	</div>
</form>



<div id="dialog-form_addTopicoEntidad" title="Nuevo Topico" class="dialog-form" hidden>
    <br><p class="validateTips">Por favor seleccione los tópicos que quiere añadir a la edición.</p>
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