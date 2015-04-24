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
//  Path: /modules/contacts/classes/type/v.php
//
require_once(DIR_FS_MODULES . 'contacts/classes/contacts.php');

class v extends contacts{	
	public $security_token = SECURITY_ID_MAINTAIN_VENDORS;
	public $page_title_new = BOX_CONTACTS_NEW_VENDOR;
	public $auto_type      = AUTO_INC_VEND_ID;
	public $auto_field     = 'next_vend_id_num';
	public $journals	     = '6,7,21';
	public $help		       = '07.02.02.02';
	public $address_types  = array('vm', 'vs', 'vb', 'im');
	public $type            = 'v';
	
	public function __construct(){
		$this->tab_list[] = array('file'=>'template_addbook',	'tag'=>'addbook',  'order'=>20, 'text'=>TEXT_ADDRESS_BOOK);
		$this->tab_list[] = array('file'=>'template_contacts',	'tag'=>'contacts', 'order'=> 5, 'text'=>TEXT_CONTACTS);
		$this->tab_list[] = array('file'=>'template_history',	'tag'=>'history',  'order'=>10, 'text'=>TEXT_HISTORY);
		$this->tab_list[] = array('file'=>'template_notes',		'tag'=>'notes',    'order'=>40, 'text'=>TEXT_NOTES);
		$this->tab_list[] = array('file'=>'template_general',	'tag'=>'general',  'order'=> 1, 'text'=>TEXT_GENERAL);
		parent::__construct();
	}
}
?>