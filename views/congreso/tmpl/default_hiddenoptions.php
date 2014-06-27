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

?>

<div id="htmlOculto" hidden>
    <table id="filaTableNuevosTopicos">
        <tr>
            <td><input class="topicosEdicion_topico" name="nuevoTopico[]" size="50" placeholder=" Nuevo Tópico"/></td>
            <td>
                <input class="topicosEdicion_descripcion" name="descripcionNuevoTopico[]" size="80" placeholder=" Descripción del nuevo tópico"/> 
                <img class="iconInput pointer iconDelete_sinPreguntar" src="components/com_gestorcore/imgs/iconDelete.png">
            </td>
        </tr>
    </table>
    <select name="topicosFromCongreso[]" class="crossSelect" multiple> </select>
    <table id="filaTableEntidadValoracion">
        <tr class="row0">
            <td> 
                <select class="selectorEntidadCalidad"  name="selectorEntidadCalidad[]">
                    <option value="" selected>Selecciona una Entidad</option>
                    <?php foreach ($this->entidadesCalidad as $entidadCalidad): ?>
                            <option value="<?php echo $entidadCalidad->nombre;?>"> <?php echo $entidadCalidad->nombre;?> </option>
                    <?php endforeach;?>    
                </select>
            </td>
            <td class="selectorCategoriaCalidad">
                <!--<select class="selectorCategoriaCalidad" name="selectorCategoriaCalidad[]"></select> -->
            </td>
            <td class="center">
                <img class="iconInput pointer iconDelete_preguntar" src="components/com_gestorcore/imgs/iconDelete.png">
            </td>
        </tr>
    </table>
    
    
</div>