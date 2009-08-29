<?php
// $Id: admin.php,v 1.2 2002/06/29 10:38:59 philip Exp $
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
define('_ADDFORMEXPRESS', 'Ajouter un Nouveau Formulaire');
define('_CANCELFORMEXPRESSDELETE', 'Abandonner Effacement');
define('_CONFIRMFORMEXPRESSDELETE', 'Confirmer Effacement du Formulaire ');
define('_CREATEFAILED', 'Echec durant la création');
define('_DELETEFAILED', 'Impossible a Effacer');
define('_DELETEFORMEXPRESS', 'Effacer le Formulaire');
define('_EDITFORMEXPRESS', 'Modifier le Formulaire');
define('_EDITFORMEXPRESSCONFIG', 'Modifier la configuration de FormExpress');
define('_LOADFAILED', 'Echec au chargement du Module');
define('_NEWFORMEXPRESS', 'Nouveau Formulaire');
define('_FORMEXPRESSADD', 'Ajouter un Formulaire');
define('_FORMEXPRESSCREATED', 'Formualire crée');
define('_FORMEXPRESSDELETED', 'Formulaire effacé');
define('_FORMEXPRESSMODIFYCONFIG', 'Modifier configuration de FormExpress');
define('_FORMEXPRESSNAME', 'Nom du Formulaire');
define('_FORMEXPRESSDESCRIPTION', 'Description');
define('_FORMEXPRESSACTIONSOURCE', 'Action Source');
define('_FORMEXPRESSSUBMITACTION', 'Transmission a');
define('_FORMEXPRESSSUCCESSACTION', 'Action si Succes');
define('_FORMEXPRESSFAILUREACTION', 'Action si Echec');
define('_FORMEXPRESSONLOADACTION', 'OnLoad Action');
define('_FORMEXPRESSVALIDATIONACTION', 'Form level Validation Action');
define('_FORMEXPRESSACTIVE', 'Actif');
define('_FORMEXPRESSLANGUAGE', 'Langue');
define('_FORMEXPRESSNOSUCHITEM', 'Pas de Modeles');
define('_FORMEXPRESSOPTIONS', 'Options');
define('_FORMEXPRESSUPDATE', 'Mise a Jour de FormExpress');
define('_FORMEXPRESSUPDATED', 'Formulaire Mis a jour');
define('_FORMEXPRESSMOVEUP', 'Dessus');
define('_FORMEXPRESSMOVEDOWN', 'Dessous');
define('_FORMEXPRESSEDITITEMS', 'Modeles');
define('_FORMEXPRESSNEWITEMGO', 'Envoyer >>');
define('_FORMEXPRESSINACTIVEITEMS', 'Modeles Inactifs');
define('_FORMEXPRESSITEMOPTIONS', 'Options des Modeles');
define('_VIEWFORMEXPRESS', 'Voir les Formulaires');
define('_FORMEXPRESSITEMSPERPAGE', 'Modeles par page');
define('_FORMEXPRESSDOCS', 'Documentation');

define('_FORMEXPRESSUSERVIEWLINK', 'Vos visisteurs verront ceci (il vous suffit de copier le lien dans votre Menu)');
define('_FORMEXPRESSDEFAULTFORM', 'Formulaire par Defaut (celui qui sera utilisé par FormExpress en page de départ)');
if (!defined('_FORMEXPRESSREQUIREDFIELD')) {
        define('_FORMEXPRESSREQUIREDFIELD', "'*' Indique un Champ Obligatoire");
}
if (!defined('_CONFIRM')) {
	define('_CONFIRM', 'Confirmer');
}
if (!defined('_FORMEXPRESSNOAUTH')) {
	define('_FORMEXPRESSNOAUTH','Vous ne possédez pas les droits nécessaire pour accéder a ce Module');
}

//Import/Export
define('_FORMEXPRESSIMPORTFAILED', 'Importation Impossible (pas de formulaire a ce Nom)');
define('_FORMEXPRESSIMPORTEXPORT', 'Importer/Exporter');
define('_FORMEXPRESSEXPORTNAME', 'Exporter le Formulaire');
define('_FORMEXPRESSIMPORTNAME', 'Import le Formulaire');
define('_FORMEXPRESSEXPORT', 'Exporter');
define('_FORMEXPRESSIMPORT', 'Importer');


//Inquiry Form Items
define('_VIEWFORMEXPRESSITEMS', 'Voir ce Modele');
define('_EDITFORMEXPRESSITEM', 'Modifier ce Modele');
define('_ADDFORMEXPRESSITEM', 'Ajouter un Nouveau Modele');
define('_FORMEXPRESSITEMADD', 'Créer un nouveau Modele de formulaire');
define('_FORMEXPRESSITEMCREATED', 'Modele FormExpress Cree');
define('_FORMEXPRESSITEMUPDATE', 'Mise a Jour du Modele de formulaire');
define('_FORMEXPRESSITEMUPDATED', 'Modele de FormExpress Mis a Jour');
define('_FORMEXPRESSADDITEM', 'Ajouter un Nouveau champ de formulaire de type ');
define('_DELETEFORMEXPRESSITEM', 'Formulaire - Effacer une zone');
define('_CONFIRMFORMEXPRESSITEMDELETE', 'Vous confirmez vouloir Effacer ce champ de formulaire?');
define('_FORMEXPRESSITEMSEQ', 'Sequence');
define('_FORMEXPRESSITEMREQ', 'Obligatoire?');
define('_FORMEXPRESSITEMGROUP', 'Titre du Groupe de Formulaire');
define('_FORMEXPRESSITEMTYPE', 'Type');
define('_FORMEXPRESSITEMNAME', 'Nom');
define('_FORMEXPRESSITEMPROMPT', 'Affichera');
define('_FORMEXPRESSITEMPROMPTPOS', 'en Position');
define('_FORMEXPRESSITEMVALUE', 'Valeur(s)');
define('_FORMEXPRESSITEMDEFVAL', 'Valeur par défaut');
define('_FORMEXPRESSITEMCOLS', 'Largeur');
define('_FORMEXPRESSITEMROWS', 'Hauteur');
define('_FORMEXPRESSITEMMAXLEN', 'Longueur Maximum');
define('_FORMEXPRESSITEMMULTIPLE', 'Permettre des Selections Multiples');

define('_FORMEXPRESSITEMATTR', 'Attributs du modele');
define('_FORMEXPRESSITEMVALRULE', 'Validation');

define('_FORMEXPRESSITEMRELPOS', 'Position (En rapport de la zone précédente)');

define('_FORMEXPRESSMISSINGVALUES', 'Erreur: Une valeur obligatoire a été oubliée');

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

//Icons
define('_FORMEXPRESSEDITICON', 'modules/FormExpress/pnimages/edit.gif');
define('_FORMEXPRESSDELETEICON', 'modules/FormExpress/pnimages/delete.gif');
define('_FORMEXPRESSMOVEUPICON', 'modules/FormExpress/pnimages/up_thin.gif');
define('_FORMEXPRESSMOVEDOWNICON', 'modules/FormExpress/pnimages/down_thin.gif');

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

?>
