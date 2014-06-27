<?php
/**
 * Home Congresos, Gestor de congresos y revistas.
 *
 * @version		1.0 chdemko $
 * @package		Congreso
 * @subpackage          Components
 * @copyright           Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @author		Nick Avalos
 * @link		http://google.com/+nickavalos 
 * @license		License GNU General Public License version 2 or later
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
// load tooltip behavior

JHtml::_('behavior.tooltip');

$this->document = JFactory::getDocument();
$this->document->addScript(DIR_SCRIPTS_GESCORE . "jquery-1-9-1.js");
$this->document->addScript(DIR_SCRIPTS_GESCORE . "jquery-ui.js");

$this->document->addScript(DIR_SCRIPTS_GESCORE . 'submenu.js');
//$this->document->addScript(DIR_SCRIPTS_GESCORE . 'sortTab.js');

$this->document->addStyleSheet("http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css");       
$this->document->addStyleSheet(DIR_STYLESHEETS_GESCORE . 'utils.css');
//$this->document->addStyleSheet(DIR_STYLESHEETS_GESCORE . 'sortTab.css');
?>

<script type="text/javascript">
    function changeCSS(cssFile, cssLinkIndex) {

        var oldlink = document.getElementsByTagName("link").item(cssLinkIndex);

        var newlink = document.createElement("link")
        newlink.setAttribute("rel", "stylesheet");
        newlink.setAttribute("type", "text/css");
        newlink.setAttribute("href", cssFile);

        document.getElementsByTagName("head").item(0).replaceChild(newlink, oldlink);
    }
</script>

<form action="<?php echo JRoute::_('index.php?option=com_gestorcore&controller=congreso&task=listCongresos'); ?>" method="post" name="adminForm">
	<table class="adminlist">
		<thead><?php echo $this->loadTemplate('head');?></thead>
		<tfoot><?php echo $this->loadTemplate('foot');?></tfoot>
		<tbody><?php echo $this->loadTemplate('body');?></tbody>
	</table>
	<div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>

<div id="module-submenu">
    <?php echo GescoreHelper::getSubmenu(); ?>
</div>



<!--
<div class="container">

    <div id="tablewrapper">
        <div id="tableheader">
            <div class="search">
                <select id="columns" onchange="sorter.search('query')"></select>
                <input type="text" id="query" onkeyup="sorter.search('query')" />
            </div>
            <span class="details">
                <div>Records <span id="startrecord"></span>-<span id="endrecord"></span> of <span id="totalrecords"></span></div>
                <div class="btn-reset"><a class="button blue" href="javascript:sorter.reset()">reset</a></div>
            </span>
        </div>
        <table cellpadding="0" cellspacing="0" border="0" id="table" class="tinytable">
            <thead>
                <tr>
                    <th class="nosort"><h3>ID</h3></th>
                    <th><h3>Name</h3></th>
                    <th><h3>Phone</h3></th>
                    <th><h3>Email</h3></th>
                    <th><h3>Birthdate</h3></th>
                    <th><h3>Last Access</h3></th>
                    <th><h3>Rating</h3></th>
                    <th><h3>Done</h3></th>
                    <th><h3>Salary</h3></th>
                    <th><h3>Score</h3></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td class="name">Ezekiel Hart</td>
                    <td>(627) 536-4760</td>
                    <td><a href="#" class="button-email" title="tortor@est.ca">tortor@est.ca</a></td>
                    <td>12/02/1962</td>
                    <td>March 26, 2009</td>
                    <td>-7</td>
                    <td>7%</td>
                    <td>$73,229</td>
                    <td>6.9</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Jaquelyn Pace</td>
                    <td>(921) 943-5780</td>
                    <td><a href="#" class="button-email" title="in@elementum.org">in@elementum.org</a></td>
                    <td>06/03/1957</td>
                    <td>October 20, 2006</td>
                    <td>-7</td>
                    <td>33%</td>
                    <td>$130,752</td>
                    <td>4.9</td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>Lois Pickett</td>
                    <td>(835) 361-5993</td>
                    <td><a href="#" class="button-email" title="arcu.ac@disse.ca">arcu.ac@disse.ca</a></td>
                    <td>10/15/1983</td>
                    <td>June 01, 1999</td>
                    <td>4</td>
                    <td>44%</td>
                    <td>$48,684</td>
                    <td>5.5</td>
                </tr>
                <tr>
                    <td>4</td>
                    <td>Keane Raymond</td>
                    <td>(605) 803-1561</td>
                    <td><a href="#" class="button-email" title="at.Nunc@ipsum.com">at.Nunc@ipsum.com</a></td>
                    <td>07/30/1982</td>
                    <td>July 24, 1996</td>
                    <td>5</td>
                    <td>20%</td>
                    <td>$7,023</td>
                    <td>9.5</td>
                </tr>
                <tr>
                    <td>5</td>
                    <td>Porter Thomas</td>
                    <td>(666) 569-9894</td>
                    <td><a href="#" class="button-email" title="non@Proin.ca">non@Proin.ca</a></td>
                    <td>09/27/1986</td>
                    <td>December 05, 2007</td>
                    <td>1</td>
                    <td>66%</td>
                    <td>$69,875</td>
                    <td>0.9</td>
                </tr>
                <tr>
                    <td>6</td>
                    <td>Imani Murphy</td>
                    <td>(771) 294-6690</td>
                    <td><a href="#" class="button-email" title="Ae.sed@elit.ca">Ae.sed@elit.ca</a></td>
                    <td>10/23/1970</td>
                    <td>December 08, 1996</td>
                    <td>-1</td>
                    <td>30%</td>
                    <td>$113,763</td>
                    <td>4.9</td>
                </tr>
                <tr>
                    <td>7</td>
                    <td>Zachery Guthrie</td>
                    <td>(851) 784-4129</td>
                    <td><a href="#" class="button-email" title="nunulla@vel.com">nunulla@vel.com</a></td>
                    <td>12/22/1972</td>
                    <td>September 20, 2002</td>
                    <td>-5</td>
                    <td>24%</td>
                    <td>$130,248</td>
                    <td>6.9</td>
                </tr>
                <tr>
                    <td>8</td>
                    <td>Harper Bowen</td>
                    <td>(810) 652-6704</td>
                    <td><a href="#" class="button-email" title="dis@duinec.ca">dis@duinec.ca</a></td>
                    <td>10/26/1973</td>
                    <td>May 29, 1996</td>
                    <td>5</td>
                    <td>49%</td>
                    <td>$73,197</td>
                    <td>4.5</td>
                </tr>
                <tr>
                    <td>9</td>
                    <td>Caldwell Larson</td>
                    <td>(850) 562-3177</td>
                    <td><a href="#" class="button-email" title="elit@dolor.com">elit@dolor.com</a></td>
                    <td>07/20/1985</td>
                    <td>June 22, 2004</td>
                    <td>-3</td>
                    <td>81%</td>
                    <td>$63,736</td>
                    <td>7.5</td>
                </tr>
                <tr>
                    <td>10</td>
                    <td>Baker Osborn</td>
                    <td>(378) 371-0559</td>
                    <td><a href="#" class="button-email" title="turpis.Nulla@ac.edu">turpis.Nulla@ac.edu</a></td>
                    <td>03/29/1970</td>
                    <td>July 23, 2005</td>
                    <td>-7</td>
                    <td>61%</td>
                    <td>$2,868</td>
                    <td>0.1</td>
                </tr>
                <tr>
                    <td>11</td>
                    <td>Yael Owens</td>
                    <td>(465) 520-1801</td>
                    <td><a href="#" class="button-email" title="nunc.tis@enim.com">nunc.tis@enim.com</a></td>
                    <td>08/10/1963</td>
                    <td>April 09, 1997</td>
                    <td>10</td>
                    <td>85%</td>
                    <td>$126,469</td>
                    <td>8.9</td>
                </tr>
                <tr>
                    <td>12</td>
                    <td>Fletcher Briggs</td>
                    <td>(992) 962-9419</td>
                    <td><a href="#" class="button-email" title="amet.ante@lenque.edu">amet.ante@lenque.edu</a></td>
                    <td>08/12/1971</td>
                    <td>December 12, 2006</td>
                    <td>7</td>
                    <td>23%</td>
                    <td>$142,448</td>
                    <td>8.9</td>
                </tr>
                <tr>
                    <td>13</td>
                    <td>Maggy Murphy</td>
                    <td>(585) 210-0390</td>
                    <td><a href="#" class="button-email" title="eu@Integer.com">eu@Integer.com</a></td>
                    <td>07/11/1968</td>
                    <td>April 02, 2007</td>
                    <td>9</td>
                    <td>31%</td>
                    <td>$40,267</td>
                    <td>6.9</td>
                </tr>
                <tr>
                    <td>14</td>
                    <td>Maggie Blake</td>
                    <td>(489) 101-5447</td>
                    <td><a href="#" class="button-email" title="rutr.heeit@iacuis.org">rutr.heeit@iacuis.org</a></td>
                    <td>04/11/1970</td>
                    <td>May 24, 2008</td>
                    <td>-2</td>
                    <td>32%</td>
                    <td>$99,686</td>
                    <td>7.9</td>
                </tr>
                <tr>
                    <td>15</td>
                    <td>Ginger Bell</td>
                    <td>(934) 692-7294</td>
                    <td><a href="#" class="button-email" title="erat.in.tuer@pedut.org">erat.in.tuer@pedut.org</a></td>
                    <td>06/10/1957</td>
                    <td>April 13, 2003</td>
                    <td>-10</td>
                    <td>74%</td>
                    <td>$112,997</td>
                    <td>4.5</td>
                </tr>
                <tr>
                    <td>16</td>
                    <td>Iliana Ballard</td>
                    <td>(806) 835-7035</td>
                    <td><a href="#" class="button-email" title="vel.sapien@mi.ca">vel.sapien@mi.ca</a></td>
                    <td>02/09/1989</td>
                    <td>March 27, 1996</td>
                    <td>-6</td>
                    <td>78%</td>
                    <td>$5,282</td>
                    <td>5.5</td>
                </tr>
                <tr>
                    <td>17</td>
                    <td>Alisa Monroe</td>
                    <td>(859) 974-4442</td>
                    <td><a href="#" class="button-email" title="ading.lila@aram.edu">ading.lila@aram.edu</a></td>
                    <td>02/14/1990</td>
                    <td>April 30, 2003</td>
                    <td>6</td>
                    <td>95%</td>
                    <td>$103,999</td>
                    <td>5.9</td>
                </tr>
                <tr>
                    <td>18</td>
                    <td>Kenyon Luna</td>
                    <td>(673) 147-0443</td>
                    <td><a href="#" class="button-email" title="Cras@Vestant.edu">Cras@Vestant.edu</a></td>
                    <td>04/14/1981</td>
                    <td>April 17, 2009</td>
                    <td>9</td>
                    <td>14%</td>
                    <td>$37,014</td>
                    <td>7.9</td>
                </tr>
                <tr>
                    <td>19</td>
                    <td>Nola Kerr</td>
                    <td>(340) 430-0424</td>
                    <td><a href="#" class="button-email" title="tincnt@vurmagna.com">tincnt@vurmagna.com</a></td>
                    <td>11/06/1959</td>
                    <td>August 14, 2000</td>
                    <td>10</td>
                    <td>2%</td>
                    <td>$104,149</td>
                    <td>0.5</td>
                </tr>
                <tr>
                    <td>20</td>
                    <td>Gail Cash</td>
                    <td>(291) 455-8520</td>
                    <td><a href="#" class="button-email" title="massa.rutum@Numlob.ca">massa.rutum@Numlob.ca</a></td>
                    <td>09/09/1970</td>
                    <td>April 27, 2002</td>
                    <td>-1</td>
                    <td>84%</td>
                    <td>$31,090</td>
                    <td>1.5</td>
                </tr>
                <tr>
                    <td>21</td>
                    <td>Kalia Ayala</td>
                    <td>(142) 520-1128</td>
                    <td><a href="#" class="button-email" title="Etiam.laet@velit.org">Etiam.laet@velit.org</a></td>
                    <td>04/25/1971</td>
                    <td>March 30, 2001</td>
                    <td>5</td>
                    <td>30%</td>
                    <td>$44,641</td>
                    <td>5.9</td>
                </tr>
                <tr>
                    <td>22</td>
                    <td>Violet Ballard</td>
                    <td>(126) 374-6078</td>
                    <td><a href="#" class="button-email" title="Iner.gna@ntulis.edu">Iner.gna@ntulis.edu</a></td>
                    <td>01/23/1984</td>
                    <td>June 08, 2006</td>
                    <td>-10</td>
                    <td>85%</td>
                    <td>$46,450</td>
                    <td>7.9</td>
                </tr>
                <tr>
                    <td>23</td>
                    <td>Kevin Carrillo</td>
                    <td>(687) 483-9669</td>
                    <td><a href="#" class="button-email" title="in@adiscing.edu">in@adiscing.edu</a></td>
                    <td>03/17/1977</td>
                    <td>October 01, 2005</td>
                    <td>-3</td>
                    <td>5%</td>
                    <td>$51,754</td>
                    <td>1.5</td>
                </tr>
                <tr>
                    <td>24</td>
                    <td>Alexandra Nixon</td>
                    <td>(422) 644-3488</td>
                    <td><a href="#" class="button-email" title="nec.luctu@oriis.com">nec.luctu@oriis.com</a></td>
                    <td>12/01/1981</td>
                    <td>February 25, 2001</td>
                    <td>-1</td>
                    <td>41%</td>
                    <td>$46,672</td>
                    <td>7.5</td>
                </tr>
                <tr>
                    <td>25</td>
                    <td class="name" title="Charissa Manning">Charissa Manning</td>
                    <td>(438) 395-9392</td>
                    <td><a href="#" class="button-email" title="nib.vuate@necon.org">nib.vuate@necon.org</a></td>
                    <td>07/01/1980</td>
                    <td>April 02, 2005</td>
                    <td>-8</td>
                    <td>11%</td>
                    <td>$32,193</td>
                    <td>3.5</td>
                </tr>
                <tr>
                    <td>26</td>
                    <td>Noah Smith</td>
                    <td>(963) 652-2643</td>
                    <td><a href="#" class="button-email" title="Sed.neque@Duis.org">Sed.neque@Duis.org</a></td>
                    <td>01/19/1986</td>
                    <td>April 03, 2005</td>
                    <td>-5</td>
                    <td>86%</td>
                    <td>$96,995</td>
                    <td>3.5</td>
                </tr>
                <tr>
                    <td>27</td>
                    <td>Patience Battle</td>
                    <td>(294) 644-5306</td>
                    <td><a href="#" class="button-email" title="tempus.mau@eurus.com">tempus.mau@eurus.com</a></td>
                    <td>09/16/1988</td>
                    <td>October 19, 2003</td>
                    <td>7</td>
                    <td>62%</td>
                    <td>$47,510</td>
                    <td>4.5</td>
                </tr>
                <tr>
                    <td>28</td>
                    <td>Kathleen Hudson</td>
                    <td>(190) 189-1420</td>
                    <td><a href="#" class="button-email" title="orci.quis@auctor.com">orci.quis@auctor.com</a></td> 
                    <td>08/03/1963</td>
                    <td>January 03, 2004</td>
                    <td>8</td>
                    <td>27%</td>
                    <td>$36,286</td>
                    <td>6.9</td>
                </tr>
                <tr>
                    <td>29</td>
                    <td>Marsden Bowman</td>
                    <td>(163) 780-6121</td>
                    <td><a href="#" class="button-email" title="maus.a@Sedit.edu">maus.a@Sedit.edu</a></td>
                    <td>03/08/1974</td>
                    <td>June 29, 2005</td>
                    <td>10</td>
                    <td>46%</td>
                    <td>$124,913</td>
                    <td>1.5</td>
                </tr>
                <tr>
                    <td>30</td>
                    <td>Dorian Hodge</td>
                    <td>(304) 536-8850</td>
                    <td><a href="#" class="button-email" title="pelque@laoreet.org">pelque@laoreet.org</a></td>
                    <td>08/16/1978</td>
                    <td>February 21, 2007</td>
                    <td>6</td>
                    <td>6%</td>
                    <td>$28,057</td>
                    <td>0.1</td>
                </tr>
                <tr>
                    <td>31</td>
                    <td>Levi Britt</td>
                    <td>(272) 171-5731</td>
                    <td><a href="#" class="button-email" title="felis@Dogiat.ca">felis@Dogiat.ca</a></td> 
                    <td>12/10/1988</td>
                    <td>August 11, 2008</td>
                    <td>7</td>
                    <td>6%</td>
                    <td>$100,959</td>
                    <td>1.5</td>
                </tr>
                <tr>
                    <td>32</td>
                    <td>Rana Blake</td>
                    <td>(608) 893-4909</td>
                    <td><a href="#" class="button-email" title="mala.fames@dui.edu">mala.fames@dui.edu</a></td>
                    <td>07/23/1959</td>
                    <td>February 13, 1997</td>
                    <td>-4</td>
                    <td>26%</td>
                    <td>$87,972</td>
                    <td>4.9</td>
                </tr>
                <tr>
                    <td>33</td>
                    <td>Helen Mccullough</td>
                    <td>(937) 368-5946</td>
                    <td><a href="#" class="button-email" title="socis.nue@mysum.org">socis.nue@mysum.org</a></td>
                    <td>09/13/1980</td>
                    <td>May 17, 2001</td>
                    <td>8</td>
                    <td>18%</td>
                    <td>$51,217</td>
                    <td>9.9</td>
                </tr>
                <tr>
                    <td>34</td>
                    <td>Gil Ferguson</td>
                    <td>(832) 581-6953</td>
                    <td><a href="#" class="button-email" title="libero@Infbus.com">libero@Infbus.com</a></td> 
                    <td>04/19/1980</td>
                    <td>March 22, 1996</td>
                    <td>-6</td>
                    <td>83%</td>
                    <td>$120,374</td>
                    <td>6.9</td>
                </tr>
                <tr>
                    <td>35</td>
                    <td>Judah Manning</td>
                    <td>(332) 888-8768</td>
                    <td><a href="#" class="button-email" title="congue@Nuncut.com">congue@Nuncut.com</a></td>
                    <td>07/16/1974</td>
                    <td>December 14, 2009</td>
                    <td>9</td>
                    <td>34%</td>
                    <td>$95,173</td>
                    <td>6.9</td>
                </tr>
                <tr>
                    <td>36</td>
                    <td>Yoshi Humphrey</td>
                    <td>(961) 215-0426</td>
                    <td><a href="#" class="button-email" title="phatra@rutrce.edu">phatra@rutrce.edu</a></td>
                    <td>01/13/1962</td>
                    <td>August 24, 1997</td>
                    <td>4</td>
                    <td>84%</td>
                    <td>$140,552</td>
                    <td>3.9</td>
                </tr>
                <tr>
                    <td>37</td>
                    <td>Idona Williams</td>
                    <td>(163) 580-2609</td>
                    <td><a href="#" class="button-email" title="Intr.auam@Seero.org">Intr.auam@Seero.org</a></td>
                    <td>08/27/1967</td>
                    <td>February 22, 2000</td>
                    <td>-3</td>
                    <td>15%</td>
                    <td>$37,762</td>
                    <td>5.9</td>
                </tr>
                <tr>
                    <td>38</td>
                    <td>Petra Bennett</td>
                    <td>(976) 799-4111</td>
                    <td><a href="#" class="button-email" title="Proin@Doentum.org">Proin@Doentum.org</a></td>
                    <td>01/02/1959</td>
                    <td>April 27, 1999</td>
                    <td>2</td>
                    <td>81%</td>
                    <td>$32,371</td>
                    <td>6.5</td>
                </tr>
                <tr>
                    <td>39</td>
                    <td>Phyllis Rogers</td>
                    <td>(798) 959-3321</td>
                    <td><a href="#" class="button-email" title="eget.dtt@iibero.ca">eget.dtt@iibero.ca</a></td>
                    <td>11/27/1961</td>
                    <td>February 14, 2009</td>
                    <td>-10</td>
                    <td>42%</td>
                    <td>$77,847</td>
                    <td>4.9</td>
                </tr>
                <tr>
                    <td>40</td>
                    <td>Fritz Benton</td>
                    <td>(525) 353-2984</td>
                    <td><a href="#" class="button-email" title="a@diamnunc.com">a@diamnunc.com</a></td>
                    <td>10/02/1957</td>
                    <td>June 16, 2002</td>
                    <td>-5</td>
                    <td>2%</td>
                    <td>$75,654</td>
                    <td>8.9</td>
                </tr>
                <tr>
                    <td>41</td>
                    <td>Mannix Davidson</td>
                    <td>(106) 260-1651</td>
                    <td><a href="#" class="button-email" title="punar@Duisvt.org">punar@Duisvt.org</a></td>
                    <td>08/24/1964</td>
                    <td>October 24, 2002</td>
                    <td>1</td>
                    <td>93%</td>
                    <td>$11,498</td>
                    <td>2.5</td>
                </tr>
                <tr>
                    <td>42</td>
                    <td>Grant Lawrence</td>
                    <td>(807) 487-5786</td>
                    <td><a href="#" class="button-email" title="a@interdbero.ca">a@interdbero.ca</a></td>
                    <td>10/10/1973</td>
                    <td>March 28, 2007</td>
                    <td>9</td>
                    <td>6%</td>
                    <td>$137,743</td>
                    <td>3.9</td>
                </tr>
                <tr>
                    <td>43</td>
                    <td>Laurel Ortiz</td>
                    <td>(945) 481-7808</td>
                    <td><a href="#" class="button-email" title="laot.puere@vais.com">laot.puere@vais.com</a></td>
                    <td>08/19/1962</td>
                    <td>May 11, 2006</td>
                    <td>5</td>
                    <td>55%</td>
                    <td>$64,799</td>
                    <td>8.9</td>
                </tr>
                <tr>
                    <td>44</td>
                    <td>Lewis Frank</td>
                    <td>(844) 314-8683</td>
                    <td><a href="#" class="button-email" title="fames@graida.edu">fames@graida.edu</a></td>
                    <td>12/01/1966</td>
                    <td>January 25, 1998</td>
                    <td>5</td>
                    <td>30%</td>
                    <td>$99,927</td>
                    <td>1.5</td>
                </tr>
                <tr>
                    <td>45</td>
                    <td>Yasir Knox</td>
                    <td>(814) 509-0367</td>
                    <td><a href="#" class="button-email" title="Cras.ve.velit@asUt.com">Cras.ve.vit@asUt.com</a></td>
                    <td>10/23/1981</td>
                    <td>August 20, 2007</td>
                    <td>-2</td>
                    <td>61%</td>
                    <td>$36,618</td>
                    <td>0.9</td>
                </tr>
                <tr>
                    <td>46</td>
                    <td>Palmer Newman</td>
                    <td>(955) 748-1014</td>
                    <td><a href="#" class="button-email" title="fringilla@id.edu">fringilla@id.edu</a></td>
                    <td>10/29/1980</td>
                    <td>December 28, 2007</td>
                    <td>-9</td>
                    <td>85%</td>
                    <td>$19,325</td>
                    <td>2.5</td>
                </tr>
                <tr>
                    <td>47</td>
                    <td>Tate Webster</td>
                    <td>(107) 247-3380</td>
                    <td><a href="#" class="button-email" title="pelleue@pedeultri.com">pelleue@pedeultri.com</a></td>
                    <td>06/11/1963</td>
                    <td>August 11, 2005</td>
                    <td>-4</td>
                    <td>14%</td>
                    <td>$109,433</td>
                    <td>6.5</td>
                </tr>
                <tr>
                    <td>48</td>
                    <td>Charity Hahn</td>
                    <td>(395) 200-9188</td>
                    <td><a href="#" class="button-email" title="ac@Quisque.edu">ac@Quisque.edu</a></td>
                    <td>08/04/1976</td>
                    <td>January 17, 2009</td>
                    <td>-2</td>
                    <td>86%</td>
                    <td>$3,246</td>
                    <td>5.5</td>
                </tr>
                <tr>
                    <td>49</td>
                    <td>Katell Crosby</td>
                    <td>(259) 659-7498</td>
                    <td><a href="#" class="button-email" title="tinunt.vla@ura.com">tinunt.vla@ura.com</a></td>
                    <td>12/31/1961</td>
                    <td>January 02, 2007</td>
                    <td>8</td>
                    <td>10%</td>
                    <td>$67,319</td>
                    <td>8.9</td>
                </tr>
            </tbody>
        </table>
        <div id="tablefooter">
          <div id="tablenav">
            	<div>
                    <img src="images/first.png" width="16" height="16" alt="First Page" onclick="sorter.move(-1,true)" />
                    <img src="images/previous.png" width="16" height="16" alt="First Page" onclick="sorter.move(-1)" />
                    <img src="images/next.png" width="16" height="16" alt="First Page" onclick="sorter.move(1)" />
                    <img src="images/last.png" width="16" height="16" alt="Last Page" onclick="sorter.move(1,true)" />
                </div>
                <div>
                	<select  id="pagedropdown"></select>
				</div>
                <div class="btn-reset"><a class="button blue" href="javascript:sorter.showall()">view all</a>
                </div>
            </div>
			<div id="tablelocation">
            <div>
                    <select onchange="sorter.size(this.value)">
                    <option value="5">5</option>
                        <option value="10" selected="selected">10</option>
                        <option value="20">20</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <span class="txt-page">Entries Per Page</span>
                </div>

            	
                <div class="page txt-txt">Page <span id="currentpage"></span> of <span id="totalpages"></span></div>
            </div>
        </div>
    </div>
</div>

  <div id="modal" hidden>
	<div id="heading" class="heading-color">
		For more info send me an email
	</div>

	<div id="content">
        <div class="txt-subject">
        <p style="margin-left:10px;">Subject: </p></div> 
        <div class="content-subject">
        <input type="text"/></div>
		<div class="txt-email">
        <p style="margin-left:10px;">Email: </p></div> 
        <div class="content-email">
        <p id="email" style=" color:#464747; font:12px;"></p></div>
        <div class="txt-message"><p>Message: </p></div> 
        <div class="content-message">
        <textarea style="width:100%;background-color:#f7fbfe; margin-left:10px; height:100px;"></textarea></div>
        <div class="contact-img"><img src="images/email.png" class="img-contact" alt=""/></div>

		<div style="margin: 0 0 0 10px;"><a href="#" class="button blue position">Send</a></div>
	</div>
</div>-->
<!---End Pannel Contact-->

<script type="text/javascript">
	/*var sorter = new TINY.table.sorter('sorter','table',{
	    headclass: 'head', // Header Class //
	    ascclass: 'asc', // Ascending Class //
	    descclass: 'desc', // Descending Class //
	    evenclass: 'evenrow', // Even Row Class //
	    oddclass: 'oddrow', // Odd Row Class //
	    evenselclass: 'evenselected', // Even Selected Column Class //
	    oddselclass: 'oddselected', // Odd Selected Column Class //
	    paginate: true, // Paginate? (true or false) //
	    size: 10, // Initial Page Size //
	    colddid: 'columns', // Columns Dropdown ID (optional) //
	    currentid: 'currentpage', // Current Page ID (optional) //
	    totalid: 'totalpages', // Current Page ID (optional) //
	    startingrecid: 'startrecord', // Starting Record ID (optional) //
	    endingrecid: 'endrecord', // Ending Record ID (optional) //
	    totalrecid: 'totalrecords', // Total Records ID (optional) //
	    hoverid: 'selectedrow', // Hover Row ID (optional) //
	    pageddid: 'pagedropdown', // Page Dropdown ID (optional) //
	    navid: 'tablenav', // Table Navigation ID (optional) //
	    sortcolumn: 1, // Index of Initial Column to Sort (optional) //
	    sortdir: 1, // Sort Direction (1 or -1) //
	    sum: [8], // Index of Columns to Sum (optional) //
	    avg: [6, 7, 8, 9], // Index of Columns to Average (optional) //
	    columns: [{ index: 7, format: '%', decimals: 1 }, { index: 8, format: '$', decimals: 0}], // Sorted Column Settings (optional) //
	    init: true// Init Now? (true or false) //

	});*/
  </script>
  
