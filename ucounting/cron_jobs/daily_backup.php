#!/usr/bin/php -q
<?php

define('DB_SERVER_USERNAME','root');
define('DB_SERVER_PASSWORD','');
define('DB_SERVER','localhost');
define('CMP_NAME','mycompany');
define('DB_NAME','ucbook');
define('WEB_ROOT','/var/www/html/UcBooks');

define('DEBUG','0');

$company_name = CMP_NAME;

if(DEBUG)
{
	echo "\n\nCompany name :".$company_name;
}
if(!is_dir(WEB_ROOT."/".PATH_TO_MY_FILES."backups"))
{
	shell_exec("mkdir ".WEB_ROOT."/".PATH_TO_MY_FILES."backups");
	if(DEBUG)
	{
		echo "\n\nmkdir ".WEB_ROOT."/".PATH_TO_MY_FILES."backups";
	}
}

shell_exec("mkdir -p ".WEB_ROOT."/".PATH_TO_MY_FILES."backups/bu-".$company_name."-".date("Ymd").WEB_ROOT."/".PATH_TO_MY_FILES);
if(DEBUG)
{
	echo "\n\nmkdir -p ".WEB_ROOT."/".PATH_TO_MY_FILES."backups/bu-".$company_name."-".date("Ymd").WEB_ROOT."/".PATH_TO_MY_FILES;
}

shell_exec("cp -Rf ".WEB_ROOT."/".PATH_TO_MY_FILES.$company_name." ".WEB_ROOT."/".PATH_TO_MY_FILES."backups/bu-".$company_name."-".date("Ymd").WEB_ROOT."/".PATH_TO_MY_FILES);

if(DEBUG)
{	
	echo "\n\ncp -Rf ".WEB_ROOT."/".PATH_TO_MY_FILES.$company_name." ".WEB_ROOT."/".PATH_TO_MY_FILES."backups/bu-".$company_name."-".date("Ymd").WEB_ROOT."/".PATH_TO_MY_FILES;
}

if(DB_SERVER_PASSWORD!="")
{
	shell_exec("/usr/bin/mysqldump --opt -u ".DB_SERVER_USERNAME." -p".DB_SERVER_PASSWORD." ".DB_NAME." > ".WEB_ROOT."/".PATH_TO_MY_FILES."backups/bu-".$company_name."-".date("Ymd").WEB_ROOT."/".PATH_TO_MY_FILES.$company_name."-".date("Ymd").".sql");
		
	if(DEBUG)
	{
		echo "\n\n/usr/bin/mysqldump --opt -u ".DB_SERVER_USERNAME." -p".DB_SERVER_PASSWORD." ".DB_NAME." > ".WEB_ROOT."/".PATH_TO_MY_FILES."backups/bu-".$company_name."-".date("Ymd").WEB_ROOT."/".PATH_TO_MY_FILES.$company_name."/db-".$company_name."-".date("Ymd").".sql";
	}
}
else
{
	shell_exec("/usr/bin/mysqldump --opt -u ".DB_SERVER_USERNAME." ".DB_NAME." > ".WEB_ROOT."/".PATH_TO_MY_FILES."backups/bu-".$company_name."-".date("Ymd").WEB_ROOT."/".PATH_TO_MY_FILES.$company_name."/db-".$company_name."-".date("Ymd").".sql");
	
	if(DEBUG)
	{
		echo "\n\n/usr/bin/mysqldump --opt -u ".DB_SERVER_USERNAME." ".DB_NAME." > ".WEB_ROOT."/".PATH_TO_MY_FILES."backups/bu-".$company_name."-".date("Ymd").WEB_ROOT."/".PATH_TO_MY_FILES.$company_name."/db-".$company_name."-".date("Ymd").".sql";
	}
}

shell_exec("cd ".WEB_ROOT."/".PATH_TO_MY_FILES."backups/ && zip -r bu-".$company_name."-".date("Ymd").".zip bu-".$company_name."-".date("Ymd")."/");

if(DEBUG)
{	
	echo "\n\ncd ".WEB_ROOT."/".PATH_TO_MY_FILES."backups/ && zip -r bu-".$company_name."-".date("Ymd").".zip bu-".$company_name."-".date("Ymd")."/";
}
shell_exec("rm -Rf ".WEB_ROOT."/".PATH_TO_MY_FILES."backups/bu-".$company_name."-".date("Ymd"));

if(DEBUG)
{
	echo "\n\nrm -Rf ".WEB_ROOT."/".PATH_TO_MY_FILES."backups/bu-".$company_name."-".date("Ymd");
}
?>