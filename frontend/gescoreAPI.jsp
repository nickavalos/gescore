//<!-- Set the vars that api.js will need -->
/**
 * This file is part of the project Gestor de Congresos y Revistas en Joomla (GesCORE).
 *
 * @package		GesCORE frontend 
 * @subpackage  - 
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
var gescoreURL = "http://gescore.byethost11.com/";
//var gescoreURL = "http://localhost/gestorRevistasCongresos16/";
var gescoreURL_path = "index.php?option=com_gestorcore&controller=api&task=search&format=raw";

var divTag_debug = 'gescoreDebugArea';
var renderLocation = 'gescore_output';
var gescoreTimeout = "40000"; 

var debug = new gescoreDebug();
var gescore = new gescoreInterface();


function gescoreInterface() {
    this._resultsValid = false;
    this._results = null;
    this._errors = null;

    this._search = null;

    this._callback = null;
    this._errorCallback = null;

    this.Backend = new gescoreBackend();
    this.Renderer = new gescoreListRenderer();
    
    this._categoryCongress      = 'Congreso';
    this._categoryJournal       = 'Revista';
    this._categoryEdition       = 'Edición';
    this._categoryIssue         = 'Issue';
    this._categoryEditorial     = 'Editorial';
    this._categoryQualityEntity = 'Entidad de Calidad';
    this._categoryTopic         = 'Tópico';
    
    this._administrator         = 'administrator/';
    this._uriCongress           = this._administrator + 'index.php?option=com_gestorcore&controller=congreso&task=infoCongreso&id=';
    this._uriJournal            = this._administrator + 'index.php?option=com_gestorcore&controller=revista&task=infoRevista&id=';
    this._uriEdition_order      = this._administrator + 'index.php?option=com_gestorcore&controller=congreso&task=infoEdicion&orden=';
    this._uriEdition_congress   = '&congreso=';
    this._uriIssue_volume       = this._administrator + 'index.php?option=com_gestorcore&controller=revista&task=infoIssue&volumen=';
    this._uriIssue_number       = '&numero=';
    this._uriIssue_journal      = '&revista=';
    this._uriQualityEntity      = this._administrator + 'index.php?option=com_gestorcore&view=infoEntidadCalidad&id=';
    this._uriTopic              = this._administrator + 'index.php?option=com_gestorcore&view=infoTopico&id=';
    
    this._querySeparator = '%%';
    
    // see here for explination of "me": 
    // http://w3future.com/html/stories/callbacks.xml
    var me = this;
    
    //set the callback to be called when a search is compleate
    this.setCallback = function(callback){
        if(callback !== null){
            this._callback = callback;
        }else{
            debug.write("setCallback: no callback specified");
        }
    };
    
    //search and it's callback
    this.search = function(searchObj){
        me._resultsValid = false;
        me._search = searchObj;
        me.Backend.submitSearch(searchObj, me.runSearchCallback);
    };
    
    this.runSearchCallback = function(responseObj){
        me._errors = null;
        me._results = null;
        me._resultsValid = false;
        
        if(responseObj == "timeout"){
            debug.write("runSearch: search timed out");
            if(me._errorCallback !== null){
                me._errorCallback();
            }
        } if(responseObj.ERROR != null){
            debug.write("search: error occured durring search");
            if(me._errorCallback !== null){
                me._errorCallback();
            }
            me._errors = responseObj.ERROR;
        } else if(responseObj.OK != null){
            debug.write("search: search results are OK");
            
            responseObj.OK.results = me.prepareResults(responseObj.OK.results);

            me._results = responseObj.OK;
            me._resultsValid = true;
        }
        if(me._callback != null) {
            me._callback();
        } else {
            debug.write("search:no callback specified");
        }
    };
    
    //search and it's callback
    this.searchAndPrint = function(searchObj){
        me._resultsValid = false;
        me._search = searchObj;
        me.Backend.submitSearch(searchObj, me.searchCallback_print);
    };
    
    this.searchCallback_print = function(responseObj){
        me._errors = null;
        me._results = null;
        me._resultsValid = false;
        
        if(responseObj == "timeout"){
            debug.write("runSearch: search timed out");
            if(me._errorCallback !== null){
                me._errorCallback();
            }
        } if(responseObj.ERROR != null){
            debug.write("search: error occured durring search");
            //me.Renderer.renderErrors(responseObj.ERROR.Errors);
            me.Renderer.renderErrors(responseObj.ERROR);
            if(me._errorCallback !== null){
                me._errorCallback();
            }
            me._errors = responseObj.ERROR;
        } else if(responseObj.OK != null){
            debug.write("search: search results are OK");
            
            responseObj.OK.results = me.prepareResults(responseObj.OK.results);
            me.Renderer.renderResults(responseObj.OK.results);

            me._results = responseObj.OK;
            me._resultsValid = true;
        }
        if(me._callback != null) {
            me._callback();
        } else {
            debug.write("search:no callback specified");
        }
    };
    
    this.prepareResults = function(results) {
        for (var i = 0; i < results.length; i++) {
            var camps = results[i].title.split(this._querySeparator);
            switch(results[i].categoria) {
                case this._categoryCongress:
                    results[i].uriGescore = gescoreURL + this._uriCongress + results[i].id;
                    break;
                case this._categoryJournal:
                    results[i].uriGescore = gescoreURL + this._uriJournal + results[i].id;
                    break;
                case this._categoryEdition:
                    results[i].congress = camps[1];
                    results[i].orderEdition = camps[0];
                    if (camps[2] != null) {
                        results[i].appearsInTopic = camps[2];
                    }
                    delete results[i].title;
                    
                    //uri Gescore
                    var ids = results[i].id.split(this._querySeparator);
                    results[i].uriGescore = gescoreURL + this._uriEdition_order + ids[0] + this._uriEdition_congress + ids[1];
                    break;
                case this._categoryIssue:
                    results[i].journal = camps[2];
                    results[i].issue = camps[1];
                    results[i].volume = camps[0];
                    if (camps[3] != null) {
                        results[i].appearsInTopic = camps[3];
                    }
                    delete results[i].title;
                    
                    //uri Gescore
                    var ids = results[i].id.split(this._querySeparator);
                    results[i].uriGescore = gescoreURL + this._uriIssue_volume + ids[0] + this._uriIssue_number + ids[1] + this._uriIssue_journal + ids[2];
                    break;
                case this._categoryQualityEntity:
                    results[i].uriGescore = gescoreURL + this._uriQualityEntity + results[i].id;
                    break;
                case this._categoryTopic:
                    results[i].uriGescore = gescoreURL + this._uriTopic + results[i].id;
                    break;
                default:
                    break;
            }
        }
        return results;
    };

    this.areSearchResultsValid = function(){
        return this._resultsValid;
    };
    
    this.getSearchResults = function(){
        if(this._resultsValid) {
            return this._results;
        }else{
            debug.write("getSearchResults:results are not currently valid");
        }
    };

    //Renderer passthrough functions
    this.renderDocument = function(position){
        this.Renderer.renderDocument(this._results, position);
    };
    this.renderField = function(position, field){
        this.Renderer.renderField(this._results, position, field);
    };
    this.getField = function(position, field){
        return this._results.results[position][field];
    };
    this.getTotalHits = function(){
        return this._results.totalResults;
    };
    this.getNumResults = function(){
        return this._results.returnedResults;
    };
    this.getPosition = function(){
        return this._results.position;
    };

    //get the last search request: Obj
    this.getLastSearchRequest = function(){
        return this._search;
    };

    //Debug option setters
    this.setDebug = function(value){
        debug.setDebug(value);
    };
    this.getDebug = function(){
        return debug.getDebug();
    };
    
    //set the renderer (overiding the previous or default renderer)
    this.setRenderer = function(newRenderer){
        if (newRenderer != null){
            this.Renderer = newRenderer;
        }else{
            debug.write("setRenderer:no renderer specified");
        }
    };
    
    this.setErrorCallback = function(callback){
        if(callback != null){
            this._errorCallback = callback;
        }else{
            debug.write("setErrorCallback:no callback specified");
        }
    };

}

//The debug class
function gescoreDebug(){
    this._debug = false;
    this._divTag = divTag_debug;

    this.setDebug = function(value){
        this._debug = value;
    };

    this.getDebug = function(){
        return this._debug;
    };

    this.setDebugDivTag = function(value) {
        this._divTag = value;
    };

    //if debuging is enabled writes the message to the end of the specified div tag
    this.write = function(message){
        if(this.getDebug()) {
            var newElement = document.createElement("p");
            var newtext = document.createTextNode(message);
            newElement.appendChild(newtext);
            document.getElementById(this._divTag).appendChild(newElement);
        }
    };
}

//This class handles parsing the response from the server and rendering it into 
//html which is then inserted into the html page.
function gescoreListRenderer(){
    //the id of the div tag to replace the contents of
    this._renderLocation = renderLocation;
    //this._sciverseURL = sciverseURL;

    this._contentProvidedByString = "<strong> Content Provided by gesCORE</strong>";

    this._resultsTableStart     = "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\" class=\"txt\">";
    this._resultsTableEnd 	= "<tr><td>&nbsp;</td><td>"+this._contentProvidedByString +"</tr></table>";

    this._errorTableStart       = "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\" class=\"txt\">";
    this._errorTableEnd 	= "</table>";

    this._errorRowStart1	= "<td valign=\"top\" class=\"";
    this._errorRowStart2	= "\">&nbsp;</td><td valign=\"top\" class=\"";
    this._errorRowStart3        = "\" class=\"txt\">";

    this._documentRowStart1	= "<td valign=\"top\" width=\"11\" class=\"";
    //this._documentRowStart2	= "\"><img src=\""+this._sciverseURL+"images/bullet.gif\" width=\"10\" height=\"12\" /></td><td valign=\"top\" class=\"";
    this._documentRowStart2	= "\">*</td><td valign=\"top\" class=\"";
    this._documentRowStart3     = "\" class=\"txt\">";
    this._documentRowEnd	= "</td></tr>";

    this._white                 ="whitebg";
    this._grey			="greybg";

    this._titleStart		= "<strong>";
    this._titleEnd              = "</strong>";

    this._errorStart            = "<strong class=\"txtRedBold\">";
    this._errorEnd              = "</strong>";
    
    /**/
    this._title                 = "title";
    
    this._congress              ="congress";
    this._congressName          ="Congress";
    
    this._orderEdition          ="orderEdition";
    this._orderEditionName      ="Order edition";
    
    this._journal               ="journal";
    this._journalName           ="Journal";
    
    this._vol                   ="volume";
    this._volName               ="Volume";
    
    this._issue                 ="issue";
    this._issueName             ="Issue";
    
    this._appears               ="appearsInTopic";
    this._appearsName           ="Appears in (topic)";

    this._acronim               ="acronimo";
    this._acronimName           ="Acronim";

    this._category              ="categoria";
    this._categoryName          ="Category";

    this._date                  ="fecha";
    this._dateName              ="Publication date";

    this._place                 ="lugar";
    this._placeName             ="Place of edition";

    this._link                  ="linkEntidad";
    this._linkName              ="Public Link";
    
    this._uriGescore            ="uriGescore";
    /**/
    
    this._publicLinkStart       ="<a class=\"noDeco\" href=\"";
    this._publicLinkEnd         ="\">View public link </a>";
    
    this._gescoreUriStart       ="<a class=\"noDeco\" href=\"";
    this._gescoreUriEnd         ="\">View in gesCORE backend </a>";

    this._fieldSeparator        =": ";
    this._fieldStart            ="";
    this._fieldEnd              ="<br />";
    this._querySeparator        ="%%";

    this._noResults             ="<strong class=\"txtRedBold\">No results were found for this search</strong>";
    
    var orderedFields           = [this._title, this._congress, this._orderEdition, this._journal, this._vol, this._issue, this._appears, 
                                   this._acronim, this._category, this._date, this._place, this._link, this._uriGescore];

    //sets a new render location overriding the previous one
    this.setRenderLocation = function(idName){
        if(idName != null && idName != ""){
            this._renderLocation = idName;
        }else{
            debug.write("gescoreRenderer.setRenderLocation: no render location specified");
        }
    };

    //render any errors 
    this.renderErrors = function(errors){
        var output = this._resultsTableStart;
        var hilight = false;

        for (var i = 0; i < errors.length; i++){
            if(hilight){
                output+= this._errorRowStart1+this._grey+this._errorRowStart2+this._grey+this._errorRowStart3;
            }else{
                output+= this._errorRowStart1+this._white+this._errorRowStart2+this._white+this._errorRowStart3;
            }

            //var error = errors[i].split("\n");
            output += this._errorStart+errors[i]+this._errorEnd+this._fieldEnd;

            output += this._documentRowEnd;
            hilight = !hilight;
        }
        output += this._resultsTableEnd;
        this.writeString(output);
    };

    //render any warnings
    this.renderWarnings = function(warnings){ //not used at this time
        //render warnings
        var output = "";
        output+= this.getAllRenderedWarningsString(warnings.Warnings);

        //renderresults
        output += this.getAllRenderedResultsString(warnings.PartOK.results);
        this.writeString(output);
    };

    this.getAllRenderedWarningsString = function(warnings){ //not used at this time
        for (i = 0; i < warnings.length; i++){
            alert(warnings[i]);
        }
        return "";
    };

    //render the response
    this.renderResults = function(response){
        var output = this._resultsTableStart;
        output += this.getAllRenderedResultsString(response);
        this.writeString(output);
    };

    this.getAllRenderedResultsString = function(results){
        var output = this._resultsTableStart;
        var hilight = false;
        if(results.length >0){
            for(var i = 0; i < results.length; i++){
                output += this.getRenderedResultString(results[i], hilight);
                hilight = !hilight;
            }
        }else{
            output += this._documentRowStart1 + this._white + this._documentRowStart2 + this._white + this._documentRowStart3;
            output += this._noResults;
            output += this._documentRowEnd;
        }
        output += this._resultsTableEnd;
        return output;
    };

    this.getRenderedResultString = function(result, hilight){
        var output = "";
        if(hilight){
            output += this._documentRowStart1 + this._grey + this._documentRowStart2 + this._grey + this._documentRowStart3;
        }else{
            output += this._documentRowStart1 + this._white + this._documentRowStart2 + this._white + this._documentRowStart3;
        }
        for (var i = 0; i <= orderedFields.length; i++){
            output += this.getRenderedFieldString(orderedFields[i], result[orderedFields[i]], result[this._category], hilight);
        }

        output += this._documentRowEnd;
        return output;
    };
    
    this.getRenderedFieldString = function(field, string, category, hilight){
        var output = "";
        if(typeof(string) == 'undefined' || string == null || string == "") {return "";}
        
        if(field === this._title) {
            output += this._titleStart + string + this._titleEnd;
        } else if( field === this._congress) {
            output += this._congressName + this._fieldSeparator + string;
        } else if( field === this._orderEdition) {
            output += this._orderEditionName + this._fieldSeparator + string;
        } else if( field === this._journal) {
            output += this._journalName + this._fieldSeparator + string;
        } else if( field === this._vol) {
            output += this._volName + this._fieldSeparator + string;
        } else if( field === this._issue) {
            output += this._issueName + this._fieldSeparator + string;
        } else if( field === this._appears) {
            output += this._appearsName + this._fieldSeparator + string;
        } else if( field === this._acronim) {
            output += this._acronimName + this._fieldSeparator + string;
        } else if( field === this._category) {
            output += this._categoryName + this._fieldSeparator + string;
        } else if( field === this._date) {
            output += this._dateName + this._fieldSeparator + string;
        } else if( field === this._place) {
            output += this._placeName + this._fieldSeparator + string;
        } else if( field === this._link) {
            //output += this._publicLinkStart + string + this._publicLinkEnd;
            output += this._linkName + this._fieldSeparator + string;
        } else if( field === this._uriGescore) {
            output += this._gescoreUriStart + string + this._gescoreUriEnd;
        }
        return output + this._fieldEnd;
    };

    this.writeString = function(output){
        document.getElementById(this._renderLocation).innerHTML = output;
    };
    
    this.renderDocument = function(response, position){
        var output = this._resultsTableStart;
        output += this.getRenderedResultString(response.results[position], false);
        output += this._resultsTableEnd;
        this.writeString(output);
    };

    this.renderField = function(response, position, field){
        var output = this.getRenderedFieldString(field, response.results[position][field]);
        this.writeString(output);
    };
}

