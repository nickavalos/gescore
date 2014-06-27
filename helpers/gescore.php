<?php
/**
 * @package		Helper
 * @subpackage          com_gescore
 * @copyright           Copyright 2013 New Life in IT Pty Ltd. All rights reserved.
 * @license		GNU General Public License version 2 or later.
 */

// No direct access
defined('_JEXEC') or die;

/**
 * Hello display helper.
 *
 * @package		Helper
 * @subpackage          com_gescore
 * @since		1.0
 */
class GescoreHelper
{
    var $viewGescore = 'gescore';
    /**
     * Configure el submenu.
     *
     * @param   string  $vName  Nombre del actual task
     *
     * @return  void
     *
     * @since   1.0
     */
    public static function addSubmenu($vName)
    {
        JSubMenuHelper::addEntry(
            JText::_('COM_GESCORE_SUBMENU_BUSCADOR'),
            'index.php?option=com_gestorcore&view=gescore',
            $vName == 'gescore'
        );
        JSubMenuHelper::addEntry(
            JText::_('COM_GESCORE_SUBMENU_CONGRESOS'),
            'index.php?option=com_gestorcore&controller=congreso&task=listCongresos',
            $vName == 'listCongresos'
        );
        JSubMenuHelper::addEntry(
            JText::_('COM_GESCORE_SUBMENU_REVISTAS'),
            'index.php?option=com_gestorcore&controller=revista&task=listRevistas',
            $vName == 'listRevistas'
        );
        JSubMenuHelper::addEntry(
            JText::_('COM_GESCORE_SUBMENU_ADD_EDICION'),
            'index.php?option=com_gestorcore&view=addEdicion',
            $vName == 'addEdicion'
        );
        JSubMenuHelper::addEntry(
            JText::_('COM_GESCORE_SUBMENU_ADD_ISSUE'),
            'index.php?option=com_gestorcore&view=addIssue',
            $vName == 'addIssue'
        );
        JSubMenuHelper::addEntry(
            JText::_('COM_GESCORE_SUBMENU_EDITORIALES'),
            'index.php?option=com_gestorcore&controller=editorial&task=listEditoriales',
            $vName == 'listEditoriales'
        );
        JSubMenuHelper::addEntry(
            JText::_('COM_GESCORE_SUBMENU_TOPICOS'),
            'index.php?option=com_gestorcore&controller=topico&task=listTopicos',
            $vName == 'listTopicos'
        );
        JSubMenuHelper::addEntry(
            JText::_('COM_GESCORE_SUBMENU_ENTIDADES_CALIDAD'),
            'index.php?option=com_gestorcore&controller=entidadCalidad&task=listEntidadesCalidad',
            $vName == 'listEntidadesCalidad'
        );
        JSubMenuHelper::addEntry(
            JText::_('COM_GESCORE_SUBMENU_AYUDA'),
            'index.php?option=com_gestorcore&view=ayuda',
            $vName == 'ayuda'
        );
    }
    
