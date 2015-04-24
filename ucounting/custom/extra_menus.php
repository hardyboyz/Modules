<?php
//  Path: /modules/ucounting/custom/extra_menus.php
//

$menu[] = array(
  'text'        => sprintf(BOX_STATUS_MGR, ORD_TEXT_16_WINDOW_TITLE), 
  'heading'     => MENU_HEADING_INVENTORY, 
  'rank'        => 16, 
  'security_id' => SECURITY_ID_ADJUST_INVENTORY, 
  'link'        => html_href_link(FILENAME_DEFAULT, 'module=inventory&amp;page=status_adj&amp;adj_type=adj', 'SSL'),
);

if (ENABLE_MULTI_BRANCH) $menu[] = array(
  'text'        =>  sprintf(BOX_STATUS_MGR, BOX_INV_TRANSFER), 
  'heading'     => MENU_HEADING_INVENTORY, 
  'rank'        => 81, 
  'security_id' => SECURITY_ID_TRANSFER_INVENTORY, 
  'link'        => html_href_link(FILENAME_DEFAULT, 'module=inventory&amp;page=status_adj&amp;adj_type=xfr', 'SSL'),
);


?>
