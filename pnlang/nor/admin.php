<?php 
// $Id$
// ----------------------------------------------------------------------
// FormExpress module for POST-NUKE Content Management System
// Copyright (C) 2002 by Stutchbury Limited
// http://www.stutchbury.net/
// ----------------------------------------------------------------------
// Based on:
// PHP-NUKE Web Portal System - http://phpnuke.org/
// Thatware - http://thatware.org/
// ----------------------------------------------------------------------
// LICENSE
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License (GPL)
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WIthOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------
// Original Author of file: Philip Fletcher
// Purpose of file:  Language defines for pnadmin.php
// ----------------------------------------------------------------------
//
define('_FORMEXPRESS', 'FormExpress');
define('_ADDFORMEXPRESS', 'Legg til nytt skjema');
define('_CANCELFORMEXPRESSDELETE', 'Avbryt sletting');
define('_CONFIRMFORMEXPRESSDELETE', 'Bekreft sletting av skjema');
define('_CREATEFAILED', 'Oppretelse av nytt skjema feilet');
define('_DELETEFAILED', 'Sletting feilet');
define('_DELETEFORMEXPRESS', 'Slett Skjema');
define('_EDITFORMEXPRESS', 'Rediger Skjema');
define('_EDITFORMEXPRESSCONFIG', 'Rediger Skjema konfigurasjon');
define('_LOADFAILED', 'Lasting av modul feilet');
define('_NEWFORMEXPRESS', 'Nytt Skjema');
define('_FORMEXPRESSADD', 'Legg til nytt skjema');
define('_FORMEXPRESSCREATED', 'Skjema opprettet');
define('_FORMEXPRESSDELETED', 'Skjema slettet');
define('_FORMEXPRESSMODIFYCONFIG', 'Rediger Skjema konfigurasjon');
define('_FORMEXPRESSNAME', 'Skjema navn');
define('_FORMEXPRESSDESCRIPTION', 'Beskrivelse');
define('_FORMEXPRESSACTIONSOURCE', 'Action Source');
define('_FORMEXPRESSACTIONNAME', 'Action Name');
define('_FORMEXPRESSACTIONARGS', 'Action Arguments');
define('_FORMEXPRESSSUCCESSMSG', 'Action Success Message');
define('_FORMEXPRESSFAILUREMSG', 'Action Failure Message');
define('_FORMEXPRESSACTIVE', 'Aktiv');
define('_FORMEXPRESSLANGUAGE', 'Språk');
define('_FORMEXPRESSNOSUCHITEM', 'Intet slik element');
define('_FORMEXPRESSOPTIONS', 'Valg');
define('_FORMEXPRESSUPDATE', 'Oppdater skjema');
define('_FORMEXPRESSUPDATED', 'Skjema oppdatert');
define('_FORMEXPRESSMOVEUP', 'Opp');
define('_FORMEXPRESSMOVEDOWN', 'Ned');
define('_FORMEXPRESSEDITITEMS', 'Elementer');
define('_FORMEXPRESSNEWITEMGO', 'Gå >>');
define('_FORMEXPRESSINACTIVEITEMS', 'Inaktive element');
define('_FORMEXPRESSITEMOPTIONS', 'Element valg');
define('_VIEWFORMEXPRESS', 'Se skjema');
define('_FORMEXPRESSITEMSPERPAGE', 'Elementer pr side');
if (!defined('_CONFIRM')) {
	define('_CONFIRM', 'Bekreft');
}
if (!defined('_FORMEXPRESSNOAUTH')) {
	define('_FORMEXPRESSNOAUTH','Ikke autorisert tilgang til FormExpress modulen');
}
//Inquiry Form Items
define('_VIEWFORMEXPRESSITEMS', 'Se skjemaelementer');
define('_EDITFORMEXPRESSITEM', 'Rediger skjemaelementer');
define('_ADDFORMEXPRESSITEM', 'Legg til et nytt skjemaelement');
define('_FORMEXPRESSITEMADD', 'Opprett nytt skjemaelement');
define('_FORMEXPRESSITEMCREATED', 'FormExpress element opprettet');
define('_FORMEXPRESSITEMUPDATE', 'Oppdater skjemaelement');
define('_FORMEXPRESSITEMUPDATED', 'FormExpress element oppdatert');
define('_FORMEXPRESSADDITEM', 'Legg til nytt skjemaelement av type ');
define('_FORMEXPRESSITEMSEQ', 'Rekkefølge');
define('_FORMEXPRESSITEMREQ', 'Obligatorisk ?');
define('_FORMEXPRESSITEMGROUP', 'Gruppe tittel');
define('_FORMEXPRESSITEMTYPE', 'Type');
define('_FORMEXPRESSITEMNAME', 'Navn');
define('_FORMEXPRESSITEMPROMPT', 'Prompt');
define('_FORMEXPRESSITEMPROMPTPOS', 'Prompt Posisjon');
define('_FORMEXPRESSITEMVALUE', 'Verdi(er)');
define('_FORMEXPRESSITEMDEFVAL', 'Standard verdi');
define('_FORMEXPRESSITEMCOLS', 'Bredde');
define('_FORMEXPRESSITEMROWS', 'Høyde');
define('_FORMEXPRESSITEMMAXLEN', 'Maksimum lengde');
define('_FORMEXPRESSITEMMULTIPLE', 'Tillat multippele valg');
define('_FORMEXPRESSITEMCLASS', 'CSS Class Navn');
define('_FORMEXPRESSITEMRELPOS', 'Posisjon (Ihenhold til forrige element)');