    public static function getSubmenu() {
        $submenu = array();
        $submenu[] = '<ul id="menu">';
        $submenu[] = '<li><a class="" href="index.php?option=com_gestorcore&view=gescore">Buscador</a></li>';
        $submenu[] = '<li><a class="node" href="index.php?option=com_gestorcore&controller=congreso&task=listCongresos">Congresos</a>';
        $submenu[] = '    <ul>';
        $submenu[] = '        <li><a class="icon-16-levels" href="index.php?option=com_gestorcore&controller=congreso&task=listCongresos">Lista de Congresos</a></li>';
        $submenu[] = '        <li class="separator"><span></span></li>';
        //$submenu[] = '        <li id="add_congreso"><a class="icon-16-newarticle" href="index.php?option=com_gestorcore&view=congresos&layout=default_nuevoCongreso&path=&tmpl=component#dialog-form_addCongreso">Nuevo Congreso</a></li>';
        $submenu[] = '        <li><a class="icon-16-newlevel" href="index.php?option=com_gestorcore&view=congresos&layout=default_nuevoCongreso">Nuevo Congreso</a></li>';
        $submenu[] = '        <li class="separator"><span></span></li>';
        $submenu[] = '        <li><a class="icon-16-newarticle" href="index.php?option=com_gestorcore&controller=congreso&task=nuevaEdicion">Nueva Edición</a></li>';
        $submenu[] = '    </ul>';
        $submenu[] = '</li>';
        $submenu[] = '<li><a class="node" href="index.php?option=com_gestorcore&controller=revista&task=listRevistas">Revistas</a>';
        $submenu[] = '    <ul>';
        $submenu[] = '        <li><a class="icon-16-levels" href="index.php?option=com_gestorcore&controller=revista&task=listRevistas">Lista de Revistas</a></li>';
        $submenu[] = '        <li class="separator"><span></span></li>';
        $submenu[] = '        <li><a class="icon-16-newlevel" href="index.php?option=com_gestorcore&view=revistas&layout=default_nuevaRevista">Nueva Revista</a></li>';
        $submenu[] = '        <li class="separator"><span></span></li>';
        $submenu[] = '        <li><a class="icon-16-newarticle" href="index.php?option=com_gestorcore&controller=revista&task=nuevaIssue">Nueva Issue</a></li>';
        $submenu[] = '    </ul>';
        $submenu[] = '</li>';
//        $submenu[] = '<li><a class="node" href="index.php?option=com_admin&amp;view=sysinfo">Editoriales</a>';
//        $submenu[] = '    <ul>';
//        $submenu[] = '        <li><a class="icon-16-module" href="index.php?option=com_users&amp;view=users">Lista de editoriales</a></li>';
//        $submenu[] = '        <li class="separator"><span></span></li>';
//        $submenu[] = '        <li><a class="icon-16-newarticle" href="index.php?option=com_users&amp;view=users">Nueva Editorial</a></li>';
//        $submenu[] = '    </ul>';
//        $submenu[] = '</li>';
        $submenu[] = '<li><a class="node" href="index.php?option=com_gestorcore&amp;view=topico">Tópicos</a>';
        $submenu[] = '    <ul>';
        $submenu[] = '        <li><a class="icon-16-massmail" href="index.php?option=com_gestorcore&amp;view=topico">Lista de tópicos</a></li>';
        $submenu[] = '        <li class="separator"><span></span></li>';
        $submenu[] = '        <li><a class="icon-16-newarticle" href="index.php?option=com_gestorcore&amp;view=topico&amp;layout=default_nuevotopico">Nuevo Tópico</a></li>';
        $submenu[] = '    </ul>';
        $submenu[] = '</li>';
        $submenu[] = '<li class="separator"><span></span></li>';
        $submenu[] = '<li><a class="node" href="index.php?option=com_gestorcore&view=entidadCalidad">Entidades de Calidad</a>';
        $submenu[] = '    <ul>';
        $submenu[] = '        <li><a class="icon-16-levels" href="index.php?option=com_gestorcore&view=entidadCalidad">Lista de Entidades de Calidad</a></li>';
        $submenu[] = '        <li class="separator"><span></span></li>';
        $submenu[] = '        <li><a class="icon-16-newarticle" href="index.php?option=com_gestorcore&view=entidadcalidad&layout=default_nuevaentidadcalidad">Nueva Entidad de Calidad</a></li>';
        $submenu[] = '    </ul>';
        $submenu[] = '</li>';
        $submenu[] = '<li><a href="index.php?option=com_gestorcore&view=import">Importar</a>';
        $submenu[] = '    <ul>';
        $submenu[] = '        <li><a class="icon-16-clear" href="index.php?option=com_gestorcore&view=import">Scopus</a></li>';
        //$submenu[] = '        <li class="separator"><span></span></li>';
        //$submenu[] = '        <li><a class="icon-16-levels" href="index.php?option=com_gestorcore&view=entidadcalidad&layout=default_nuevaentidadcalidad">Nueva Entidad de Calidad</a></li>';
        $submenu[] = '    </ul>';
        $submenu[] = '</li>';
        $submenu[] = '</ul>';
        
        return implode('', $submenu);
    }

    /**
     * Gets a list of the actions that can be performed.
     *
     * @return	JObject
     * @since	1.6
     */
    public static function getActions()
    {
        $user	= JFactory::getUser();
        $result	= new JObject;

        $actions = array(
                'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.state', 'core.delete'
        );

        foreach ($actions as $action) {
                $result->set($action, $user->authorise($action, 'com_gestorcore'));
        }

        return $result;
    }
}