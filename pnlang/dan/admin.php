<?php // File: admin.php 19.08.03
// ----------------------------------------------------------------------
// POST-NUKE Content Management System
// Copyright (C) 2001 by the Post-Nuke Development Team.
// http://www.postnuke.com/
// Based on ... License ... You know the drill.
// ----------------------------------------------------------------------
//
// Purpose of file: Language Definitions for FormExpress module
// ----------------------------------------------------------------------
//
// Please include your name and an e-mail where you may be contacted if further help is needed.
//
//  Translator: 
//  E-mail: 
// ----------------------------------------------------------------------

define('_FORMEXPRESS','FormExpress');
define('_ADDFORMEXPRESS','Tilføj ny formular');
define('_CANCELFORMEXPRESSDELETE','Fortryd sletning');
define('_CONFIRMFORMEXPRESSDELETE','Bekræft sletning af formular');
define('_CREATEFAILED','Oprettelse mislykkedes');
define('_DELETEFAILED','Sletning mislykkedes');
define('_DELETEFORMEXPRESS','Slet formular');
define('_EDITFORMEXPRESS','Rediger formular');
define('_EDITFORMEXPRESSCONFIG','Rediger formular konfiguration');
define('_LOADFAILED','Indlæsning af modul mislykkedes');
define('_NEWFORMEXPRESS','Ny formular');
define('_FORMEXPRESSADD','Tilføj ny formular');
define('_FORMEXPRESSCREATED','Formular oprettet');
define('_FORMEXPRESSDELETED','Formular slettet');
define('_FORMEXPRESSMODIFYCONFIG','Gem formular ændringer');
define('_FORMEXPRESSNAME','Formularens navn');
define('_FORMEXPRESSDESCRIPTION','Beskrivelse');
define('_FORMEXPRESSACTIONSOURCE','Action Source');
define('_FORMEXPRESSSUBMITACTION','Submit Action');
define('_FORMEXPRESSSUCCESSACTION','Success Action');
define('_FORMEXPRESSFAILUREACTION','Failure Action');
define('_FORMEXPRESSONLOADACTION','OnLoad Action');
define('_FORMEXPRESSVALIDATIONACTION','Form level Validation Action');
define('_FORMEXPRESSACTIVE','Aktiv');
define('_FORMEXPRESSLANGUAGE','Sprog');
define('_FORMEXPRESSNOSUCHITEM','Ingen sådan enhed');
define('_FORMEXPRESSOPTIONS','Muligheder');
define('_FORMEXPRESSUPDATE','Opdater formular');
define('_FORMEXPRESSUPDATED','Formular opdateret');
define('_FORMEXPRESSMOVEUP','Op');
define('_FORMEXPRESSMOVEDOWN','Ned');
define('_FORMEXPRESSEDITITEMS','Enheder');
define('_FORMEXPRESSNEWITEMGO','Kør &gt;&gt;');
define('_FORMEXPRESSINACTIVEITEMS','Inaktive enheder');
define('_FORMEXPRESSITEMOPTIONS','Enhedsmuligheder');
define('_VIEWFORMEXPRESS','Vis formularer');
define('_FORMEXPRESSITEMSPERPAGE','Enheder pr. side');
define('_FORMEXPRESSDOCS','Dokumentation');
define('_FORMEXPRESSUSERVIEWLINK','User View of this form (copy link to menu)');
define('_FORMEXPRESSDEFAULTFORM','Default Form (will be used if FormExpress is the home page)');
define('_CONFIRM','Bekræft');
define('_FORMEXPRESSNOAUTH','Not authorised to access FormExpress module');
define('_FORMEXPRESSIMPORTFAILED','Form Import failed (no form name found)');
define('_FORMEXPRESSIMPORTEXPORT','Import/Export');
define('_FORMEXPRESSEXPORTNAME','Export Form Name');
define('_FORMEXPRESSIMPORTNAME','Import File Name');
define('_FORMEXPRESSEXPORT','Export');
define('_FORMEXPRESSIMPORT','Import');
define('_VIEWFORMEXPRESSITEMS','View Form Items');
define('_EDITFORMEXPRESSITEM','Edit Form Item');
define('_ADDFORMEXPRESSITEM','Add a new Form Item');
define('_FORMEXPRESSITEMADD','Create New Form Item');
define('_FORMEXPRESSITEMCREATED','FormExpress Item Created');
define('_FORMEXPRESSITEMUPDATE','Update Form Item');
define('_FORMEXPRESSITEMUPDATED','FormExpress Item Updated');
define('_FORMEXPRESSADDITEM','Add New Form Item of type');
define('_FORMEXPRESSBEFORE','Før');
define('_FORMEXPRESSENDOFFORM','bunden af formularen');
define('_DELETEFORMEXPRESSITEM','Delete Form Item');
define('_CONFIRMFORMEXPRESSITEMDELETE','Confirm deletion of form item');
define('_FORMEXPRESSITEMDELETED','Enhed slettet');
define('_FORMEXPRESSITEMSEQ','Sequence');
define('_FORMEXPRESSITEMREQ','Required?');
define('_FORMEXPRESSITEMGROUP','Group Title');
define('_FORMEXPRESSITEMTYPE','Type');
define('_FORMEXPRESSITEMNAME','Name');
define('_FORMEXPRESSITEMPROMPT','Prompt');
define('_FORMEXPRESSITEMPROMPTPOS','Prompt Position');
define('_FORMEXPRESSITEMVALUE','Value(s)');
define('_FORMEXPRESSITEMDEFVAL','Default Value');
define('_FORMEXPRESSITEMCOLS','Width');
define('_FORMEXPRESSITEMROWS','Height');
define('_FORMEXPRESSITEMMAXLEN','Maximum Length');
define('_FORMEXPRESSITEMMULTIPLE','Allow Multiple Selections');
define('_FORMEXPRESSITEMATTR','Item Attributes');
define('_FORMEXPRESSITEMVALRULE','Validation Rule');
define('_FORMEXPRESSITEMRELPOS','Position (Relative to previous item)');
define('_FORMEXPRESSMISSINGVALUES','Error: Missing required values');
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

