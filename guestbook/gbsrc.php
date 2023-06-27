<? 
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
			PHP Guestbook 1.0 (Feb. 2002)
			by Harald Meyer (webmaster@harrym.nu) 
			Feel free to use it, but don't delete these lines. 
			PHP FXP is Freeware. 
			Based on another Guestbook.
			Please send me modified versions! 
			If you use it on your page, a link back to its homepage would
			be highly appreciated.
			Homepage: http://tools.harrym.nu/php_guestbook.php
 ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */

//*********************************************************************************************//
// To change:																         		   //	
//*********************************************************************************************//


// File that contains the data
$filelocation="gbook_data.php";  
//Name of this document here
$boardname="guestbook.php"; 

// The message to display when someone entered data $name is the name then
$thanksmessage="<br><br><b>Thanks for your message, $name!</b><br>";

// The message to display, if there is an error 
$errormessage="<br><br><b>You must enter your name and a message!</b><br>";

// Defines, if special characters should be converted to their html equivalents like > to &gt;
// 1 enables and 0 disables this feature
$specialchars=1;

// Defines, if Ascii linebreaks (people pressing enter while filling the form should become
// html linebreaks <br>
// 1 enables and 0 disables this feature
$linebreaks=1;

// Entrytext, displayed every time the guestbooks gets loaded
$entrytext = "";

// Maximal length of the words, to prevent someone killing your layout 
$maxwordlength = 30;

// Rules, displayed when you enter some text...
$rules = "";

//Captions
$cap_submit="Submit";
$cap_msg="Message:";
$cap_noenty="Kein Eintrag bis jetzt!";

//Colors, Styles
$col_tb="#CCCCCC";

/*-----------------------------------------------------------------------------------------------
					Adding a new entry to the data file
------------------------------------------------------------------------------------------------*/
if ($edit == "yes"){
	
	// test if someone forgot to enter spaces.
	$spaces = explode(" ",$message);
	for ($i=0;$i<sizeof($spaces);$i++){
	if (strlen($spaces[$i]) > $maxwordlength) $bozo="yes";
	}
	$spaces = explode(" ",$name);
	for ($i=0;$i<sizeof($spaces);$i++){
	if (strlen($spaces[$i]) > $maxwordlength) $bozo="yes";
	}

	// When there is missing data, display the errormessage and reprint the form
	if ($name == "" || $message =="" || $bozo == "yes"){
		echo "<table align=\"center\" width=\"90%\">
		<center><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\"> $errormessage \n</center>";
		if ($bozo = "yes") echo "";
		echo "
		
		
		<form>
		<dl>
		<tr><td><div align=\"left\"><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\">Name:</td>
		<td><input type=\"text\" name=\"name\" value=\"$name\" class=search size=30></td></tr>
		<tr><td><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\">eMail:</td>
		<td><input type=\"text\" name=\"email\" value=\"$email\" class=search size=30></td></tr>
		<tr><td><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\">url:</td>
		<td><input type=\"text\" name=\"url\" value=\"$url\" class=search size=30></td></tr>
		<tr><td><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\">$cap_msg</td>
		<td><textarea name=\"message\" class=search cols=30 rows=5>".$message."</textarea></td></tr>
		</dl>
		<tr><td><div align=\"left\">
		<input type=\"hidden\" name=\"edit\" value=\"yes\">
		<input type=\"submit\" value=$cap_submit border=0>
		</div></td></tr>
		</form>
		</table>
		";
	}
	// If all the entries are correct, display thanksmessage add to datafile
	else {
		echo "<p align=\"center\"><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\"> $thanksmessage\n";
		// Change % and # to percent and no, to avoid wrong splitting of the data.
		$message = eregi_replace("#","no.",$message);
		$message = eregi_replace("%","percent",$message);
		$name = eregi_replace("#","no.",$name);
		$name = eregi_replace("%","percent",$name);
		// When specialchars are to be replaced...
		if ($specialchars==1){
		$message=htmlentities($message);
		$name=htmlentities($name);
		}
		// When ASCII Linebreaks should become <br>s
		if ($linebreaks==1){$message = nl2br($message);}
		// When there is no datafile yet, create a new one
		if (!file_exists($filelocation)) {
			$newfile = fopen($filelocation,"w+");
			fclose($newfile);
			}
		// Add the data to the end of the datafile
		$newfile = fopen($filelocation,"a+");
		$add = "%".$name."#".$email."#".$url."#".date("F jS Y")."#".$message."\n";
		fwrite($newfile, $add);
		fclose($newfile);
	}
}