define('_FORMEXPRESSITEMBOILERPLATEHELP', 'Boilerplate Help: Text entered in ' . _FORMEXPRESSITEMVALUE . ' field will be displayed verbatim. This can be used to enter html markup such as &lt;img&gt; links or to generally mess around with the layout.');
//define('_FORMEXPRESSITEMCHECKBOXHELP', 'Checkbox Help '. _FORMEXPRESSITEMVALUE);

define('_FORMEXPRESSITEMRADIOHELP', 'Radio Help: The Name field defines a radio button set. Radio buttons within a set will be mutually exclusive. The Value(s) field determines the value returned if a button is selected. If a default value is entered, the button will be selected. The Prompt is normally positioned on the right.');

define('_FORMEXPRESSITEMCHECKBOXHELP', 'Checkbox Help: The Value(s) field determines the value returned if a checkbox is selected. If any default value is entered, the checkbox will be selected. The Prompt is normally positioned on the right.');

define('_FORMEXPRESSITEMSELECTLISTHELP', 'SelectList Help: Contents of ' . _FORMEXPRESSITEMVALUE . ' field will be used to build a list of values from comma seperated string. Example:<tt>,1=Advert,2=Recomendation,3=Internet,Other</tt> will create a list of five values, the first is blank, the next three will return 1,2 or 3, the last will return Other.');

//Covered by generic help
//define('_FORMEXPRESSITEMTEXTHELP', 'Text Help');
//define('_FORMEXPRESSITEMPASSWORDHELP', 'Password Help');
//define('_FORMEXPRESSITEMTEXTAREAHELP', 'TextArea Help');
//define('_FORMEXPRESSITEMSUBMITHELP', 'Submit Help');
//define('_FORMEXPRESSITEMRESETHELP', 'Reset Help');

define('_FORMEXPRESSITEMBUTTONHELP', 'Button Help: Default value should be either button, submit or reset. Value will be displayed on button and also used for the value attribute.');

define('_FORMEXPRESSITEMHIDDENHELP', 'Hidden Help');

define('_FORMEXPRESSITEMGROUPSTARTHELP', 'Group Start Help: Prompt is normally hidden, but can be used as per normal imput items. Contents of the Value(s) field will be used as a region heading in a &lt;fieldset&gt; element. To ensure the resulting html is well formed, please remember to explicitly create a GroupEnd as and when required');

define('_FORMEXPRESSITEMGROUPENDHELP', 'Group End Help: Thank you for creating a GroupEnd. This will help to ensure your html is well formed.');

define('_FORMEXPRESSITEMGENERICHELP', '<p>Generic Help</p><p>Name: Must be unique within a form.</p><p>Required: Set if you want your field to be mandatory.</p><p>Prompt: Optionally displayed as per the Prompt Position.</p><p>Prompt Position: Fairly obvious, except 	Left Column, which will place the Prompt into a seperate table cell to the left of the item.</p><p>Item Attributes: Enter any valid attribute(s) for the the type on element, space seperated, formatted as: name="value".</p><p>Position (Relative to previous item): FormExpress uses a patented* RIB layout engine which allows you to place items to the Right, Inline or Below the previous item. To Right: will create a new table cell, but remain on current row; Inline - will place item in the same table cell as previous item; Below - will create a new row; </p><p/><p>*Only joking<p/>'
);


//Item Type LOV
define('_FORMEXPRESSITEMTYPELOVBOILERPLATE', 'boilerplate');
define('_FORMEXPRESSITEMTYPELOVCHECKBOX', 'checkbox');
define('_FORMEXPRESSITEMTYPELOVRADIO', 'radio');
define('_FORMEXPRESSITEMTYPELOVSELECTLIST', 'selectlist');
define('_FORMEXPRESSITEMTYPELOVTEXT', 'text');
define('_FORMEXPRESSITEMTYPELOVPASSWORD', 'password');
define('_FORMEXPRESSITEMTYPELOVTEXTAREA', 'textarea');
define('_FORMEXPRESSITEMTYPELOVSUBMIT', 'submit');
define('_FORMEXPRESSITEMTYPELOVRESET', 'reset');
define('_FORMEXPRESSITEMTYPELOVBUTTON', 'button');
define('_FORMEXPRESSITEMTYPELOVHIDDEN', 'hidden');
define('_FORMEXPRESSITEMTYPELOVGROUPSTART', 'GroupStart');
define('_FORMEXPRESSITEMTYPELOVGROUPEND', 'GroupEnd');

//Prompt position LOV
define('_FORMEXPRESSPROMPTLOVABOVE', 'Above');
define('_FORMEXPRESSPROMPTLOVBELOW', 'Below');
define('_FORMEXPRESSPROMPTLOVLEFTCOL', 'Left Column');
define('_FORMEXPRESSPROMPTLOVLEFT', 'Left');
define('_FORMEXPRESSPROMPTLOVRIGHT', 'Right');
define('_FORMEXPRESSPROMPTLOVHIDDEN', 'Hidden');

//Item position LOV
define('_FORMEXPRESSITEMPOSLOVBELOW', 'Below' );
define('_FORMEXPRESSITEMPOSLOVRIGHT', 'To Right' );
define('_FORMEXPRESSITEMPOSLOVINLINE', 'Inline' );

//Icons
define('_FORMEXPRESSEDITICON', 'modules/FormExpress/pnimages/edit.gif');
define('_FORMEXPRESSDELETEICON', 'modules/FormExpress/pnimages/delete.gif');
define('_FORMEXPRESSMOVEUPICON', 'modules/FormExpress/pnimages/up_thin.gif');
define('_FORMEXPRESSMOVEDOWNICON', 'modules/FormExpress/pnimages/down_thin.gif');


?>
