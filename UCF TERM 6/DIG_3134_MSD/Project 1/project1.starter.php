<?php
 session_start();
 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Project 1 Starter Kit</title>
</head>

<body>
<form method='post'>

<?php
/////////////////////
// This is the starter kit for Project 1 in DIG 3134 - Spring 2011
//	J. Michael Moshell - UCF Digital Media
/////////////////////
	
	$nextaction=$_POST['dowhat'];
	
	//////////////////////////////////////
	if(!$nextaction) // If nobody clicked a button to get to this page,
	{								  
	/////////////
	// SCREEN 1
	/////////////
		$target=rand(1,100);
		$_SESSION['targetvalue']=$target; // so we will have it later

		print "<p>This is a number guessing game.</p>";
		print "<p>We will cheat to make it easier for you, ".
			"by printing out the number you are trying to guess.</p>";
		print "<p>target=$target. Enter your first guess (an integer between 1-100, inclusive.)<br />";
		print "<input type='text' name='guessvalue'> <br />";
		
		print "<input type='submit' name='dowhat' value='GUESS'>";
		print "<input type='submit' name='dowhat' value='GIVE UP'>";

	}
	
	else if ($nextaction=="GIVE UP")
	{
		print "Quitter!";
		exit; // so as not to draw buttons any more.
	}
	else if ($nextaction=="GUESS")
	{
		####################
		# SCREEN TWO
		####################
		
		$guess=$_POST['guessvalue'];
		$target=$_SESSION['targetvalue'];
				
		if ($target==$guess)
		{
			print "You got it! Yaa hoo!";
			exit;// we're done
		}
		else
		{
			if ($target>$guess)
				print "Too low.";
			else
				print "too high.";
				
			print "Enter your next guess (1-100)<br />";
			print "<input type='text' name='guessvalue'><br />";
		}
		
		print "<input type='submit' name='dowhat' value='GUESS'>";
		print "<input type='submit' name='dowhat' value='GIVE UP'>";

	}
	else
		print "Error 100: nextaction=$nextaction, but I don't know what to do.";
?>
</form>
</body>
</html>

