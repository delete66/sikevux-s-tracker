<?
require "include/bittorrent.php";
dbconn();
loggedinorreturn();
stdhead("Staff");
begin_main_frame();
begin_frame("");
?>


<?
$act = $_GET["act"];
if (!$act) {
// Get current datetime
$dt = gmtime() - 60;
$dt = sqlesc(get_date_time($dt));
// Search User Database for Moderators and above and display in alphabetical order
$res = mysql_query("SELECT * FROM users WHERE class>=".UC_UPLOADER.
" AND status='confirmed' ORDER BY username" ) or sqlerr();

while ($arr = mysql_fetch_assoc($res))
{

$staff_table[$arr['class']]=$staff_table[$arr['class']].
"<td class=embedded><a class=altlink href=userdetails.php?id=".$arr['id'].">".
$arr['username']."</a></td><td class=embedded> ".("'".$arr['last_access']."'">$dt?"<img src=".$pic_base_url."button_online.gif border=0 alt=\"online\">":"<img src=".$pic_base_url."button_offline.gif border=0 alt=\"offline\">" )."</td>".
"<td class=embedded><a href=sendmessage.php?receiver=".$arr['id'].">".
"<img src=".$pic_base_url."button_pm.gif border=0></a></td>".
" ";



// Show 3 staff per row, separated by an empty column
++ $col[$arr['class']];
if ($col[$arr['class']]<=2)
$staff_table[$arr['class']]=$staff_table[$arr['class']]."<td class=embedded>&nbsp;</td>";
else
{
$staff_table[$arr['class']]=$staff_table[$arr['class']]."</tr><tr height=15>";
$col[$arr['class']]=0;
}
}
begin_frame("Staff");
?>

<table width=725 cellspacing=0>
<tr>
<tr><td class=embedded colspan=11>All software support questions and those already answered in the FAQ will be ignored.</td></tr>
<!-- Define table column widths -->
<td class=embedded width="125">&nbsp;</td>
<td class=embedded width="25">&nbsp;</td>
<td class=embedded width="35">&nbsp;</td>
<td class=embedded width="85">&nbsp;</td>
<td class=embedded width="125">&nbsp;</td>
<td class=embedded width="25">&nbsp;</td>
<td class=embedded width="35">&nbsp;</td>
<td class=embedded width="85">&nbsp;</td>
<td class=embedded width="125">&nbsp;</td>
<td class=embedded width="25">&nbsp;</td>
<td class=embedded width="35">&nbsp;</td>
</tr>
<tr><td class=embedded colspan=11><b>Administrators</b></td></tr>
<tr><td class=embedded colspan=11><hr color="#4040c0" size=1></td></tr>
<tr height=15>
<?=$staff_table[UC_ADMINISTRATOR]?>
</tr>
<tr><td class=embedded colspan=11>&nbsp;</td></tr>
<tr><td class=embedded colspan=11><b>Moderators</b></td></tr>
<tr><td class=embedded colspan=11><hr color="#4040c0" size=1></td></tr>
<tr height=15>
<?=$staff_table[UC_MODERATOR]?>
</tr>
<tr><td class=embedded colspan=11>&nbsp;</td></tr>
<tr><td class=embedded colspan=11><b>Uploaders</b></td></tr>
<tr><td class=embedded colspan=11><hr color="#4040c0" size=1></td></tr>
<tr height=15>
<?=$staff_table[UC_UPLOADER]?>
</tr>
</table>
<?
end_frame();
}
?>

<? if (get_user_class() >= UC_SYSOP) { ?>
<? begin_frame("Site Owner Tools<font color=#FF0000> - Viewable by SysOp only.</font>"); ?>
<table width=500 cellspacing=10 align=center>
<tr>
<td class=embedded><form method=get action=importpg.php><input type=submit value="PeerGuardian" style='height: 20px; width: 100px'></form></td>
<td class=embedded><form method=get action=staffmess.php><input type=submit value="Mass Messager" style='height: 20px; width: 100px'></form></td>
<td class=embedded><form method=get action=category.php><input type=submit value="Modify Categories" style='height: 20px; width: 100px'></form></td>
<td class=embedded><form method=get action=delacct.php><input type=submit value="Delete Account" style='height: 20px; width: 100px'></form></td>
<td class=embedded><form method=get action=status.php><input type=submit value="Server Status" style='height: 20px; width: 100px'></form></td>
</tr>
</table>
<? end_frame();
}