//This class handles building and submitting the search request to the server as well as
//the callback response from the server.
function gescoreBackend(){
    this._busy = false;
    this._gescoreCallback = null;

    this._reqCounter = 0;
    this._requests = {};

    //Request static vars (should be config vars in a perfect world)
    this._gescoreURL = gescoreURL + gescoreURL_path;
    this._timeoutVal = gescoreTimeout;

    //request field names
    this._preventCache = "preventCache";
    this._search = "search";
    this._type = "type";
    this._sort = "sort";
    this._sortDirection = "sortDirection";
    this._numResults = "numResults";
    this._offset = "offset";
    this._searchInTopics = "searchInTopics";
    this._callback = "callback";
    
    //request field names of Edition
    this._place = "place";
    this._limReception_min = "limReception_min";
    this._limReception_max = "limReception_max";
    this._definitiveDate_min = "definitiveDate_min";
    this._definitiveDate_max = "definitiveDate_max";
    this._startDate_min = "startDate_min";
    this._startDate_max = "startDate_max";
    this._endDate_min = "endDate_min";
    this._endDate_max = "endDate_max";
    
    //request field names of Issue
    /*this._issue_limReception_min = "issue_limReception_min";
    this._issue_limReception_max = "issue_limReception_max";
    this._issue_definitiveDate_min = "issue_definitiveDate_min";
    this._issue_definitiveDate_max = "issue_definitiveDate_max";*/
    this._publicationDate_min = "publicationDate_min";
    this._publicationDate_max = "publicationDate_max";

    //useful callback strings
    this._callbackStringStart = "gescore.Backend._requests.";
    this._callbackStringEnd = ".callback";

    //useful url chars
    this._equals = "=";
    this._connector = "&";
    this._post = "?";

    //functions calling this method need to provide a callback method which is
    //in the form function(response).  This is due to the fact that this is an 
    //asyncronus library so when control is returned, processing is not done.
    //When the search is complete submitSearch will call the callback function.
    this.submitSearch = function(searchObj, callback){
        if(this._busy){
                debug.write("Search already in flight...ignoring search request");
                return;
        }else {
            //set busy to true
            this._busy = true;

            debug.write("Search submitted: query="+searchObj.getSearch());
            this._gescoreCallback = callback;

            var searchRequestURL = this._gescoreURL;

            //add in browser cacheing prevention.  This is used to change the generated URL
            //slightly each time to prevent the browser from caching a response to identical searches.
            //That's bad because if it happens the browser won't "run" the response.
            searchRequestURL = this.appendVarToURL(searchRequestURL, this._preventCache, this.randomString());

            //add in the search
            searchRequestURL = this.appendVarToURL(searchRequestURL, this._search, searchObj.getSearch());
            
            //add in the type
            searchRequestURL = this.appendVarToURL(searchRequestURL, this._type, searchObj.getType());

            //add in the sort
            searchRequestURL = this.appendVarToURL(searchRequestURL, this._sort, searchObj.getSort());

            //add in the sortDirection
            searchRequestURL = this.appendVarToURL(searchRequestURL, this._sortDirection, searchObj.getSortDirection());

            //add in the numResults
            searchRequestURL = this.appendVarToURL(searchRequestURL, this._numResults, searchObj.getNumResults());

            //add in the offset
            searchRequestURL = this.appendVarToURL(searchRequestURL, this._offset, searchObj.getOffset());
            
            //add in the searchInTopics
            searchRequestURL = this.appendVarToURL(searchRequestURL, this._searchInTopics, searchObj.getSearchInTopics().toString());
            
            //building url for object type
            if (searchObj.getObjectType() != null) {
                var obj = searchObj.getObjectType();
                switch (searchObj.getType()) {
                    case 'edition':
                        //Place
                        searchRequestURL = this.appendVarToURL(searchRequestURL, this._place, obj.getPlace());
                        //limReception
                        searchRequestURL = this.appendVarToURL(searchRequestURL, this._limReception_min, obj.getLimReception_min());
                        searchRequestURL = this.appendVarToURL(searchRequestURL, this._limReception_max, obj.getLimReception_max());
                        //Definitive Date
                        searchRequestURL = this.appendVarToURL(searchRequestURL, this._definitiveDate_min, obj.getDefinitiveDate_min());
                        searchRequestURL = this.appendVarToURL(searchRequestURL, this._definitiveDate_max, obj.getDefinitiveDate_max());
                        //Start Date
                        searchRequestURL = this.appendVarToURL(searchRequestURL, this._startDate_min, obj.getStartDate_min());
                        searchRequestURL = this.appendVarToURL(searchRequestURL, this._startDate_max, obj.getStartDate_max());
                        //End Date
                        searchRequestURL = this.appendVarToURL(searchRequestURL, this._endDate_min, obj.getEndDate_min());
                        searchRequestURL = this.appendVarToURL(searchRequestURL, this._endDate_max, obj.getEndDate_max());
                        break;
                    case 'issue':
                        searchRequestURL = this.appendVarToURL(searchRequestURL, this._limReception_min, obj.getLimReception_min());
                        searchRequestURL = this.appendVarToURL(searchRequestURL, this._limReception_max, obj.getLimReception_max());
                        //Definitive Date
                        searchRequestURL = this.appendVarToURL(searchRequestURL, this._definitiveDate_min, obj.getDefinitiveDate_min());
                        searchRequestURL = this.appendVarToURL(searchRequestURL, this._definitiveDate_max, obj.getDefinitiveDate_max());
                        //Start Date
                        searchRequestURL = this.appendVarToURL(searchRequestURL, this._publicationDate_min, obj.getPublicationDate_min());
                        searchRequestURL = this.appendVarToURL(searchRequestURL, this._publicationDate_max, obj.getPublicationDate_max());
                        break;
                }
            }
            //build the callback string
            var searchID = "search" + this._reqCounter++;
            var searchObjectString = this._callbackStringStart + searchID;
            var callbackString = this._callbackStringStart + searchID + this._callbackStringEnd;
            //add in callback
            searchRequestURL = this.appendVarToURL(searchRequestURL,this._callback,callbackString);

            //create a new inflight object
            var flight = new inflight(searchID);
            //and add it to the requests array
            this._requests[searchID] = flight;
            
            //write requets url in mode debug
            debug.write(searchRequestURL);
            
            //make the async call
            this.addRequestToHeader(searchRequestURL);
            //setup a timeout in case bad things happen
            setTimeout('gescore.Backend.timeout('+searchObjectString+')', this._timeoutVal);
        }
    };

    //if the value is not null this appends a field and value to an URL 
    //and returns it.  Otherwise it just returns the URL
    this.appendVarToURL = function(url, field, value){
        if(value != null && value != ""){
            url += this._connector + field + this._equals + value;
            return url;
        }else{
            return url;
        }
    };

    //Adds an URL to the header of an HTML document in a javascript tag
    //The purpose here is to cause the browser to load the new URL asyncronusly
    this.addRequestToHeader = function(searchURL){
        var head = document.getElementsByTagName("head")[0];
        var script = document.createElement('script');
        script.id = 'gescoreSearch';
        script.type = 'text/javascript';
        script.src = searchURL;
        head.appendChild(script);
    };

    //Creates a random string that is 20 chars long.
    this.randomString = function() {
        var chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz";
        var string_length = 20;
        var randomstring = '';
        for (var i=0; i<string_length; i++) {
            var rnum = Math.floor(Math.random() * chars.length);
            randomstring += chars.substring(rnum,rnum+1);
        }
        return randomstring;
    };

    //This method is called by the inflight object when the response is returned.  At
    //that time it will handle reseting the state of the backend to idle, and the state 
    //of the request to complete, and call the callback function.
    this.callback = function(search, response){
        debug.write("Results recieved by callback...");
        if(!search.inflight) {
            debug.write("...Javascript just recieved a response for a search that had timed out.");
        } else{
            search.inflight = false;
            debug.write("...looking good");

            this._busy = false;
            this._gescoreCallback(response);
        }
    };

    //This method is called when the timer for a search runs down.  If the search is still active
    //at that time it resets the state of the backend to idle, marks the request as complete(timedout)
    this.timeout = function(search){
        debug.write("Checking for timedout search...");
        if(search.inflight){
            debug.write("...Search was timed out");
            search.inflight = false;
            this._busy = false;
            this._gescoreCallback("timeout");
        }else{
            debug.write("...search had already compleated");
        }
        //timeout code
    };
}

