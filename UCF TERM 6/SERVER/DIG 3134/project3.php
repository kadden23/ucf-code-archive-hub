<?php

session_start();

$Login="ky775779"; 
$Password='$ull3y**';
$Gridsize=7;
$Cellwidth=250/$Gridsize;

define('BLACK','#000000');
define('GOLD','#AAAA33');
define('BLUE','#0000FF');
define('WHITE','#FFFFFF');
define('GTEAM','GOLD');
define('BTEAM','BLACK');


function makeheader()
{ global $Cellwidth;

	print '
	  <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Project 3 - Wall War -Kyle Cartechine</title>';


	print "	<style type='text/css'>
	
			table.mtable
			{
				margin-left:auto;
				margin-right:auto;
			}
			.mtable td 
			{
				width:$Cellwidth"."px; height:$Cellwidth"."px;
				
				color:WHITE;
			}
		</style>";

print '</head>
<body><form method="post">
';
}

function preparegrid()
{global $Grid, $Gridsize;

	for ($y=0; $y<=$Gridsize+1; $y++)
	{
		for ($x=0; $x<=$Gridsize+1; $x++)
		{
			if ($x==0) // left margin
			{
				if ($y==($Gridsize+1)/2)
					$Grid[$x][$y]=BLACK;
				else
					$Grid[$x][$y]=BLUE;
			}
			else if ($y==0) // top margin
			{
				if ($x==($Gridsize+1)/2)
					$Grid[$x][$y]=GOLD;
				else
					$Grid[$x][$y]=BLUE;
			}
			else if (($x==$Gridsize+1)||($y==$Gridsize+1))
			{	// right margin and bottom margin
				$Grid[$x][$y]=BLUE;
			}
			else	// fill the dude with white!
				$Grid[$x][$y]=WHITE;
				
		} # x loop
	} # y loop
} # preparegrid

function drawgrid()
{global $Grid,$Gridsize;

	$result='<table class="mtable" border=1>';
	for ($y=0; $y<=$Gridsize+1; $y++)
	{
		$result.="<tr>";
		for ($x=0; $x<=$Gridsize+1; $x++)
		{
			if ($x==0 && ($y>0 && $y<8)) $char=$y;			
			else if ($y==0 && ($x>0 && $x<8)) $char=chr($x+64);
			else if(($x==$Gridsize+1)||($y==$Gridsize+1)||($x==0 && $y==0))  $char="&nbsp;";			
			else $char="<input type='radio' name='fillhere' value='$x.$y'";
							
			$color=$Grid[$x][$y];
			$result.="<td align='center' style='background-color:$color'>$char</td>";
		} # x loop
		$result.="</tr>";
	} # y loop
	$result.="</table>";
	print $result;
} #drawgrid

function drawinputs()
{
	print "X position(letter):<input type='text' size=2 name='xinput'><br />
	Y position(number):<input type='text' size=2 name='yinput'><br />
	Or Just Type X and Y:<input type='text' size=4 name='fullinput'> <br />
	Color:<input type='text' size=2 name='colorinput'> <br />
	<input type='submit' name='action' value='GO'>	
	<input type='submit' name='action' value='CLEAR'>
	<input type='submit' name='action' value='LIST MOVES'>
	<input type='submit' name='action' value='UNDO'>
	<input type='submit' name='action' value='BLACK'>
	<input type='submit' name='action' value='GOLD'>";

}

function xinputConvert()
{global $x;
	$a = $x;
	$x = ord($a)-64;
	if ($x>8)
	$x = $x-32;
	return $x;
}

function addhistory($x,$y,$color)
{global $Login, $Password;
	$connection = mysql_connect('localhost', $Login,$Password)or print "main connect failed";
	mysql_select_db('ky775779', $connection) or print "main select failed";
	
	$q="INSERT INTO history VALUES (null, '$x', '$y','$color')";
	$result = mysql_query ($q, $connection) or print "query '$q' failed";
}

#showhistory:
function showhistory()
{global $Login, $Password;
	$connection=mysql_connect('localhost',$Login,$Password) or print "main connect failed";
	mysql_select_db('ky775779',$connection) or print "main select failed";
	
	$q="SELECT * FROM history WHERE 1";	
	$result = mysql_query ($q, $connection) or print "query '$q' failed";
	
	$report= "<table>";
	while($row=mysql_fetch_array($result))
							
	{	
			$value0=$row[0];
			$value1=$row[1];
			$value2=$row[2];			
							
			$report.="<tr><td align='center'>$value0</td><td align='center'>$value1</td>".
					"<td>$value2</td></tr>";
	}
	
	$report.="</table>";
	if (!$value0)
		print "The history is empty!";
		
	print $report;
} 

function last_step()
{global $Login, $Password;
	$connection=mysql_connect("localhost",$Login,$Password) or print 'main connect failed';
	mysql_select_db('ky775779',$connection) or print "main select failed";
	$q="SELECT MAX(Step) FROM history";
	$result = mysql_query ($q, $connection) or print "query '$q' failed";
	$row=mysql_fetch_array($result);
	$laststep=$row[0];
	return $laststep;
}

