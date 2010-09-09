<?php



function ping_init(&$a) {

	if(! local_user())
		xml_status(0);

	$r = q("SELECT COUNT(*) AS `total` FROM `item` 
		WHERE `unseen` = 1 AND `visible` = 1 AND `deleted` = 0 AND `uid` = %d",
		intval(get_uid())
	);
	$network = $r[0]['total'];

	$r = q("SELECT COUNT(*) AS `total` FROM `item` 
		WHERE `unseen` = 1 AND `visible` = 1 AND `deleted` = 0 AND `uid` = %d AND `type` != 'remote' ",
		intval(get_uid())
	);
	$home = $r[0]['total'];

	$r = q("SELECT COUNT(*) AS `total` FROM `intro` 
		WHERE `uid` = %d  AND `blocked` = 0 AND `ignore` = 0 ",
		intval(get_uid())
	);
	$intro = $r[0]['total'];

	$myurl = $a->get_baseurl() . '/profile/' . $user['nickname'] ;
	$r = q("SELECT COUNT(*) AS `total` FROM `mail`
		WHERE `uid` = %d AND `seen` = 0 AND `from-url` != '%s' ",
		intval(get_uid()),
		dbesc($myurl)
	);

	$mail = $r[0]['total'];
	
	header("Content-type: text/xml");
	echo "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\r\n<result><intro>$intro</intro><mail>$mail</mail><net>$network</net><home>$home</home></result>\r\n";

	killme();
}

