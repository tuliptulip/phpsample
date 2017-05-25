<?php
	// Include the CRC Object class that needs to
	// extended by all classes. This is the super
	// class.
	include_once('crc_constants.mod.php');
	include_once('crc_object.cls.php');
	include_once('crc_mysql.cls.php');

	//******************************************
	// Name: crc_object
	//******************************************
	//
	// Desc: The Evaluation Object
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

	class crc_evaluation extends crc_object {

		var $m_sql;
		var $m_data;
		var $m_question;
		var $m_profilename;
		var $m_date;
		var $m_studentnb;
		
		function crc_evaluation($debug) {
			//******************************************
			// Initialization by constructor
			//******************************************
			$this->classname = 'crc_evaluation';
			$this->classdescription = 'Handle user feedback and evaluation.';
			$this->classversion = '1.0.0';
			$this->classdate = 'March 10th, 2003';
			$this->classdevelopername = 'Shaffin Bhanji';
			$this->classdeveloperemail = 'shaffin_bhanji@hotmail.com';
			$this->_DEBUG = $debug;

			if ($this->_DEBUG) {
				echo "DEBUG {crc_evaluation::constructor}: The class \"crc_evaluation\" was successfuly created. <br>";
				echo "DEBUG {crc_evaluation::constructor}: Running in debug mode. <br>";
			}

		}

		function fn_getcategory($db, $category) {
			//***************************************************************
			// Get the questions for a specific category
			// Reads continuosly from database without closing the connection
			//and without freeing the resource
			//***************************************************************						
			if ($this->_DEBUG) {
				echo "DEBUG {crc_evaluation::fn_getsection}: Retreiving questions for category: '" . $category . "'. <br>";
			}
			
			if (!isset($category) || ($category == "")) {
				$this->lasterrmsg = "Invalid input";
				return null;
			}

			$result = null;
			if ($db->m_mysqlhandle != false) {			
				$this->m_sql = 'select * ' .
								'from ' . MYSQL_FEEDBACK_QUESTIONS_TBL . 
								' where (feedback_questions_active = 0) and ' .
								'(feedback_questions_category = "' . strtoupper($category) . '") ' . 
												'order by feedback_questions_sequence asc';
				$resource = $db->fn_runsql(MYSQL_DB, $this->m_sql);
				if (is_resource($resource) && mysql_num_rows($resource) > 0) {
					$result = $resource;
				} else {
					$this->lasterrmsg = mysql_error();
					$this->lasterrnum = mysql_errno();
					if ($this->_DEBUG) {
						echo "DEBUG {crc_evaluation::fn_getsection}: '" . $this->lasterrmsg . "'.<br>";
					}
				}											
			} else {
				$this->lasterrmsg = mysql_error();
				$this->lasterrnum = mysql_errno();
				if ($this->_DEBUG) {
					echo "DEBUG {crc_evaluation::fn_getsection}: '" . $this->lasterrmsg . "'.<br>";
				}
			}
			return $result;			
		}

		function fn_getquestions() {
			//******************************************
			// Get the questions to generate the evaluation form
			//******************************************						
			if ($this->_DEBUG) {
				echo "DEBUG {crc_evaluation::fn_getquestions}: Retreiving feedback questions<br>";
			}

			$this->m_data = null;
			$db = new crc_mysql($this->_DEBUG);
			$dbhandle = $db->fn_connect();
			if ($dbhandle != false) {
				$this->m_sql = 'select distinct feedback_questions_category ' . 
								'from ' . MYSQL_FEEDBACK_QUESTIONS_TBL . 
								' where (feedback_questions_active = 0)';
				$categories = $db->fn_runsql(MYSQL_DB, $this->m_sql);
				if (is_resource($categories) && (mysql_num_rows($categories) > 0)) {				
					if ($this->_DEBUG) {
						echo "DEBUG {crc_evaluation::fn_getquestions}: Number of sections found are: " . mysql_num_rows($categories) . ". <br>";
					}					
					$index = 0;					
					while ($category = mysql_fetch_array($categories)) {
						$questions = $this->fn_getcategory($db, $category[0]);
						if ($questions == null) {
							if ($this->_DEBUG) {
								echo 'DEBUG {crc_evaluation::fn_getquestions}: Cannot get category' . $category[0] . '<br>';
								echo 'DEBUG {crc_evaluation::fn_getquestions}: ' . $this->lasterrmsg . '<br>';
							}
							continue;
						}						
						while ($row = mysql_fetch_array($questions)) {					
							$this->m_data[$index] = $row;
							if ($this->_DEBUG) {							
								switch(strtolower($this->m_data[$index][3])) {								
									case "option":
										$form = '<input type="radio" name="' . $this->m_data[$index][0] . '" value="1">';
										$form = $form . '<input type="radio" name="' . $this->m_data[$index][0] . '" value="2">';
										$form = $form . '<input type="radio" name="' . $this->m_data[$index][0] . '" value="3">';
										$form = $form . '<input type="radio" name="' . $this->m_data[$index][0] . '" value="4">';
										$form = $form . '<input type="radio" name="' . $this->m_data[$index][0] . '" value="5">';
										$form = $form . '<input name="' . $this->m_data[$index][0] . 'text" size="40" width="80">';
										break;										
									case "comment":
										$form = '<textarea name="' . $this->m_data[$index][0] . '" col="40" row="3" wrap="soft"></textarea>';
										break;
								}							
								echo 'DEBUG {crc_evaluation::fn_getquestions}: Question found: "' . $this->m_data[$index][1] . '" ' . $form . '<br>';
							}												
							$index = $index + 1;
						}						
						$db->fn_freesql($questions);					
					}
					if ($this->_DEBUG) {
						echo 'DEBUG {crc_evaluation::fn_getquestions}: Total questions are: ' . count($this->m_data) . '<br>';
					}
					$db->fn_freesql($categories);
				} else {
					$this->lasterrnum = ERR_FEEDBACK_QUESTIONS_NOSECTIONS_NUM;
					$this->lasterrmsg = ERR_FEEDBACK_QUESTIONS_NOSECTIONS_DESC;					
					if ($this->_DEBUG) {
						echo 'ERROR {crc_evaluation::fn_getquestions}: The sql command returned nothing. <br>';
						echo 'ERROR {crc_evaluation::fn_getquestions}: Error number: ' . $this->lasterrnum . '. <br>';
						echo 'ERROR {crc_evaluation::fn_getquestions}: Error description: ' . $this->lasterrmsg . '. <br>';
					}
				}
				$db->fn_disconnect();				
			} else {
				$this->lasterrmsg = mysql_error();
				$this->lasterrnum = mysql_errno();
				if ($this->_DEBUG) {
					echo 'ERROR {crc_evaluation::fn_getquestions}: ' . $this->lasterrmsg . '.<br>';
				}
			}
			return $this->m_data;
		}

		function fn_setquestions($profileid, $post) {
			//*****************************************
			// Update the users evaluation information
			//*****************************************
			if ($this->_DEBUG) {
				echo "DEBUG {crc_evaluation::fn_setquestions}: Updating the user evaluation information.<br>";
			}
			
			$this->lasterrmsg = "";//reset error message
			if(!isset($post['schedule_id']) || ($post['schedule_id'] == "") ||
			    ($profileid == null) || ($profileid == "")) {
				$this->lasterrmsg = "Invalid input";
				return false;
			}

			$result = false;
			$db = new crc_mysql($this->_DEBUG);
			$dbhandle = $db->fn_connect();
			if ($dbhandle != false) {

				$this->m_sql = 'update ' . MYSQL_STUDENT_SCHEDULE_TBL .
									' SET student_schedule_questions = 0 ' .
									'where (student_schedule_id = ' . $post['schedule_id'] . ')'; 
				$result = $db->fn_runsql(MYSQL_DB, $this->m_sql);
				if (mysql_affected_rows() <= 0)
				{
					if ($this->_DEBUG) {
						echo 'ERROR {crc_evaluation::fn_setquestions}: Cannot update student schedule for student schedule id ' . $post['schedule_id'] . '.<br>';
					}
					$this->lasterrmsg = "Cannot update student schedule";
					return false;
				}				
				$this->m_sql = 'insert into ' . MYSQL_FEEDBACK_TBL .
								' (feedback_profile_id, feedback_schedule_id) ' .
								' values (' . $profileid . ', ' . $post['schedule_id'] . ')'; 					
				$db->fn_runsql(MYSQL_DB, $this->m_sql);
				if (mysql_affected_rows() > 0) {
					$this->m_sql = 'select * from ' . MYSQL_FEEDBACK_TBL .
									' where (feedback_profile_id = ' . $profileid . ') and ' .
									'(feedback_schedule_id = ' . $post['schedule_id'] . ')';
					$resource = $db->fn_runsql(MYSQL_DB, $this->m_sql);
					if (!is_resource($resource))
					{
						if ($this->_DEBUG) {
							echo 'ERROR {crc_evaluation::fn_setquestions}: Cannot select feedback table.<br>';
						}
						$this->lasterrmsg = "Cannot select feedback table";
						return false;
					}
					$feedback = mysql_fetch_array($resource);					
					for ($i = 1; $i <= count($_SESSION['evaluation']); $i++) {
						$question = $_SESSION['evaluation'][$i - 1];
						$answers = $post;
						$answeri = $question[0] . 'name';
						$answer = $post[$answeri];
						if (!isset($answer)) {
							$answer = 0;
						}
						if (strtoupper($question[3]) == "OPTION") {
							$commenti = $question[0] . 'comment';
							$comment = $post[$commenti];
							$this->m_sql = 'insert into ' . MYSQL_FEEDBACK_ANSWERS_TBL .
											' (feedback_answers_feedback_id, feedback_answers_questions_id, ' .
											' feedback_answers_answer, feedback_answers_comments) ' .
											' values (' . $feedback[0] . ', ' . $question[0] . ', ' . $answer . ', "' . $comment . '")'; 
						} else {						
							$this->m_sql = 'insert into ' . MYSQL_FEEDBACK_ANSWERS_TBL .
											' (feedback_answers_feedback_id, feedback_answers_questions_id, ' .
											' feedback_answers_answer, feedback_answers_comments) ' .
											' values (' . $feedback[0] . ', ' . $question[0] . ', "0", "' . $answer . '")'; 
						
						}
						$result = $db->fn_runsql(MYSQL_DB, $this->m_sql);
						if ($result == false) {
							if ($this->_DEBUG) {
								echo 'ERROR {crc_evaluation::fn_setquestions}: Could not insert feedback information.<br>';
							}
							$this->lasterrmsg = mysql_error();
							$this->lasterrnum = mysql_errno();
							$db->fn_freesql($resource);
							return $result;
						}
					}		
					$db->fn_freesql($resource);
				} else {
					$this->lasterrnum = ERR_FEEDBACK_ADD_NUM;
					$this->lasterrmsg = ERR_FEEDBACK_ADD_DESC;
					if ($this->_DEBUG) {
						echo 'ERROR {crc_evaluation::fn_setquestions}: Could not add feedback information. <br>';
						echo 'ERROR {crc_evaluation::fn_setquestions}: Error number: ' . $this->lasterrnum . '. <br>';
						echo 'ERROR {crc_evaluation::fn_setquestions}: Error description: ' . $this->lasterrmsg . '. <br>';
					}
				}
				$db->fn_disconnect();				
			} else {
				$this->lasterrmsg = mysql_error();
				$this->lasterrnum = mysql_errno();
			}			
			return $result;
		}
		
		function fn_getanswers($scheduleid) {
			//***********************************************
			//Get the answers to questions for a given course
			//***********************************************
			if ($this->_DEBUG) {
				echo "DEBUG {crc_evaluation::fn_getanswers}: Getting the user evaluation information for the schedule id " . $scheduleid . ".<br>";
			}
			
			$db = new crc_mysql($this->_DEBUG);
			$dbhandle = $db->fn_connect();
			$this->m_data = null;
			if ($dbhandle != false) {
				//get student_schedule_id				
				$this->m_sql = 'select student_schedule_id, student_schedule_profile_id ' . 
								'from ' . MYSQL_STUDENT_SCHEDULE_TBL . 
								' where (student_schedule_schedule_id = "' . $scheduleid . '")';
				$studentscheduleid = $db->fn_runsql(MYSQL_DB, $this->m_sql);
				if (mysql_num_rows($studentscheduleid) > 0) {					
					$studidx = 0;
					$ansidx = 0;
					while ($ssid = mysql_fetch_array($studentscheduleid)) {
						//get student name
						$name = $this->fn_getprofilename($db, $ssid[1]);
						//get feedback_id
						$this->m_sql = 'select feedback_id ' . 
								'from ' . MYSQL_FEEDBACK_TBL . 
								' where (feedback_schedule_id = "' . $ssid[0] . '")';
						$feedbackid = $db->fn_runsql(MYSQL_DB, $this->m_sql);
						if (mysql_num_rows($feedbackid) > 0) {
							$fid = mysql_fetch_row($feedbackid);//a single feedback id should correspond to a student schedule id
							//get questions, answers and comments
							$this->m_sql = 'select feedback_answers_questions_id, ' .
												'feedback_answers_answer, feedback_answers_comments ' . 
												'from ' . MYSQL_FEEDBACK_ANSWERS_TBL . 
												' where (feedback_answers_feedback_id = "' . $fid[0] . '")';
							$answers = $db->fn_runsql(MYSQL_DB, $this->m_sql);
							if (mysql_num_rows($answers) > 0) {
								$ansidx = 0;
								while ($ans = mysql_fetch_array($answers)) {
									if ($studidx == 0) {
										
										$this->m_data[$ansidx]['ans'] = 0;
										$this->m_data[$ansidx]['comm'] = "";
										$this->m_data[$ansidx]['que'] = $this->fn_getquestion($db, $ans[0]);
									}
									$this->m_data[$ansidx]['ans'] += $ans[1];
									if ($ans[2] != "") {
										$this->m_data[$ansidx]['comm'] .= '<br>&nbsp;&nbsp;&nbsp;' . $name . ': ' . $ans[2];
									}									
									$ansidx++; 
								}
								$studidx++;
							} else if ($this->_DEBUG) {
								echo "DEBUG {crc_evaluation::fn_getanswers}: Cannot get the answers.<br>";
							}							
							$db->fn_freesql($answers);
						} else if ($this->_DEBUG) {
							echo "DEBUG {crc_evaluation::fn_getanswers}: Cannot get feedback ID.<br>";
						}
						$db->fn_freesql($feedbackid);						
					}
					for ($i = 0; $i < $ansidx; $i++) {
						$this->m_data[$i]['ans'] =  number_format(round($this->m_data[$i]['ans']/$studidx, 2), 2);//take the mean evaluation
					}
				} else if ($this->_DEBUG) {
					echo "DEBUG {crc_evaluation::fn_getanswers}: Cannot get the student schedule.<br>";
				}
				$db->fn_freesql($studentscheduleid);
			} else 	if ($this->_DEBUG) {
				echo "DEBUG {crc_evaluation::fn_getanswers}: Cannot connect to MySQL database.<br>";
			}
			$db->fn_disconnect();
			return $this->m_data;
		}
		
		function fn_getquestion($db, $questionid) {
			//*****************************************************
			//Get the question string identified by the question id
			//*****************************************************
			if ($this->_DEBUG) {
				echo "DEBUG {crc_evaluation::fn_getquestion}: Getting the question string for the question id " . $questionid . ".<br>";
			}
			
			//check input
			if (!isset($db, $questionid) || ($questionid == "")) {
				return null;
			}
			
			$this->m_question = null;
			if ($db->m_mysqlhandle != false) {
				$this->m_sql = 'select feedback_questions_question ' . 
								'from ' . MYSQL_FEEDBACK_QUESTIONS_TBL . 
								' where (feedback_questions_id = "' . $questionid . '")';
				$result = $db->fn_runsql(MYSQL_DB, $this->m_sql);
				if (is_resource($result)) {
					if (mysql_num_rows($result) > 0) {
						$row = mysql_fetch_row($result);
						$this->m_question = $row[0];
						if ($this->_DEBUG) {
							echo 'ERROR {crc_evaluation::fn_getquestion}: ' . $this->m_question . '<br>';
						}
					} else if ($this->_DEBUG) {
						echo 'ERROR {crc_evaluation::fn_getquestion}: The sql command returned nothing. <br>';
					}
					$db->fn_freesql($result);
				}
			}
			return $this->m_question;
		}
		
		function fn_getprofilename($db, $profileid) {
			//**********************************************
			// Get profile name, given the profile id
			//**********************************************
			if ($this->_DEBUG) {
				echo "DEBUG {crc_evaluation::fn_getprofilename}: Getting the first and last name for the profile id " . $profileid . ".<br>";
			}
			
			//check input
			if (!isset($db, $profileid) || ($profileid == "")) {
				return null;
			}
			
			$this->m_profilename = null;
			if ($db->m_mysqlhandle != false) {
				$this->m_sql = 'select profile_firstname, profile_lastname ' . 
								'from ' . MYSQL_PROFILES_TBL . 
								' where (profile_id = "' . $profileid . '")';
				$result = $db->fn_runsql(MYSQL_DB, $this->m_sql);
				if (is_resource($result)) {
					if (mysql_num_rows($result) > 0) {
						$row = mysql_fetch_row($result);
						$this->m_profilename = $row[1] . ", " . $row[0];
						if ($this->_DEBUG) {
							echo 'ERROR {crc_evaluation::fn_getprofilename}: ' . $this->m_profilename . '. <br>';
						}
					} else if ($this->_DEBUG) {
						echo 'ERROR {crc_evaluation::fn_getprofilename}: The sql command returned nothing. <br>';
					}
					$db->fn_freesql($result);
				}
			}
			return $this->m_profilename;
		}
		
		function fn_getcoursename($scheduleid) {
			//*************************************
			// Get course from from the schedule id
			//*************************************
			if ($this->_DEBUG) {
				echo "DEBUG {crc_evaluation::fn_getcoursename}: Getting the course name for the schedule id " . $scheduleid . ".<br>";
			}
			
			$db = new crc_mysql($this->_DEBUG);
			$dbhandle = $db->fn_connect();
			$this->m_data = null;
			if ($dbhandle != false) {
				$this->m_sql = 'select schedule_course_id ' . 
								'from ' . MYSQL_SCHEDULE_TBL . 
								' where (schedule_id = "' . $scheduleid . '")';
				$result = $db->fn_runsql(MYSQL_DB, $this->m_sql);
				if (mysql_num_rows($result) > 0) {
					$row = mysql_fetch_row($result);
					$this->m_sql = 'select course_name ' . 
								'from ' . MYSQL_COURSES_TBL . 
								' where (course_id = "' . $row[0] . '")';
					$result = $db->fn_runsql(MYSQL_DB, $this->m_sql);
					if (mysql_num_rows($result) > 0) {
						$row = mysql_fetch_row($result);
						$this->m_data = $row[0];
					}
				}
				$db->fn_freesql($result);
			}
			$db->fn_disconnect();
			return $this->m_data;
		}
		
		function fn_getattendance($scheduleid) {
			//***************************************************
			// Get course attendace for the specified schedule ID
			//***************************************************
			if ($this->_DEBUG) {
				echo "DEBUG {crc_evaluation::fn_getattendance}: Getting the course attendance for the schedule id " . $scheduleid . ".<br>";
			}
			
			$db = new crc_mysql($this->_DEBUG);
			$dbhandle = $db->fn_connect();
			$this->m_data = null;
			$this->m_studentnb = 0;
			if ($dbhandle != false) {
				$this->m_sql = 'select student_schedule_id ' . 
								'from ' . MYSQL_STUDENT_SCHEDULE_TBL . 
								' where (student_schedule_schedule_id = "' . $scheduleid . '")';
				$std_result = $db->fn_runsql(MYSQL_DB, $this->m_sql);
				if (mysql_num_rows($std_result) > 0) {
					while ($ssid = mysql_fetch_array($std_result)) {//student schedule ID
						$this->m_studentnb++;
						$this->m_sql = 'select attendance_date_id, attendance_check ' .
									'from ' . MYSQL_ATTENDANCE_TBL . 
									' where (attendance_student_schedule_id = "' . $ssid[0] . '")';
						$att_result = $db->fn_runsql(MYSQL_DB, $this->m_sql);
						if (mysql_num_rows($att_result) > 0) {
							while ($row = mysql_fetch_array($att_result)) {								
								$key = $this->fn_getdate($db, $row[0]);
								if ($key == null) {
									if ($this->_DEBUG) {
										echo "DEBUG {crc_evaluation::fn_getattendance}: Cannot get the date for the given date id " . $att_result[0] . ".<br>";
									}
									return null;
								}
								if(strtoupper($row[1]) == "P") {
									if (!isset($this->m_data[$key])) {
										$this->m_data[$key] = 1;
									} else {
										$this->m_data[$key]++;
									}
								} else {
									if (!isset($this->m_data[$key])) {
										$this->m_data[$key] = 0;
									}
								}
							}
						}
						$db->fn_freesql($att_result);
					}
				}
				$db->fn_freesql($std_result);
			}
			$db->fn_disconnect();
			return $this->m_data;
		}
		
		function fn_getdate($db, $dateid) {
			//************************
			// Get date, given date ID
			//************************
			if ($this->_DEBUG) {
				echo "DEBUG {crc_evaluation::fn_getdate}: Getting the date for the date id " . $dateid . ".<br>";
			}
			
			$this->m_date = null;
			if ($db->m_mysqlhandle != false) {
				$this->m_sql = 'select date_day, date_month, date_year ' . 
								'from ' . MYSQL_DATE_TBL . 
								' where (date_id = "' . $dateid . '")';
				$result = $db->fn_runsql(MYSQL_DB, $this->m_sql);
				if (mysql_num_rows($result) > 0) {
					$row = mysql_fetch_row($result);
					$this->m_date = $row[0] . '-' . $row[1] . '<br>-' . $row[2];
				}
				$db->fn_freesql($result);
			}
			return $this->m_date;
		}
	}
?>
