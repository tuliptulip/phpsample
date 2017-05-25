//---------------------------------------------------
// 	Function fShowText
//	==================
//
//	Purpose::	To show text on the browsers
//			Status Bar.
//	Author:		Shaffin N. Bhanji
//	Date:		August 26, 1998
//---------------------------------------------------

function fn_showstatus(text) {
	window.status = text;
	setTimeout("window.status=''", 5000);
}

function verify(page) {
	if (page == "register") {
		if (register.username.value == "") {
			alert("Please enter a valid 'Username'!");
			register.username.focus();
		} else if (register.password.value == "") {
			alert("Please enter a valid 'Password'!");
			register.lname.focus();
		} else if (register.fname.value == "") {
			alert("Please enter a valid 'Given Name'!");
			register.fname.focus();
		} else if (register.lname.value == "") {
			alert("Please enter a valid 'Surname'!");
			register.lname.focus();
		} else if (register.email.value == "") {
			alert("Please enter a valid 'Email'!");
			register.email.focus();
		} else if (register.day.value == "00") {
			alert("Please enter a valid 'Day'!");
			register.day.focus();
		} else if (register.month.value == "00") {
			alert("Please enter a valid 'Month'!");
			register.month.focus();
		} else if (register.year.value == "0000") {
			alert("Please enter a valid 'Year'!");
			register.year.focus();
		} else if (register.add1.value == "") {
			alert("Please enter a valid 'Address'!");
			register.add1.focus();
		} else if (register.city.value == "") {
			alert("Please enter a valid 'City'!");
			register.city.focus();
		} else if (register.pc.value == "") {
			alert("Please enter a valid 'Postal Code'!");
			register.pc.focus();
		} else if (register.lcode.value == "") {
			alert("Please enter a valid 'Phone Code'!");
			register.lcode.focus();
		} else if (register.lprefix.value == "") {
			alert("Please enter a valid 'Phone Prefix'!");
			register.lprefix.focus();
		} else if (register.lpostfix.value == "") {
			alert("Please enter a valid 'Phone Postfix'!");
			register.lpostfix.focus();
		} else {
			register.submit();
		}
	} else if (page == "login") {
		if (login.username.value == "") {
			alert("Please enter a valid 'Username'!");
			login.username.focus();
		} else if (login.password.value == "") {
			alert("Please enter a valid 'Password'!");
			login.password.focus();
		} else {
			login.submit();
		}
	} else if (page == "profile") {
		if (profile.username.value == "") {
			alert("Please enter a valid 'Username'!");
			profile.username.focus();
		} else if (profile.password.value == "") {
			alert("Please enter a valid 'Password'!");
			profile.lname.focus();
		} else if (profile.fname.value == "") {
			alert("Please enter a valid 'Given Name'!");
			profile.fname.focus(); 
		} else if (profile.lname.value == "") {
			alert("Please enter a valid 'Surname'!");
			profile.lname.focus();
		} else if (profile.email.value == "") {
			alert("Please enter a valid 'Email'!");
			profile.email.focus();
		} else if (profile.day.value == "") {
			alert("Please enter a valid 'Day'!");
			profile.day.focus();
		} else if (profile.month.value == "") {
			alert("Please enter a valid 'Month'!");
			profile.month.focus();
		} else if (profile.year.value == "") {
			alert("Please enter a valid 'Year'!");
			profile.year.focus();
		} else if (profile.add1.value == "") {
			alert("Please enter a valid 'Address'!");
			profile.add1.focus();
		} else if (profile.city.value == "") {
			alert("Please enter a valid 'City'!");
			profile.city.focus();
		} else if (profile.pc.value == "") {
			alert("Please enter a valid 'Postal Code'!");
			profile.pc.focus();
		} else if (profile.lcode.value == "") {
			alert("Please enter a valid 'Phone Code'!");
			profile.lcode.focus();
		} else if (profile.lprefix.value == "") {
			alert("Please enter a valid 'Phone Prefix'!");
			profile.lprefix.focus();
		} else if (profile.lpostfix.value == "") {
			alert("Please enter a valid 'Phone Postfix'!");
			profile.lpostfix.focus();
		} else {
			profile.submit();
		}
	} else if (page == "editprofile") {
		if (editprofile.username.value == "") {
			alert("Please enter a valid username");
			editprofile.username.focus();
		} else if (editprofile.password.value == "") {
			alert("Please enter a valid password");
			editprofile.password.focus();
		} else if (editprofile.fname.value == "") {
			alert("Please enter a valid first name");
			editprofile.fname.focus();
		} else if (editprofile.lname.value == "") {
			alert("Please enter a valid last name");
			editprofile.lname.focus();
		} else if (editprofile.email.value == "") {
			alert("Please enter a valid e-mail");
			editprofile.email.focus();
		} else if (editprofile.day.value == "") {
			alert("Please enter a valid day");
			editprofile.day.focus();
		} else if (editprofile.month.value == "") {
			alert("Please enter a valid month");
			editprofile.month.focus();
		} else if (editprofile.year.value == "") {
			alert("Please enter a valid year");
			editprofile.year.focus();
		} else if (editprofile.add1.value == "") {
			alert("Please enter a valid address");
			editprofile.add1.focus();
		} else if (editprofile.city.value == "") {
			alert("Please enter a valid city");
			editprofile.city.focus();
		} else if (editprofile.province.value == "") {
			alert("Please enter a valid province");
			editprofile.province.focus();
		} else if (editprofile.country.value == "") {
			alert("Please enter a valid country");
			editprofile.country.focus();
		} else if (editprofile.pc.value == "") {
			alert("Please enter a valid postal code");
			editprofile.pc.focus();
		} else if ((editprofile.lcode.value == "") || (editprofile.lprefix.value == "") || (editprofile.lpostfix.value == "")) {
			alert("Please enter a valid phone");
			editprofile.lprefix.focus();			
		} else {
			editprofile.submit();
		}
	} else if (page == "adminaddcourse") {
		if (adminaddcourse.cname.value == "") {
			alert("Please enter a valid 'Course Name'!");
			adminaddcourse.cname.focus();
		} else if (adminaddcourse.sday.value == "") {
			alert("Please enter a valid 'Start Day'!");
			adminaddcourse.sday.focus();
		} else if (adminaddcourse.smonth.value == "") {
			alert("Please enter a valid 'Start Month'!");
			adminaddcourse.smonth.focus();
		} else if (adminaddcourse.syear.value == "") {
			alert("Please enter a valid 'Start Year'!");
			adminaddcourse.syear.focus();
		} else if (adminaddcourse.eday.value == "") {
			alert("Please enter a valid 'End Day'!");
			adminaddcourse.eday.focus();
		} else if (adminaddcourse.emonth.value == "") {
			alert("Please enter a valid 'End Month'!");
			adminaddcourse.emonth.focus();
		} else if (adminaddcourse.eyear.value == "") {
			alert("Please enter a valid 'End Year'!");
			adminaddcourse.eyear.focus();
		}else if (adminaddcourse.daytime.value == "") {
			alert("Please enter a valid 'Day Time'!");
			adminaddcourse.daytime.focus();
		} else if (adminaddcourse.roomname.value == "") {
			alert("Please enter a valid 'Room'!");
			adminaddcourse.room.focus();
		} else {
			adminaddcourse.submit();
		}
	} else if (page == "editcourse") {
		if (editcourse.cname.value == "") {
			alert("Please enter a valid 'Course Name'!");
			editcourse.cname.focus();
		} else if (editcourse.sday.value == "") {
			alert("Please enter a valid 'Start Day'!");
			editcourse.sday.focus();
		} else if (editcourse.smonth.value == "") {
			alert("Please enter a valid 'Start Month'!");
			editcourse.smonth.focus();
		} else if (editcourse.syear.value == "") {
			alert("Please enter a valid 'Start Year'!");
			editcourse.syear.focus();
		} else if (editcourse.eday.value == "") {
			alert("Please enter a valid 'End Day'!");
			editcourse.eday.focus();
		} else if (editcourse.emonth.value == "") {
			alert("Please enter a valid 'End Month'!");
			editcourse.emonth.focus();
		} else if (editcourse.eyear.value == "") {
			alert("Please enter a valid 'End Year'!");
			editcourse.eyear.focus();
		}else if (editcourse.daytime.value == "") {
			alert("Please enter a valid 'Day Time'!");
			editcourse.daytime.focus();
		} else if (editcourse.roomname.value == "") {
			alert("Please enter a valid 'Room'!");
			editcourse.room.focus();
		} else {
			editcourse.submit();
		}		
	} else if (page == "adminaddstudent") {
		if (adminaddstudent.fname.value == "") {
			alert("Please enter a valid 'Given Name'!");
			adminaddstudent.fname.focus();
		} else if (adminaddstudent.lname.value == "") {
			alert("Please enter a valid 'Surname'!");
			adminaddstudent.fname.focus();
		} else {
			adminaddcourse.submit();
		}
	} else if (page == "dbsetup") {
		if (dbsetup.database.value == "") {
			alert("Please enter a database name");
			dbsetup.database.focus();
		} else if (dbsetup.password.value == "") {
			alert("Please enter a password for the root user of MySQL server");
			dbsetup.password.focus();
		} else if (dbsetup.username.value == "") {
			alert("Please enter the username of FreeSMS database");
			dbsetup.username.focus();
		} else if (dbsetup.userpassword.value == "") {
			alert("Please enter the password for user of FreeSMS database");
			dbsetup.userpassword.focus();
		} else {
			dbsetup.submit();
		}
	} else {
		alert("unknown/unhandled page");
	}	
}
