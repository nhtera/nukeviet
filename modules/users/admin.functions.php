<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @createdate 12/31/2009 2:29
 */

if( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) )
	die( 'Stop!!!' );

define( 'NV_IS_FILE_ADMIN', true );

list( $access_admin ) = $db->sql_fetchrow( $db->sql_query( "SELECT `content` FROM `" . NV_USERS_GLOBALTABLE . "_config` WHERE `config`='access_admin'" ) );
$access_admin = unserialize( $access_admin );

$allow_func = array(
	'main',
	'getuserid'
);
$level = $admin_info['level'];
if( isset( $access_admin['access_addus'][$level] ) AND $access_admin['access_addus'][$level] == 1 )
{
	$submenu['user_add'] = $lang_module['user_add'];
	$allow_func[] = 'user_add';
}
if( isset( $access_admin['access_waiting'][$level] ) AND $access_admin['access_waiting'][$level] == 1 )
{
	$submenu['user_waiting'] = $lang_module['member_wating'];
	$allow_func[] = 'user_waiting';
	$allow_func[] = 'setactive';
}
if( isset( $access_admin['access_editus'][$level] ) AND $access_admin['access_editus'][$level] == 1 )
{
	$allow_func[] = 'edit';
}
if( isset( $access_admin['access_delus'][$level] ) AND $access_admin['access_delus'][$level] == 1 )
{
	$allow_func[] = 'del';
}
$access['checked_passus'] = (isset( $access_admin['access_passus'][$level] ) AND $access_admin['access_passus'][$level] == 1) ? ' checked="checked" ' : '';
if( isset( $access_admin['access_groups'][$level] ) AND $access_admin['access_groups'][$level] == 1 )
{
	$submenu['groups'] = $lang_global['mod_groups'];
	$allow_func[] = 'groups';
}
if( defined( 'NV_IS_SPADMIN' ) )
{
	$submenu['question'] = $lang_module['question'];
	$submenu['siteterms'] = $lang_module['siteterms'];
	$allow_func[] = 'question';
	$allow_func[] = 'siteterms';
	if( defined( 'NV_IS_GODADMIN' ) )
	{
		$submenu['fields'] = $lang_module['fields'];
		$allow_func[] = 'fields';
		$submenu['config'] = $lang_module['config'];
		$allow_func[] = 'config';
	}
}

/**
 * groupList()
 *
 * @return
 */
function groupList( )
{
	global $db;

	$sql = "SELECT * FROM `" . NV_GROUPS_GLOBALTABLE . "` ORDER BY `weight`";
	$result = $db->sql_query( $sql );

	$groups = array( );
	while( $row = $db->sql_fetch_assoc( $result ) )
	{
		$groups[$row['group_id']] = $row;
	}

	return $groups;
}

/**
 * nv_fix_question()
 *
 * @return
 */
function nv_fix_question( )
{
	global $db;

	$sql = "SELECT `qid` FROM `" . NV_USERS_GLOBALTABLE . "_question` WHERE `lang`='" . NV_LANG_DATA . "' ORDER BY `weight` ASC";
	$result = $db->sql_query( $sql );
	$weight = 0;
	while( $row = $db->sql_fetchrow( $result ) )
	{
		++$weight;
		$sql = "UPDATE `" . NV_USERS_GLOBALTABLE . "_question` SET `weight`=" . $weight . " WHERE `qid`=" . $row['qid'];
		$db->sql_query( $sql );
	}
	$db->sql_freeresult( );
}
?>