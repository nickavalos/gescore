<?php
/**
 * default body template file for Gescore view of HelloWorld component
 *
 * @version		$Id: default_body.php  $
 * @package		Congreso
 * @subpackage	Components
 * @copyright	Copyright (C) 2014 Open Source Matters, Inc. All rights reserved.
 * @author		Nick Avalos
 * @link		http://google.com/+nickavalos
 * @license		License GNU General Public License version 2 or later
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

function printFormatFromTable($date) {
    if($date != FORMAT_NO_DATE and $date != "") { 
        echo date('d/m/Y', strtotime($date));
    }
}

?>
<table id="tableInfoTab" class="table_alignTop">
    <td>
    <fieldset class="datosEdicion form_addEntidadLeft">
        <legend> Datos de la Issue</legend> 
        <div>
            <label>Revista*:</label>
            <select id="selectorRevista"  name="selectorRevista" class="sololectura chosen" style="width: 83%;">
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
            <input class="sololectura" id="issnRevistaActual" name="revista" size="50" value="<?php echo $this->issnRevista; ?>" hidden/>
            <span id="div_reloadRevistaActual"><img id="iconlock_revista" class="iconInput pointer" src="components/com_gestorcore/imgs/iconBack.png" title="Volver a la revista original" <?php echo $this->issnRevista==-1?'hidden':''; ?>></span> 
            <span class="margenIzquierdo-3"> <button id="add_revista" type="button"/> Nueva Revista</button></span>
        </div>

        <label></label>
        <table id="tableEscogerTipoEdicion" width="100%" class="table_alignBottom">
            <tr>
                <td width="25%">Tipo de edición*:</td>
                <td width="30%"><input type="radio" class="noResize" name="radioTipoEdicion" value="edicionSimple" checked> <span>Edición Simple</span></td>
                <td><input type="radio" class="noResize" name="radioTipoEdicion" value="edicionEspecial"> <span> Edición Especial </span></td>
            </tr>
        </table>

        <div id="divEdicionSimple">
            <label>Volumen del issue*: </label>
            <select id="selectorVolumenesRevista"  name="selectorVolumenRevista" >
                    <option value="-1">Nuevo Volumen</option>
                <?php foreach ($this->volumenes as $volumen): ?> 
                    <option value="<?php echo $volumen->volumen;?>"><?php echo $volumen->volumen;?></option>
                <?php endforeach;?>
            </select>
            <input size="30" placeholder=" Nuevo Volumen" id="nuevoVolumen" name="nuevoVolumen" class="ui-corner-all noResize"/>
        </div>
        <div id="divEdicionEspecial" hidden>
            <label>Tema principal*: </label>
            <input class="ui-corner-all topico_complete" id="temaPrincipal" name="temaPrincipal" size="46" />
        </div>
        <!--<div id="divCheckSpecialIssue" hidden>
            <input type="checkbox" name="specialIssue" id="checkSpecialIssue"> Edición Especial
        </div>-->

        <div id="divNumeroIssue">
            <label>Número del issue* <span><small><i>(Número más reciente del volumen:  <b><span id="ultimoNumeroVolumen">-</span></b>):</i></small></span> </label>
            <input class="sololectura selectorSpinnerEdicion" type="number" id="selectorNumeroIssue" name="numeroIssue" size="25" value="1" readonly/>
            <span><img id="iconlock" class="iconInput pointer" src="components/com_gestorcore/imgs/iconLock.png"></span> 
        </div>
    </fieldset>
    </td>
    <td>    
    <fieldset class="datosEdicion form_addEntidadRight">
        <legend> Fechas de la Issue</legend>     

        <label>Fecha límite de recepción: </label>
        <input class="date_jQuery ui-widget-content ui-corner-all" name="limRecepcion" size="44" placeholder="dd/mm/aaaa" />

        <label>Fecha definitiva de recepción: </label> 	
        <input class="date_jQuery ui-widget-content ui-corner-all" name="fechaDefinitiva" size="44" placeholder="dd/mm/aaaa" />

        <label>Fecha publicación: </label>
        <input class="date_jQuery ui-widget-content ui-corner-all" name="fechaPublicacion" size="44" placeholder="dd/mm/aaaa" />
    </fieldset>
    </td>
</table>