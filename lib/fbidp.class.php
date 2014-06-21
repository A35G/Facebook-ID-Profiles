<?php

	/**
	 * Facebook ID Profiles - beta version
	 * Codename: 'What is your profile?' - FB ID Search
	 * -------------------------------------------------
	 * vers. 0.1.1 - June 2014 - rev. 0.3.2
	 * -------------------------------------------------
	 * Developed by Gianluigi 'A35G'
	 * https://github.com/A35G/Facebook-ID-Profiles
	 * -------------------------------------------------
	 * http://www.gmcode.it/ - http://www.hackworld.it/
	 * -------------------------------------------------
	 * Based on fbid Project by Gianni 'guelfoweb' Amato
	 * https://github.com/guelfoweb/fbid
	 * -------------------------------------------------
	 */

	class FacebookID {

		var $test_conn = "http://74.125.228.100";
		private $fbid;
		private $profileid;
		private $info;

		function __construct() {
			$this->info = array();
		}

		private function navigate_fb($url_navigate) {

			$curl = curl_init();

			curl_setopt_array($curl, array(
					CURLOPT_RETURNTRANSFER => 1,
					CURLOPT_URL => $url_navigate,
					CURLOPT_SSL_VERIFYPEER => false,
					CURLOPT_USERAGENT => 'What is your profile? - https://github.com/A35G/'
			));

			$resp = curl_exec($curl);

			if(!$resp)
				//die('Error: "' . curl_error($curl) . '" - Code: ' . curl_errno($curl));
				return false;
			else
				return $resp;

			curl_close($curl);

		}

    public function checkUrl($url_test) {

      // SCHEME
      $regex = "((https?|ftp)\:\/\/)?";
      // User and Pass
      $regex .= "([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?";
      // Host or IP
      $regex .= "([a-z0-9-.]*)\.([a-z]{2,3})";
      // Port
      $regex .= "(\:[0-9]{2,5})?";
      // Path
      $regex .= "(\/([a-z0-9+\$_-]\.?)+)*\/?";
      // GET Query
      $regex .= "(\?[a-z+&\$_.-][a-z0-9;:@&%=+\/\$_.-]*)?";
      // Anchor
      $regex .= "(#[a-z_.-][a-z0-9+\$_.-]*)?";

      $right_url = (preg_match("/^$regex$/", $url_test)) ? true : false;

      return $right_url;

    }

		private function validate_url_photo($url_photo) {

			if (preg_match("/[0-9]*_[0-9]*_[0-9]*_[a-z].jpg/", $url_photo))
				return true;
			else
				return false;

		}

		private function show_fbid($url_photo) {

			$url_split = explode('_', $url_photo);
			return $url_split[1];

		}

		private function check_profile_id() {

			$err_c = 0;
			$url_fb_photo = "https://www.facebook.com/photo.php?fbid=".$this->fbid;
			$craw_photo = $this->navigate_fb($url_fb_photo);

			if ($craw_photo) {

				$src_content = preg_match_all("/owner\":[0-9]*/", $craw_photo, $bsdx);

				if (($src_content) && !empty($src_content)) {

					$prfid = explode(":", $bsdx[0][0]);
					$data_profile = $prfid[1];

				} else { $err_c++; }

			} else { $err_c++; }

			if (!empty($err_c))
				return false;
			else
				return $data_profile;

		}

		private function show_profile_id() {

			$url_fb_graph = "https://graph.facebook.com/".$this->profileid;
			$craw_info = $this->navigate_fb($url_fb_graph);

			if ($craw_info)
				return json_decode($craw_info, true);
			else
				return false;

		}

		public function getProfile($url_photo) {

			if ($this->navigate_fb($this->test_conn) && $this->validate_url_photo($url_photo))
				$this->fbid = $this->show_fbid($url_photo);

			$this->profileid = $this->check_profile_id();

			$rivs = '';

			if (!$this->profileid) {

				$fbres = $this->fbid;

				if (file_exists('../tpl/row_friend.php'));
					include('../tpl/row_friend.php');

				$rivs .= $tpl_row;

			} else {

				$this->info = $this->show_profile_id();

				foreach ($this->info as $fbattr => $fbval) {

					$attrib = $fbattr;
					$fbres = stripslashes(htmlentities(htmlspecialchars($fbval, ENT_QUOTES, 'UTF-8'), ENT_QUOTES, 'UTF-8'));

					if (file_exists('../tpl/row_res.php'))
						include('../tpl/row_res.php');

					$rivs .= $tpl_row;

				}

				//print_r($this->info);

				if (!empty($rivs)) {

					$search_response = $rivs;

					if (file_exists('../tpl/table_res.php'))
						include('../tpl/table_res.php');

					return $tpl_table;

				}

			}

		}

	}