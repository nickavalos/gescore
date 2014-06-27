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
        <legend> Datos de la Edición</legend> 
        <div>
            <label>Congreso*:</label>
            <div id="div_selectorCongreso">
                <select id="selectorCongreso"  name="selectorCongreso" class="sololectura chosen" style="width: 83%;">
                    <option value="-1">Selecciona Congreso</option>
                    <?php foreach ($this->listaCongresos as $congreso): 
                        if ($congreso->nombre == $this->nomCongreso):?>
                        <option value="<?php echo $congreso->nombre;?>" selected>
                        <?php else: ?>
                            <option value="<?php echo $congreso->nombre;?>">
                        <?php endif;?>
                        <?php echo $congreso->nombre;?> </option>
                    <?php endforeach;?>
                </select>
                <input class="sololectura" id="congresoActual" name="congresoActual" size="50" value="<?php echo $this->nomCongreso; ?>" hidden/>

                <span id="div_reloadCongresoActual"><img id="reloadCongresoActual" class=" pointer" src="components/com_gestorcore/imgs/iconBack.png" title="Volver al congreso original" <?php echo $this->nomCongreso==-1?'hidden':''; ?>></span>
            </div>
            <span class="margenIzquierdo-3"> <button id="add_congreso" type="button"/> Nuevo Congreso</button></span>
        </div>

        <input id="ordenEdicion" name="ordenActual" size="50" value="<?php echo $this->edicion->orden; ?>" hidden/>
        <label for="selectorEdicion">Número de edición*: </label>
        <input class="sololectura selectorSpinnerEdicion noResize" type="number" id="selectorEdicion" name="orden" size="10" value=<?php echo $this->numeroEdicionMostrada; ?> readonly/>
        <span><img id="iconlock" class="iconInput pointer" src="components/com_gestorcore/imgs/iconLock.png" /></span> 
        <div style="display: table-cell;">(Edición más reciente:  <b><span id="ultimaEdicionNumber"><?php echo $this->ultimaEdicionCongreso->max; ?></span></b>)</div>

        <label>Lugar: </label>
        <input class="ui-widget-content ui-corner-all" name="lugar" size="46" value="<?php echo $this->edicion->lugar; ?>" />

        <label>Link de la Edicion: </label>
        <input class="ui-widget-content ui-corner-all" name="linkEdicion" size="46" value="<?php echo $this->edicion->linkEdicion; ?>" />

    </fieldset>
    </td>
    <td>    
    <fieldset class="datosEdicion form_addEntidadRight">
        <legend> Fechas de la Edición</legend> 

        <label>Fecha de Recepción de publicaciones: </label>
        <br><input class="date_jQuery ui-widget-content ui-corner-all" name="limRecepcion" size="44" placeholder="dd/mm/aaaa" value="<?php printFormatFromTable($this->edicion->limRecepcion); ?>" />

        <label>Fecha definitiva de recepción: </label>
        <br><input class="date_jQuery ui-widget-content ui-corner-all" name="fechaDefinitiva" size="44" placeholder="dd/mm/aaaa" value="<?php printFormatFromTable($this->edicion->fechaDefinitiva); ?>" />

        <label>Fecha de inicio: </label>
        <br><input class="date_ini ui-widget-content ui-corner-all" name="fechaInicio" size="44" placeholder="dd/mm/aaaa" value="<?php printFormatFromTable($this->edicion->fechaInicio); ?>" />

        <label>Fecha final: </label>
        <br><input class="date_end ui-widget-content ui-corner-all" name="fechaFin" size="44" placeholder="dd/mm/aaaa" value="<?php printFormatFromTable($this->edicion->fechaFin); ?>" />
    </fieldset>
    </td>
</table>