function removelasthistory()
{global $Login, $Password;
	$laststep=last_step(); // most recent turn will have largest number.
	$connection=mysql_connect("localhost",$Login,$Password) or print 'main connect failed';
	mysql_select_db('ky775779',$connection) or print "main select failed";
	$q="DELETE FROM history WHERE Step=$laststep";
	$result = mysql_query ($q, $connection) or print "query '$q' failed";
}

function undoLastMove()
{global $Login, $Password;
	$connection=mysql_connect("localhost",$Login,$Password) or print 'main connect failed';
	mysql_select_db('ky775779',$connection) or print "main select failed";
	$q="SELECT MAX(Step) FROM history";
	$result = mysql_query ($q, $connection) or print "query '$q' failed";
	$row=mysql_fetch_array($result);
	$laststep=$row[0];
	$lastX="SELECT X FROM history WHERE Step=$laststep";
	$lastY="SELECT Y FROM history WHERE Step=$laststep";
	$lastC="SELECT Color FROM history WHERE Step=$laststep";
	$x=$lastX;
	$y=$lastY;
	$color=WHITE;
	$Grid[$x][$y]=$color;
	
		
	removelasthistory();	
}

function whosturn()
{
	$current=$_SESSION['whosturn'];
		
		if (!$current)
			$_SESSION['whosturn']=BTEAM;
		else if($current == BTEAM)
			$_SESSION['whosturn']=GTEAM;
		else if($current == GTEAM)
			$_SESSION['whosturn']=BTEAM;
			
		print'<p style="text-align:center; font-size:20px; font-family:Tahoma, Geneva, sans-serif;">'."$current team must play.".'</p>';
}

/////// MAIN PROGRAM ///////
	makeheader();
	$Grid=$_SESSION['Grid'];

	$action=$_POST['action'];
	
	if ((!$action)||($action=='CLEAR'))
	{
		preparegrid();
		print'<p style="text-align:center; font-size:20px; font-family:Tahoma, Geneva, sans-serif;">The starting team is BLACK.</p>';
	}
	else if($action=="GO")
	{
		if(!$_POST['xinput'])
		{
			$input=$_POST['fullinput'];
			$first=substr($input,0,1);
			$test=ord($first);
			
			if( strlen("$input")>2  || $test<=71 || $test>=105)
				print"<p>Your input was invalid, please enter a grid combo like 'c3'.Don't forget to enter a color (b or y).</p>";
						
			$rest=substr($input,1);
			$num=ord($first)-96;
			$x=$num;
			$y=$rest;
			$c=$_POST['colorinput'];
			if ($c=='g')
				$color=GOLD;
			else if ($c=='b')
				$color=BLACK;
			else
				$color=WHITE;
			$Grid[$x][$y]=$color;
			addhistory($x,$y,$_POST['colorinput'] );
			
			whosturn();
		}
		else
		{
			$x=$_POST['xinput'];
			$x=xinputConvert();
			$y=$_POST['yinput'];
			$c=$_POST['colorinput'];
			if ($c=='g')
				$color=GOLD;
			else if ($c=='b')
				$color=BLACK;
			else
				$color=WHITE;
			$Grid[$x][$y]=$color;
			addhistory($_POST['xinput'],$_POST['yinput'],$_POST['colorinput'] );
			
			whosturn();
		}
		
	}
	else if($action=="BLACK")
	{
		$filled=$_POST['fillhere'];
		if ($filled)
		{
			$filledparts=explode('.',$filled);
			$x=$filledparts[0];
			$y=$filledparts[1];
			$color=BLACK;		
			$Grid[$x][$y]=$color;
			addhistory($x,$y,$color );
			whosturn();
		}
		else
		{
			$x=$_POST['xinput'];
			$x=xinputConvert();
			$y=$_POST['yinput'];
			$color=BLACK;		
			$Grid[$x][$y]=$color;
			addhistory($_POST['xinput'],$_POST['yinput'],'b' );
			whosturn();
		}
		
	}
	else if($action=="GOLD")
	{
		$filled=$_POST['fillhere'];
		if ($filled)
		{
			$filledparts=explode('.',$filled);
			$x=$filledparts[0];
			$y=$filledparts[1];
			$color=GOLD;		
			$Grid[$x][$y]=$color;
			addhistory($x,$y,$color );
			whosturn();
		}
		else
		{
			$x=$_POST['xinput'];
			$x=xinputConvert();
			$y=$_POST['yinput'];
			$color=GOLD;		
			$Grid[$x][$y]=$color;
			addhistory($_POST['xinput'],$_POST['yinput'],'y' );
			whosturn();
		}
	}

	else if($action=="LIST MOVES")
	{
		showhistory();
		whosturn();
	}
	else if($action=="UNDO")
	{
		undoLastMove();
		whosturn();
	}
	else // we must be playing already. So fetch and modify the Grid.
	{
		$Grid=$_SESSION['Grid'];
		$x=$_POST['xinput'];
		$x=xinputConvert();
		$y=$_POST['yinput'];
		$c=$_POST['colorinput'];
		if ($c=='g')
			$color=GOLD;
		else if ($c=='b')
			$color=BLACK;
		else
			$color=WHITE;
		$Grid[$x][$y]=$color;
	} # 
	
	drawgrid();
	drawinputs();
	
	$_SESSION['Grid']=$Grid; // Store the grid for the next cycle
	
?>
</form>
</body>
</html>