/*-----------------------------------------------------------------------------------------------
					Introduction Screen when there is no data from the form
------------------------------------------------------------------------------------------------*/
if ($action != "view" && $action !="new"){
	if ($edit=="yes"){
		
	}
	else {
		echo $entrytext;
	}
}

/*-----------------------------------------------------------------------------------------------
                 					   Display the guestbook 
------------------------------------------------------------------------------------------------*/

if ($action=="view"){
	echo $entrytext;
	// When there is no data file, create a new one and say there are no entries.
	if (!file_exists($filelocation)) {
	$newfile = fopen($filelocation,"w+");
	fclose($newfile);
	echo $cap_noentry;
	}
	// Open the datafile and read the content
	$newfile = fopen($filelocation,"r");
	$content = fread($newfile, filesize($filelocation));
	fclose($newfile);
	// Remove the slashes PHP automatically puts before special characters
	$content=stripslashes($content);
	// Put the entries into the array lines
	$lines = explode("%",$content);
	// Define and fill the reverse array (showing the last entry first)
	$rev=array();
	for ($i=sizeof($lines)-1;$i>0;$i--){array_push($rev,$lines[$i]);}
	$lines=$rev;
	// Display all the entries of the guestbook
	while(list($key)= each ($lines)){
		/*
		/ split the data lines into a user array
		/ user[0] is the name, [1] is the email, [2] is the url [3] is the date and [4] the message
		*/
		$urladd="";
		$mailadd="";
		$user = explode("#",$lines[$key]);

 
      
		
// if there is an email, display an email link
		if ($user[1] != ""){$mailadd="<a href=\"mailto:".$user[1]."\" class=asm><font size=\"1\" face=\"Verdana, Arial, Helvetica, sans-serif\">email</a>";}
		// if there is a url, display a link
		if ($user[2] != ""){
			// if people were dumb enough not to add http:// do so 
			if (substr($user[2],0,7)!= "http://"){
				$user[2]="http://".$user[2];
			}
			$urladd="<a href=\"".$user[2]."\" target=\"_blank\"class=asm><font size=\"1\" face=\"Verdana, Arial, Helvetica, sans-serif\">Homepage</a>";
		}
echo "<table align=\"center\" width=\"90%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
        <tr bgcolor=\"$col_tb\"> 
          <td colspan=\"2\"><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\"><b><i>";

echo "<dl><b>$user[0]</b>&nbsp;&nbsp;$mailadd&nbsp;$urladd&nbsp;&nbsp;($user[3])";

echo "</i></b></font></td>
        </tr>
        <tr> 
          <td width=\"8%\"><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\"></font></td>
          <td width=\"92%\"><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\">";

		// Display the entries, here you can tweak HTML
		echo "
		
		$user[4]\n</dl>";
echo "<br>
            </font></td>
        </tr>
      </table>";		
}
}

/*-----------------------------------------------------------------------------------------------
                    Add a new entry to the guestbook, display the rules and show the form.
------------------------------------------------------------------------------------------------*/
if ($action == "new"){
	echo "$rules<br>\n
	<table align=\"center\" width=\"90%\">
		<center><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\"> \n</center>";
		if ($bozo = "yes") echo "";
		echo "
		<form>
		
		<tr><td><div align=\"left\"><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\">Name:</td>
		<td><input type=\"text\" name=\"name\" value=\"$name\" class=search size=30></td></tr>
		<tr><td><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\">eMail:</td>
		<td><input type=\"text\" name=\"email\" value=\"$email\" class=search size=30></td></tr>
		<tr><td><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\">url:</td>
		<td><input type=\"text\" name=\"url\" value=\"$url\" class=search size=30></td></tr>
		<tr><td><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\">$cap_msg:</td>
		<td><textarea name=\"message\" class=search cols=30 rows=5>".$message."</textarea></td></tr>
		
		<tr><td><div align=\"left\">
		<input type=\"hidden\" name=\"edit\" value=\"yes\">
		<input type=\"submit\" value=$cap_submit border=0>
		</div></td></tr>
		</form>
		</table>
	";
}
?>