define('_FORMEXPRESSITEMTYPELOVBOILERPLATE','boilerplate');
define('_FORMEXPRESSITEMTYPELOVCHECKBOX','checkbox');
define('_FORMEXPRESSITEMTYPELOVRADIO','radio');
define('_FORMEXPRESSITEMTYPELOVSELECTLIST','selectlist');
define('_FORMEXPRESSITEMTYPELOVTEXT','text');
define('_FORMEXPRESSITEMTYPELOVPASSWORD','password');
define('_FORMEXPRESSITEMTYPELOVTEXTAREA','textarea');
define('_FORMEXPRESSITEMTYPELOVSUBMIT','submit');
define('_FORMEXPRESSITEMTYPELOVRESET','reset');
define('_FORMEXPRESSITEMTYPELOVBUTTON', 'button');
define('_FORMEXPRESSITEMTYPELOVHIDDEN','hidden');
define('_FORMEXPRESSITEMTYPELOVGROUPSTART','GroupStart');
define('_FORMEXPRESSITEMTYPELOVGROUPEND','GroupEnd');
define('_FORMEXPRESSPROMPTLOVABOVE','Above');
define('_FORMEXPRESSPROMPTLOVBELOW','Below');
define('_FORMEXPRESSPROMPTLOVLEFTCOL','Left Column');
define('_FORMEXPRESSPROMPTLOVLEFT','Left');
define('_FORMEXPRESSPROMPTLOVRIGHT','Right');
define('_FORMEXPRESSPROMPTLOVHIDDEN','Hidden');
//Icons
define('_FORMEXPRESSEDITICON', 'modules/FormExpress/pnimages/edit.gif');
define('_FORMEXPRESSDELETEICON', 'modules/FormExpress/pnimages/delete.gif');
define('_FORMEXPRESSMOVEUPICON', 'modules/FormExpress/pnimages/up_thin.gif');
define('_FORMEXPRESSMOVEDOWNICON', 'modules/FormExpress/pnimages/down_thin.gif');


?>