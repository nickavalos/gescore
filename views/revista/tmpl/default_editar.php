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

JHtml::_('behavior.tooltip');


$this->document = JFactory::getDocument();
$this->document->addScript(DIR_SCRIPTS_GESCORE . "jquery-1-9-1.js");
$this->document->addScript(DIR_SCRIPTS_GESCORE . "jquery-ui.js");

$this->document->addScript(DIR_SCRIPTS_GESCORE . 'chosen.jquery.js');
$this->document->addScript(DIR_SCRIPTS_GESCORE . 'utils.js');
$this->document->addScript(DIR_SCRIPTS_GESCORE . 'submenu.js');
$this->document->addScript(DIR_SCRIPTS_GESCORE . 'utilsRevista.js');
$this->document->addScript(DIR_SCRIPTS_GESCORE . 'funciones.js');
$this->document->addScript(DIR_SCRIPTS_GESCORE . 'revistaEditIssue.js');
$this->document->addScript(DIR_SCRIPTS_GESCORE . 'crossSelect.js');

$this->document->addStyleSheet(DIR_STYLESHEETS_GESCORE . 'chosen.css');
$this->document->addStyleSheet(DIR_STYLESHEETS_GESCORE . 'utils.css');
$this->document->addStyleSheet(DIR_STYLESHEETS_GESCORE . 'crossSelect.css');
//JHTML::stylesheet('jquery-ui.css', "/administrator/components/com_gestorcore/stylesheets/");

//$document = JFactory::getDocument();
//$document->addScriptDeclaration("window.addEvent('domready', function() { ejecutar(); });");
//$document->addScriptDeclaration("window.addEvent('domready', function() { ejecutarCross(); });");
//$document->addScriptDeclaration("window.addEvent('domready', function() { datePicker(); });");

$this->edicionEspecial = ($this->issue->volumen == ID_EDICION_ESPECIAL);

function printFormatFromTable($date) {
    if($date != FORMAT_NO_DATE) { 
        echo date('d/m/Y', strtotime($date));
    }
}

?>

<!-- <jdoc:include type="head" /> -->

<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
<!--<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script src="/administrator/components/com_gestorcore/views/utils.js"></script> -->