//This class represents any request that has been submitted to the server
//and contains an id, a status, and a callback function that is called by the 
//response from the server
function inflight(id) {
    this.searchID = id;
    this.inflight = true;
    this.callback = function(response) {
        gescore.Backend.callback(this, response);
    };
}

//This class is effectivly a bean, and represents all of the fields that a 
//user can specify in a search 
function searchObj(){
    /*Text to search*/
    this._search = null;
    
    /*type allowed: 'editorial', 'congress', 'edition', 'journal', 'issue', 'qualityEntity', 'topic', '*'     */
    this._type = null;
    
    /*editionObject or issueObject*/
    this._objectType = null;
    
    /*Sort allowed: title, acronimo, categoria, fecha, lugar*/
    this._sort = null;
    
    /*Sort direction allowed: ASC, DESC*/
    this._sortDirection = null;
    
    /*numResults <= 50, default: 50*/
    this._numResults = null;
    
    /*offset of the results*/
    this._offset = null;
    
    /*valids: true or false*/
    this._searchInTopics = null;

    //search
    this.setSearch = function(search){
        this._search = search;
    };
    this.getSearch = function(){
        return this._search;
    };
    
    //type
    this.setType = function(type) {
        this._type = type;
    };
    this.getType = function() {
        return this._type;
    };
    
    //objectType
    this.setObjectType = function(objType) {
        switch(objType.constructor.name) {
            case 'editionObj':
                this._type = 'edition';
                break;
            case 'issueObj':
                this._type = 'issue';
                break;
            default:
                alert('Error: Object type does not match');
                break;
        }
        this._objectType = objType;
    };
    this.getObjectType = function() {
        return this._objectType;
    };

    //sort
    this.setSort = function(sort){
        this._sort = sort;
    };
    this.getSort = function(){
        return this._sort;
    };

    //sortDirection
    this.setSortDirection = function(sortDirection){
        this._sortDirection = sortDirection;
    };
    this.getSortDirection = function(){
        return this._sortDirection;
    };

    //numResults
    this.setNumResults = function(numResults){
        this._numResults = numResults;
    };
    this.getNumResults = function(){
        return this._numResults;
    };

    //offset
    this.setOffset = function(offset){
        this._offset = offset;
    };
    this.getOffset = function(){
        return this._offset;
    };
    
    //topics
    this.setSearchInTopics = function(value) {
        this._searchInTopics = value;
    };
    this.getSearchInTopics = function() {
        return this._searchInTopics;
    };
}

