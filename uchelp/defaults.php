<?php
// +-----------------------------------------------------------------+
// |                   UcBooks Open Source ERP                    |
// +-----------------------------------------------------------------+
// | Copyright (c) 2008, 2009, 2010, 2011, 2012 UcSoft, LLC       |
// | http://www.UcSoft.com                                        |
// +-----------------------------------------------------------------+
// | This program is free software: you can redistribute it and/or   |
// | modify it under the terms of the GNU General Public License as  |
// | published by the Free Software Foundation, either version 3 of  |
// | the License, or any later version.                              |
// |                                                                 |
// | This program is distributed in the hope that it will be useful, |
// | but WITHOUT ANY WARRANTY; without even the implied warranty of  |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the   |
// | GNU General Public License for more details.                    |
// +-----------------------------------------------------------------+
//  Path: /modules/uchelp/defaults.php
//

// Set the path to the root directory of the help files, specific language if available
if (file_exists(DIR_FS_MODULES . 'ucounting/language/' . $_SESSION['langauge'] . '/manual/welcome.php')) {
  define('DOC_ROOT_URL', DIR_WS_MODULES . 'ucounting/language/' . $_SESSION['langauge'] . '/manual/welcome.html');
} else {
  define('DOC_ROOT_URL', DIR_WS_MODULES . 'ucounting/language/en_us/manual/welcome.html');
}
// URL to the ucsoft help site, BBS, application website, usergroup, etc.
define('DOC_WWW_HELP','http://www.ucsoft.com/documentation.php');
// Extensions allowed for inclusion (separated by commas, no spaces)
define('VALID_EXTENSIONS','html,htm,php,asp');
// Set the maximum title length before truncated to keep displays on a single row
define('PH_DEFAULT_TRIM_LENGTH', 25);

?>