<form action="<?php echo JRoute::_($this->uriPOST); ?>" method="post" name="adminForm" id="form_gestionIssue">
    <?php echo JHtml::_('tabs.start','config-tabs-', array('useCookie'=>0)); 
        echo JHtml::_('tabs.panel', "Información general", 'infoTab');
        ?>
    
            <table id="tableInfoTab" class="table_alignTop">
                <td>
                <fieldset class="datosEdicion form_addEntidadLeft">
                    <legend> Datos de la Issue</legend> 
                    <div>
                        <label>Revista*:</label>
                        <select id="selectorRevista"  name="selectorRevista" class="sololectura chosen" style="width:83%;">
                            <option value="-1">Selecciona Revista</option>
                            <?php foreach ($this->listaRevistas as $revista): 
                                if ($revista->issn == $this->issnRevista):?>
                                    <option value="<?php echo $revista->issn;?>" selected>
                                <?php else: ?>
                                    <option value="<?php echo $revista->issn;?>">
                                <?php endif;?>
                                <?php echo $revista->issn;?> </option>
                            <?php endforeach;?>
                        </select>
                        <input class="sololectura" id="issnRevistaActual" name="issnRevistaActual" size="50" value="<?php echo $this->issnRevista; ?>" hidden/>
                        <span id="div_reloadRevistaActual"><img id="iconlock_revista" class="iconInput pointer" src="components/com_gestorcore/imgs/iconBack.png" title="Volver a la revista original" <?php echo $this->issnRevista==-1?'hidden':''; ?>></span> 
                        <span class="margenIzquierdo-3"> <button id="add_revista" type="button"/> Nueva Revista</button></span>
                    </div>

                    <label></label>
                    <table id="tableEscogerTipoEdicion" width="100%" class="table_alignBottom">
                        <tr>
                            <td width="25%">Tipo de edición*:</td>
                            <td width="30%"><input type="radio" class="noResize" name="radioTipoEdicion" value="edicionSimple" <?php echo (!$this->edicionEspecial)? 'checked':''; ?> > <span>Edición Simple</span></td>
                            <td><input type="radio" class="noResize" name="radioTipoEdicion" value="edicionEspecial" <?php echo ($this->edicionEspecial)? 'checked':''; ?> > <span> Edición Especial </span></td>
                        </tr>
                    </table>

                    <div id="divEdicionSimple" <?php echo ($this->edicionEspecial)? 'hidden':''; ?>>
                        <label>Volumen del issue*: </label>
                        <input class="sololectura" id="volumenIssueActual" name="volumenIssueActual" size="50" value="<?php echo $this->issue->volumen; ?>" hidden/>
                        <select id="selectorVolumenesRevista" name="selectorVolumenRevista" >
                                <option value="-1">Nuevo Volumen</option>
                            <?php foreach ($this->volumenes as $volumen):
                                if ($volumen->volumen != 'specialIssue'):
                                    if ($volumen->volumen == $this->issue->volumen):?> 
                                        <option value="<?php echo $volumen->volumen;?>" selected><?php echo $volumen->volumen;?></option>
                                    <?php else: ?>
                                        <option value="<?php echo $volumen->volumen;?>"><?php echo $volumen->volumen;?></option>
                                    <?php endif; 
                                endif;?>
                            <?php endforeach;?>
                        </select>
                        <input size="30" placeholder=" Nuevo Volumen" id="nuevoVolumen" name="nuevoVolumen" class="sololectura ui-corner-all noResize" disabled/>
                    </div>
                    <div id="divEdicionEspecial" <?php echo (!$this->edicionEspecial)? 'hidden':''; ?>>
                        <label>Tema principal*: </label>
                        <input class="ui-corner-all topico_complete" id="temaPrincipal" name="temaPrincipal" size="46" value="<?php echo $this->issue->temaPrincipal;?>"/>
                    </div>
                    <div id="divNumeroIssue" <?php echo ($this->edicionEspecial)? 'hidden':''; ?>>
                        <label>Número del issue* <span><small><i>(Número más reciente del volumen:  <b><span id="ultimoNumeroVolumen"><?php echo $this->ultimoNumeroVolumen->max; ?> </span></b>):</i></small></span> </label>
                        <input class="sololectura selectorSpinnerEdicion" type="number" id="selectorNumeroIssue" name="numeroIssue" size="25" value="<?php echo ($this->edicionEspecial)?"":$this->issue->numero;?>" readonly/>
                        <span><img id="iconlock" class="iconInput pointer" src="components/com_gestorcore/imgs/iconLock.png"></span>
                        <input class="sololectura" id="numeroIssueActual" name="numeroIssueActual" size="50" value="<?php echo $this->issue->numero; ?>" hidden/>
                    </div>
                </fieldset>
                </td>
                <td>    
                <fieldset class="datosEdicion form_addEntidadRight">
                    <legend> Fechas de la Issue</legend>     

                    <label>Fecha límite de recepción: </label>
                    <input class="date_jQuery ui-widget-content ui-corner-all" name="limRecepcion" size="44" placeholder="dd/mm/aaaa" value="<?php printFormatFromTable($this->issue->limRecepcion);?>"/>

                    <label>Fecha definitiva de recepción: </label> 	
                    <input class="date_jQuery ui-widget-content ui-corner-all" name="fechaDefinitiva" size="44" placeholder="dd/mm/aaaa" value="<?php printFormatFromTable($this->issue->fechaDefinitiva);?>"/>

                    <label>Fecha publicación: </label>
                    <input class="date_jQuery ui-widget-content ui-corner-all" name="fechaPublicacion" size="44" placeholder="dd/mm/aaaa" value="<?php printFormatFromTable($this->issue->fechaPublicacion);?>"/>
                </fieldset>
                </td>
            </table>
    
        <?php echo JHtml::_('tabs.panel', "Tópicos", 'topicosTab'); ?>
            <div class="topicosOptions">
                <div id="divTopicosFromRevista" class="divTopicosFromEntidad">
                    <select name="topicosFromRevista[]" class="crossSelectRevista" multiple>
                        <?php 
                        foreach($this->topicosFromRevista as $topico):
                            $trobat = false;
                            foreach ($this->issue->topicos as $key => $topicoIssue):
                                if ($topicoIssue->nomTopico == $topico->nomTopico) {
                                    unset($this->issue->topicos[$key]);
                                    $trobat=true;
                                }
                            endforeach;
                            if ($trobat):
                            ?>
                                <option value="<?php echo $topico->nomTopico;?>" selected>
                            <?php else: ?>
                                <option value="<?php echo $topico->nomTopico;?>">
                            <?php endif;?>    

                            <?php echo $topico->nomTopico; ?> 
                            </option>
                        <?php endforeach;?>
                    </select>
                </div>   
                
                <?php echo JHtml::_('sliders.start', 'PanelID', array('useCookie'=>0)); ?>
                    <?php echo JHtml::_('sliders.panel', "Otros Tópicos", ''); ?>
                        <div class="ui-widget" id="otrosTopicosWidget">
                            <br><input id="inputAddTopico" class="ui-corner-all" size="60" placeholder=" Criterio de búsqueda"/>
                            <input id="checkIncluirDescripcion" type="checkbox" checked> Incluir descripción de los tópicos en la búsqueda
                            <div id="topicosSeleccionados">
                                <br><br><p>Tópicos seleccionados:</p>
                                <select size="7" id="listTopicosSeleccionados"  multiple>
                                    <?php foreach ($this->issue->topicos as $key => $topicoIssue):?>
                                    <option value="<?php echo $topicoIssue->nomTopico;?>"><?php echo $topicoIssue->nomTopico;?></option>
                                    <?php endforeach; ?>
                                </select>
                                <select size="7" name="listTopicosSeleccionados[]" id="copy_listTopicosSeleccionados"  multiple hidden>
                                    <?php foreach ($this->issue->topicos as $key => $topicoIssue):?>
                                    <option value="<?php echo $topicoIssue->nomTopico;?>" selected><?php echo $topicoIssue->nomTopico;?></option>
                                    <?php endforeach; ?>
                                </select> 
                                <div class="topicosSeleccionados_deleteTopico">
                                    <button id="delete_topico" type="button">Eliminar seleccionados</button>                        
                                </div>
                            </div>
                        </div>
                    <?php echo JHtml::_('sliders.panel', "Nuevos Tópicos", ''); ?>
                        <?php echo $this->loadTemplate('nuevostopicos'); ?>

                <?php echo JHtml::_('sliders.end'); ?>
            </div>
                
        <?php echo JHtml::_('tabs.panel', "Valoraciones", 'valoracionTab'); ?>
            
            <p>Nota: Recuerde que una entidad de valoración no puede valorar más de una vez la misma edición.</p>
            <table class="adminlist" width="100%" id="tableValoraciones"> 	
                <thead>
                <tr>
                    <th width="60%"> Entidad de indice de valoración </th>
                    <th width="30%"> Valoración de la issue</th>
                    <th width="10%"> Eliminar?<br><span><input type="checkbox" id="checkBox_noPreg"/>Sin preguntar</span> </th>
                </tr>
                </thead>
                <tbody>
                    <?php foreach($this->issue->valoraciones as $i=>$valoracion): ?>
                        <tr class="row0">
                            <td> 
                                <select class="selectorEntidadCalidad chosen"  name="selectorEntidadCalidad[]" style="width: 72%;">
                                    <?php foreach ($this->entidadesCalidad as $entidadCalidad): 
                                        if ($entidadCalidad->nombre == $valoracion->nomEntidad): ?>
                                            <option value="<?php echo $entidadCalidad->nombre;?>" selected> <?php echo $entidadCalidad->nombre;?> </option>
                                        <?php else:?>
                                            <option value="<?php echo $entidadCalidad->nombre;?>"> <?php echo $entidadCalidad->nombre;?> </option>
                                        <?php endif;?>
                                    <?php endforeach;?>    
                                </select>
                            </td>
                            <td class="selectorCategoriaCalidad">
                                <?php if ($valoracion->tipoCategoria == GESCORE_TIPO_CATEGORIA_LISTA): ?>
                                    <select name="selectorCategoriaCalidad[]">
                                        <?php foreach($valoracion->categorias as $categoria):
                                            if ($categoria->categoria == $valoracion->indice): ?>
                                                <option value="<?php echo $categoria->categoria;?>" selected> <?php echo $categoria->categoria;?> </option>
                                            <?php else:?>
                                                <option value="<?php echo $categoria->categoria;?>"> <?php echo $categoria->categoria;?> </option>
                                            <?php endif;?>
                                        <?php endforeach;?>    
                                    </select> 
                                <?php else: ?>
                                    <input size="30" class='selectorSpinnerEdicion' placeholder=" Valoración numérica (e.j: 7.45)" name="selectorCategoriaCalidad[]" value="<?php echo $valoracion->indice;?>"/>
                                <?php endif; ?>
                            </td>
                            <td>
                                <img class="iconInput pointer iconDelete_preguntar" src="components/com_gestorcore/imgs/iconDelete.png">
                            </td>
                        </tr>
                    <?php endforeach;?>
                </tbody>
            </table>
            <div class="div_addValoracion">
                <button id="buttonAddValoracion" type="button">Añadir nueva valoración</button>
            </div>
        
    <?php echo JHtml::_('tabs.end'); ?>
        
    <div>
        <input type="hidden" name="option" value="com_gestorcore" />
        <input type="hidden" name="task" value="" />
        <inupt type="hidden" name="controller" value="revista" />                
        <?php echo JHtml::_('form.token'); ?>
    </div>
</form>

<?php echo $this->loadTemplate('hiddenOptions'); ?>    

<div id="dialog-form_addRevista" title="Nueva Revista" class="dialog-form" hidden>
    <br>
    <div></div>
</div>
<div id="module-submenu">
    <?php echo GescoreHelper::getSubmenu(); ?>
</div>