function editionObj() {
    this._place = null;
    
    /*Limit recepction: format: dd-mm-aaaa*/
    this._limReception_min = null;
    this._limReception_max = null;
    
    /*Definitive Date: format: dd-mm-aaaa*/
    this._definitiveDate_min = null;
    this._definitiveDate_max = null;
    
    /*Start Date: format: dd-mm-aaaa*/
    this._startDate_min = null;
    this._startDate_max = null;
    
    /*Fecha de Fin: format: dd-mm-aaaa*/
    this._endDate_min = null;
    this._endDate_max = null;
    
    //Place
    this.setPlace = function(place){
        this._place = place;
    };
    this.getPlace = function(){
        return this._place;
    };
    
    //Limit recepction MIN
    this.setLimReception_min = function(value){
        this._limReception_min = value;
    };
    this.getLimReception_min = function(){
        return this._limReception_min;
    };
    //Limit recepction MAX
    this.setLimReception_max = function(value){
        this._limReception_max = value;
    };
    this.getLimReception_max = function(){
        return this._limReception_max;
    };
    
    //Definitive Date MIN
    this.setDefinitiveDate_min = function(value){
        this._definitiveDate_min = value;
    };
    this.getDefinitiveDate_min = function(){
        return this._definitiveDate_min;
    };
    //Definitive Date MAX
    this.setDefinitiveDate_max = function(value){
        this._definitiveDate_max = value;
    };
    this.getDefinitiveDate_max = function(){
        return this._definitiveDate_max;
    };
    
    //Start Date Min
    this.setStartDate_min = function(value){
        this._startDate_min = value;
    };
    this.getStartDate_min = function(){
        return this._startDate_min;
    };
    //Start Date Max
    this.setStartDate_max = function(value){
        this._startDate_max = value;
    };
    this.getStartDate_max = function(){
        return this._startDate_max;
    };
    
    //End Date Min
    this.setEndDate_min = function(value){
        this._endDate_min = value;
    };
    this.getEndDate_min = function(){
        return this._endDate_min;
    };
    //End Date Max
    this.setEndDate_max = function(value){
        this._endDate_max = value;
    };
    this.getEndDate_max = function(){
        return this._endDate_max;
    };
}

