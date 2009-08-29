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
// Purpose of file:  Language defines for pnuser.php
// ----------------------------------------------------------------------
define('_FORMEXPRESS', 'FormExpress');
define('_FORMEXPRESSFORMFAILED', 'Ingen skjema funnet');
define('_FORMEXPRESSITEMFAILED', 'Ingen elementer funnet');
define('_FORMEXPRESSNAME', 'Skjemanavn');
define('_FORMEXPRESSDESCRIPTION', 'Beskrivelse');
define('_FORMEXPRESSVIEW', 'Vis skjemaer');
define('_FORMEXPRESSVALUEREQUIRED', 'Mangler obligatorisk verdi(er) for: ');
define('_FORMEXPRESSREQUIREDFIELD', "'*' indikerer obligatorisk felt");

define('_FORMEXPRESSEMAILHEADER', '');
define('_FORMEXPRESSEMAILFOOTER', '');
define('_FORMEXPRESSEMAILSENTMSG', 'Vennligst oppgi dette referansenummeret i framtidige henvendelser: ');
define('_FORMEXPRESSEMAILSENDERROR', 'E-post transport feil');
define('_FORMEXPRESSEMAILID', 'E-post ID = ');

if (!defined('_FORMEXPRESSNOAUTH')) {
	define('_FORMEXPRESSNOAUTH','Ikke autorisert tilgang til FormExpress modulen');
}
?>
