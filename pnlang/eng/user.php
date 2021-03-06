<?php
// $Id: user.php,v 1.2 2002/06/29 10:38:59 philip Exp $
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
// Purpose of file:  Language defines for pnuser.php
// ----------------------------------------------------------------------

//Headings etc
define('_FORMEXPRESS', 'FormExpress');
define('_FORMEXPRESSVIEW', 'View Forms');
define('_FORMEXPRESSREQUIREDFIELD', "'*' indicates required field");
define('_FORMEXPRESSNAME', 'Form Name');
define('_FORMEXPRESSDESCRIPTION', 'Description');


//Error messages
define('_FORMEXPRESSFORMFAILED', 'No forms found');
define('_FORMEXPRESSITEMFAILED', 'No items found');
define('_FORMEXPRESSVALIDATIONFAILED', 'ERROR! Validation Failed<br>');
define('_FORMEXPRESSVALUEREQUIRED', ' is a required field');
define('_FORMEXPRESSNOFORMFOUND', 'No form found');
define('_FORMEXPRESSNOITEMSFOUND', 'No items have been defined for this form');
define('_FORMEXPRESSNOBLOCKFORMID', 'No Form has been specified for this block');
if (!defined('_FORMEXPRESSNOAUTH')) {
	define('_FORMEXPRESSNOAUTH','Not authorised to access FormExpress module');
}
define('_FORMEXPRESSFUNCPARSEERROR', 'A parse error occured. Please check your Form action(s) syntax or your Item validation/default value syntax.');
define('_FORMEXPRESSFUNCVOIDRESULT', 'I was expecting a result, but got nothing (void returned from your dynamic function call).  Please check your Form action(s) syntax or your Item validation/default value syntax.');


//Sendmail backend
define('_FORMEXPRESSEMAILHEADER', '');
define('_FORMEXPRESSEMAILFOOTER', '');
define('_FORMEXPRESSEMAILSENDERROR', 'Mail transport failure');
define('_FORMEXPRESSEMAILID', 'Mail ID = ');
define('_FORMEXPRESSEMAILADDRERROR', 'No email address found. Please check your Form action syntax.');

?>
