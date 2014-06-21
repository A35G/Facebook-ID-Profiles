<?php

	$inform = '';

	if (isset($_POST['url_p']) && !empty($_POST['url_p'])) {

		$url_img = htmlentities($_POST['url_p'], ENT_QUOTES, 'UTF-8');

		if (file_exists('../lib/fbidp.class.php'))
			include('../lib/fbidp.class.php');

		if (class_exists('FacebookID')) {

    	$fbres = new FacebookID;
    	$val_url = $fbres->checkUrl($url_img);

    	if ($val_url)
    		$inform = $fbres->getProfile($url_img);

		}

	}

	if (!empty($inform))
		echo $inform;