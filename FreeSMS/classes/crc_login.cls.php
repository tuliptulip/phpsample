<?php
	// Include the CRC Object class that needs to
	// extended by all classes. This is the super
	// class.
	if(isset($_REQUEST[session_name()])) {
		// There is a session already available		
		session_start();
	}
	include_once('crc_constants.mod.php');	
	include_once('crc_object.cls.php');	
	include_once('crc_mysql.cls.php');	

	//******************************************
	// Name: crc_login
	//******************************************
	//
	// Desc: The Login Object
	// Developer: Free SMS team
	// Email: shaffin_bhanji@hotmail.com
	// Date: March 10th, 2003
	// Version: 1.0.0
	//
	// Copyright
	// =========
	// This code is copyright, use in part or
	// whole is prohibited without a written
	// concent to the developer.
	//******************************************

	class crc_login extends crc_object {

		var $m_uid;
		var $m_pwd;
		var $m_rdn;
		var $m_sess;
		var $m_name;
		var $m_profileid;
		var $m_roleid;

		var $m_sql;

		function crc_login($debug) {
			//******************************************
			// Initialization by constructor
			//******************************************
			$this->classname = 'crc_login';
			$this->classdescription = 'Handle user login.';
			$this->classversion = '1.0.0';
			$this->classdate = 'March 10th, 2003';
			$this->classdevelopername = 'Shaffin Bhanji';
			$this->classdeveloperemail = 'shaffin_bhanji@hotmail.com';
			$this->_DEBUG = $debug;

			if ($this->_DEBUG) {
				echo "DEBUG {crc_login::constructor}: The class \"crc_login\" was successfuly created. <br>";
				echo "DEBUG {crc_login::constructor}: Running in debug mode. <br>";
			}

		}

		function fn_session() {
			//******************************************
			// Return SQL query result
			//******************************************

			if ($this->_DEBUG) {
				echo "DEBUG {crc_login::fn_session}: Adding user information to the session table.<br>";
			}

			//check entries
			if (($this->m_sess == "") || ($this->m_uid == "") ||
				($this->m_pwd == "") || ($this->m_rdn == ""))
			{
				$this->lasterrmsg = "Invalid input";
				return false;		
			}
			
			$db = new crc_mysql($this->_DEBUG);
			$dbhandle = $db->fn_connect();
			if ($dbhandle != false) {

				$this->m_sql = 'insert into ' . MYSQL_SESSIONS_TBL .
								'(session_id, session_uid, session_pwd, session_dn) ' .
								'values("' . $this->m_sess . '", "' . $this->m_uid	. '", "' .
								$this->m_pwd . '", "' . $this->m_rdn . '")';

				$result = $db->fn_runsql(MYSQL_DB, $this->m_sql);
				$db->fn_disconnect();
				return true;
			} else {
				$this->lasterrnum = $db->lasterrnum;
				$this->lasterrmsg = $db->lasterrmsg;
				if ($this->_DEBUG) {
					echo "DEBUG {crc_login::fn_session}: " . $this->lasterrmsg . ".<br>";
				}
				return false;
			}
		}

		function fn_login($post) {
			//******************************************
			// Set all the member variables with the post information.
			//******************************************
			
			$this->m_uid = $post['username'];
			$this->m_pwd = $post['password'];
			$this->m_rdn = $post['context'];
			$this->m_sess = session_id();
			
			//check entries
			if (($this->m_sess == "") || ($this->m_uid == "") ||
				($this->m_pwd == "") || ($this->m_rdn == ""))
			{
				$this->lasterrmsg = "Please enter a nonempty username and/or password";
				return false;		
			}
			
			if ($this->_DEBUG) {
				echo "DEBUG {crc_login::fn_login}: Trying to login. <br>";
				echo "DEBUG {crc_login::fn_login}: The session id is \"" . $this->m_sess . "\". <br>";
				echo "DEBUG {crc_login::fn_login}: The username for login is \"" . $this->m_uid . "\". <br>";
				echo "DEBUG {crc_login::fn_login}: The password for login is \"" . $this->m_pwd . "\". <br>";
				echo "DEBUG {crc_login::fn_login}: The context for login is \"" . $this->m_rdn . "\". <br>";
			}
			$result = false;
			if ($this->fn_session()) { 
				$db = new crc_mysql($this->_DEBUG);
				$dbhandle = $db->fn_connect();
				if ($dbhandle != false) {
					$this->m_sql = 'select profile_id, profile_firstname, profile_lastname, profile_role_id ' . 
									'from ' . MYSQL_PROFILES_TBL . 
									' where ((profile_uid = "' . $this->m_uid . '") AND' .
									' (profile_pwd = SHA1("' . $this->m_pwd . '")) AND (profile_rdn = "' . $this->m_rdn . '"))';

					$resource = $db->fn_runsql(MYSQL_DB, $this->m_sql);

					if (mysql_num_rows($resource) > 0) {
						$row = mysql_fetch_row($resource);
						$this->m_name = $row[1] . ' ' . $row[2];
						$this->m_profileid = $row[0];
						$this->m_roleid = $row[3];
						$result = true;
					} else {
						$this->lasterrnum = mysql_errno();
						$this->lasterrmsg = mysql_error();
						if ($this->lasterrmsg == '') {
							$this->lasterrnum = ERR_LOGIN_NOUSER_NUM;
							$this->lasterrmsg = ERR_LOGIN_NOUSER_DESC;
						}
						if ($this->_DEBUG) {
							echo '<font color="red">';
							echo 'ERROR {crc_login::fn_login}: The sql command returned nothing. <br>';
							echo 'ERROR {crc_login::fn_login}: Error number: ' . $this->lasterrnum . '. <br>';
							echo 'ERROR {crc_login::fn_login}: Error description: ' . $this->lasterrmsg . '. <br>';
							echo '</font>';
						}
					}
					$db->fn_freesql($resource);
					$db->fn_disconnect();					
				} else {
					$this->lasterrnum = $db->lasterrnum;
					$this->lasterrmsg = $db->lasterrmsg;
				}				
			}
			return $result;
		}
	}
?>