function issueObj() {
    /*Limit recepction: format: dd-mm-aaaa*/
    this._limReception_min = null;
    this._limReception_max = null;
    
    /*Definitive Date: format: dd-mm-aaaa*/
    this._definitiveDate_min = null;
    this._definitiveDate_max = null;
    
    /*Publication Date: format: dd-mm-aaaa*/
    this._publicationDate_min = null;
    this._publicationDate_max = null;
    
    //Limit recepction MIN
    this.setLimReception_min = function(value){
        this._limReception_min = value;
    };
    this.getLimReception_min = function(){
        return this._limReception_min;
    };
    //Limit recepction MAX
    this.setLimReception_max = function(value){
        this._limReception_max = value;
    };
    this.getLimReception_max = function(){
        return this._limReception_max;
    };
    
    //Definitive Date MIN
    this.setDefinitiveDate_min = function(value){
        this._definitiveDate_min = value;
    };
    this.getDefinitiveDate_min = function(){
        return this._definitiveDate_min;
    };
    //Definitive Date MAX
    this.setDefinitiveDate_max = function(value){
        this._definitiveDate_max = value;
    };
    this.getDefinitiveDate_max = function(){
        return this._definitiveDate_max;
    };
    
    //Publication Date Min
    this.setPublicationDate_min = function(value){
        this._publicationDate_min = value;
    };
    this.getPublicationDate_min = function(){
        return this._publicationDate_min;
    };
    //Start Date Max
    this.setPublicationDate_max = function(value){
        this._publicationDate_max = value;
    };
    this.getPublicationDate_max = function(){
        return this._publicationDate_max;
    };
    
}