if (get_user_class() >= UC_ADMINISTRATOR) { ?>
<? begin_frame("Site Owner Tools<font color=#009900> - Viewable by Administrators only.</font>"); ?>
<table width=500 cellspacing=10 align=center>
<tr>
<td class=embedded><form method=get action=unco.php><input type=submit value="Unconfirmed Users" style='height: 20px; width: 100px'></form></td>
<td class=embedded><form method=get action=delacctadmin.php><input type=submit value="Delete USERS" style='height: 20px; width: 100px'></form></td>
<td class=embedded><form method=get action=bans.php><input type=submit value="Client Bans" style='height: 20px; width: 100px'></form></td>
</tr>
<tr>
<td class=embedded><form method=get action=topten.php><input type=submit value="Top 10" style='height: 20px; width: 100px'></form></td>
<td class=embedded><form method=get action=bitbucketlog.php><input type=submit value="Bitbucket Logs" style='height: 20px; width: 100px'></form></td>
<td class=embedded><form method=get action=findnotconnectable.php><input type=submit value="NON Connectable" style='height: 20px; width: 100px'></form></td>
</tr>
</table>
<? end_frame();
}

if (get_user_class() >= UC_MODERATOR) { ?>
<? begin_frame("Staff tools - <font color=#004E98>Viewable by Mods only.</font>"); ?>


<table width=640 cellspacing=3>
<tr>
<? if (get_user_class() >= UC_MODERATOR) { ?>
</tr>
<tr>
<td class=embedded><a class=altlink href=staff.php?act=users>List users with ratio below 0.20</a></td>
<td class=embedded>Lists all the users that have an share ratio below 0.20</td>
</tr>
<tr>
<td class=embedded><a class=altlink href=staff.php?act=banned>List all banned users</a></td>
<td class=embedded>Lists all the users that have been banned from the site</td>
</tr>
<tr>
<td class=embedded><a class=altlink href=staff.php?act=last>Newest users</a></td>
<td class=embedded>100 newest user accounts</td>
</tr>
<tr>
<td class=embedded><a class=altlink href=log.php>Site log</a></td>
<td class=embedded>See whats been upped/deleted/etc</td>
</tr>
</table>

<? end_frame(); ?>
<br>
<? begin_frame("Moderators and Tools - <font color=#004E98>Viewable by Mods only.</font>"); ?>

<br>
<table width=500 cellspacing=3>
<tr>
<td class=embedded></td>

</tr>

</table>
<table width=500 cellspacing=10 align=center>
<tr>
<td class=embedded><form method=get action=warned.php><input type=submit value="Users Warned" style='height: 20px; width: 100px'></form></td>
<td class=embedded><form method=get action=adduser.php><input type=submit value="Add User" style='height: 20px; width: 100px'></form></td>
<td class=embedded><form method=get action=makepoll.php><input type=submit value="Create a Poll" style='height: 20px; width: 100px'></form></td>
<td class=embedded><form method=get action=recover.php><input type=submit value="Recover Account" style='height: 20px; width: 100px'></form></td>
<td class=embedded><form method=get action=upstats.php><input type=submit value="Uploaders" style='height: 20px; width: 100px'></form></td>
</tr>
<tr>
<td class=embedded><form method=get action=polloverview.php><input type=submit value="Poll Overview" style='height: 20px; width: 100px'></form></td>
<td class=embedded><form method=get action=users.php><input type=submit value="User List" style='height: 20px; width: 100px'></form></td>
<td class=embedded><form method=get action=tags.php><input type=submit value="Forum Tags" style='height: 20px; width: 100px'></form></td>
<td class=embedded><form method=get action=smilies.php><input type=submit value="Smilies" style='height: 20px; width: 100px'></form></td>
<td class=embedded><form method=get action=pending.php><input type=submit value="Pending" style='height: 20px; width: 100px'></form></td>
</tr>
<tr>
<td class=embedded><form method=get action=stats.php><input type=submit value="Tracker Stats" style='height: 20px; width: 100px'></form></td>
<td class=embedded><form method=get action=testip.php><input type=submit value="Test IP" style='height: 20px; width: 100px'></form></td>
<td class=embedded><form method=get action=reports.php><input type=submit value="Reports" style='height: 20px; width: 100px'></form></td>
<td class=embedded><form method=get action=ipcheck.php><input type=submit value="Duplicate IPs" style='height: 20px; width: 100px'></form></td>
</tr>
</table>
<br>

<? end_frame(); ?>

<? begin_frame("Search user - <font color=#004E98>Viewable by Mods only.</font>"); ?>


<table width=640 cellspacing=3>
<tr>
<td class=embedded>
<form method=get action="users.php">
Search: <input type=text size=30 name=search>
<select name=class>
<option value='-'>(any class)</option>
<option value=0>User</option>
<option value=1>Power User</option>
<option value=2>VIP</option>
<option value=3>Uploader</option>
<option value=4>Moderator</option>
<option value=5>Administrator</option>
<option value=6>SysOp</option>
</select>
<input type=submit value='Okay'>
</form>
</td>
</tr>
<tr><td class=embedded><li><a href="usersearch.php">Advance user search</li></a></td></tr>
</table>

<? end_frame(); ?>
<br>
<? if ($act == "users") {
begin_frame("Users with ratio below 0.20");

echo '<table width="640" border="0" align="center" cellpadding="2" cellspacing="0">';
echo "<tr><td class=colhead align=left>User</td><td class=colhead>Ratio</td><td class=colhead>IP</td><td class=colhead>Date Joined</td><td class=colhead>Last Access</td><td class=colhead>Download</td><td class=colhead>Upload</td></tr>";


$result = mysql_query ("SELECT * FROM users WHERE uploaded / downloaded < 0.20 AND enabled = 'yes' ORDER BY downloaded DESC ");
if ($row = mysql_fetch_array($result)) {
do {
if ($row["uploaded"] == "0") { $ratio = "inf"; }
elseif ($row["downloaded"] == "0") { $ratio = "inf"; }
$ratio = "<font color=" . get_ratio_color($ratio) . ">$ratio</font>";
echo "<tr><td><a href=userdetails.php?id=".$row["id"]."><b>".$row["username"]."</b></a></td><td><strong>".$ratio."</strong></td><td>".$row["ip"]."</td><td>".$row["added"]."</td><td>".$row["last_access"]."</td><td>".mksize($row["downloaded"])."</td><td>".mksize($row["uploaded"])."</td></tr>";


} while($row = mysql_fetch_array($result));
} else {print "<tr><td>Sorry, no records were found!</td></tr>";}
echo "</table>";
end_frame(); }?>

<? if ($act == "last") {
begin_frame("Latest users");

echo '<table width="640" border="0" align="center" cellpadding="2" cellspacing="0">';
echo "<tr><td class=colhead align=left>User</td><td class=colhead>Ratio</td><td class=colhead>IP</td><td class=colhead>Date Joined</td><td class=colhead>Last Access</td><td class=colhead>Download</td><td class=colhead>Upload</td></tr>";

$result = mysql_query ("SELECT * FROM users WHERE enabled = 'yes' AND status = 'confirmed' ORDER BY added DESC limit 100");
if ($row = mysql_fetch_array($result)) {
do {
if ($row["uploaded"] == "0") { $ratio = "inf"; }
elseif ($row["downloaded"] == "0") { $ratio = "inf"; }
else {
$ratio = number_format($row["uploaded"] / $row["downloaded"], 3);
$ratio = "<font color=" . get_ratio_color($ratio) . ">$ratio</font>";
}
echo "<tr><td><a href=userdetails.php?id=".$row["id"]."><b>".$row["username"]."</b></a></td><td><strong>".$ratio."</strong></td><td>".$row["ip"]."</td><td>".$row["added"]."</td><td>".$row["last_access"]."</td><td>".mksize($row["downloaded"])."</td><td>".mksize($row["uploaded"])."</td></tr>";


} while($row = mysql_fetch_array($result));
} else {print "<tr><td>Sorry, no records were found!</td></tr>";}
echo "</table>";
end_frame(); }?>


<? if ($act == "banned") {
begin_frame("Banned users");

echo '<table width="640" border="0" align="center" cellpadding="2" cellspacing="0">';
echo "<tr><td class=colhead align=left>User</td><td class=colhead>Ratio</td><td class=colhead>IP</td><td class=colhead>Date Joined</td><td class=colhead>Last Access</td><td class=colhead>Download</td><td class=colhead>Upload</td></tr>";
$result = mysql_query ("SELECT * FROM users WHERE enabled = 'no' ORDER BY last_access DESC ");
if ($row = mysql_fetch_array($result)) {
do {
if ($row["uploaded"] == "0") { $ratio = "inf"; }
elseif ($row["downloaded"] == "0") { $ratio = "inf"; }
else {
$ratio = number_format($row["uploaded"] / $row["downloaded"], 3);
$ratio = "<font color=" . get_ratio_color($ratio) . ">$ratio</font>";
}
echo "<tr><td><a href=userdetails.php?id=".$row["id"]."><b>".$row["username"]."</b></a></td><td><strong>".$ratio."</strong></td><td>".$row["ip"]."</td><td>".$row["added"]."</td><td>".$row["last_access"]."</td><td>".mksize($row["downloaded"])."</td><td>".mksize($row["uploaded"])."</td></tr>";


} while($row = mysql_fetch_array($result));
} else {print "<tr><td>Sorry, no records were found!</td></tr>";}
echo "</table>";
end_frame(); } }



}
if (get_user_class() >= UC_USER) {

if (!$act) {
$dt = gmtime() - 180;
$dt = sqlesc(get_date_time($dt));
// LIST ALL FIRSTLINE SUPPORTERS
// Search User Database for Firstline Support and display in alphabetical order
$res = mysql_query("SELECT * FROM users WHERE support='yes' AND status='confirmed' ORDER BY username LIMIT 10") or sqlerr();
while ($arr = mysql_fetch_assoc($res))
{
$land = mysql_query("SELECT name,flagpic FROM countries WHERE id=$arr[country]") or sqlerr();
$arr2 = mysql_fetch_assoc($land);
$firstline .= "<tr height=15><td class=embedded><a class=altlink href=userdetails.php?id=".$arr['id'].">".$arr['username']."</a></td>
<td class=embedded> ".("'".$arr['last_access']."'">$dt?"<img src=".$pic_base_url."button_online.gif border=0 alt=\"online\">":"<img src=".$pic_base_url."button_offline.gif border=0 alt=\"offline\">" )."</td>".
"<td class=embedded><a href=sendmessage.php?receiver=".$arr['id'].">"."<img src=".$pic_base_url."button_pm.gif border=0></a></td>".
"<td class=embedded><img src=".$pic_base_url."/flag/$arr2[flagpic] border=0 width=19 height=12></td>".
"<td class=embedded>".$arr['supportfor']."</td></tr>\n";
}


begin_frame("Firstline Support");
?>

<table width=725 cellspacing=0>
<tr>
<td class=embedded colspan=11>General support questions should preferably be directed to these users. Note that they are volunteers, giving away their time and effort to help you.
Treat them accordingly. (Languages listed are those besides English.)<br><br><br></td></tr>
<!-- Define table column widths -->
<tr>
<td class=embedded width="30"><b>Username</b></td>
<td class=embedded width="5"><b>Active</b></td>
<td class=embedded width="5"><b>Contact</b></td>
<td class=embedded width="85"><b>Language</b></td>
<td class=embedded width="200"><b>Support for:</b></td>
</tr>


<tr>
<tr><td class=embedded colspan=11><hr color="#4040c0" size=1></td></tr>

<?=$firstline?>

</tr>
</table>
<?
end_frame();
}

?>
<?
end_frame();
end_main_frame();
stdfoot();
}
?>