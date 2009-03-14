<?
require_once("include/bittorrent.php");
dbconn();
loggedinorreturn();
stdhead();

//$id = 0 + $_GET["id"];
print("<h2><center>HnR List</center></h2>");
print("<center>Kill them all!<br><br></center>");
if ($CURUSER && (get_user_class() == UC_SYSOP)) {
?>
<form action="hnr.php" method="post"><input type=hidden name=addfine value="true"><input type=submit value="Add fine points"></form>
<?
}
print("<table width=60% border=0 cellspacing=0 cellpadding=5>");
print("<tr>\n");
print("<td class=colhead align=left>Username</td>\n");
print("<td class=colhead align=left>Torrent</td>\n");
print("<td class=colhead align=center>Seed Time</td>\n");
// print("<td class=colhead align=left>Downl.</td>\n");
print("<td class=colhead align=left>Ratio</td>\n");
print("<td class=colhead align=left>?????</td>\n");

print("</tr>");
$res = mysql_query("SELECT id FROM torrents WHERE (times_completed > 0) ORDER BY id");
while ($temp_arr = mysql_fetch_assoc($res)) {
$tor[] = $temp_arr;
}

foreach($tor as $tor_id_arr){
$snatch=array();
$seed=array();
$id = $tor_id_arr[id];

if (!is_valid_id($id))
stderr("Error", "It appears that you have entered an invalid id.");

$res = mysql_query("SELECT id, name FROM torrents WHERE id = $id") or sqlerr();
$arr = mysql_fetch_assoc($res);

if (!$arr)
stderr("Error", "It appears that there is no torrent with that id.");

$res = mysql_query("SELECT userid FROM snatched WHERE torrentid = $id AND to_go=0") or sqlerr();
$res2 = mysql_query("SELECT userid FROM peers WHERE torrent=$id AND seeder='yes'") or sqlerr();

while ($temp_arr2 = mysql_fetch_assoc($res)) {
$snatch[] = $temp_arr2[userid];
}
while ($temp_arr3 = mysql_fetch_assoc($res2)) {
$seed[] = $temp_arr3[userid];
}


foreach ($snatch as $userid) {
$seeding = false;
foreach ($seed as $seedid) {
if ($userid == $seedid) {
$seeding = true;
}
}
$bitch = mysql_fetch_assoc(mysql_query("SELECT username, warned, uploaded, downloaded, fine FROM users WHERE id=$userid"));

if ((!$seeding) && ($ratio < 1)) {

if ($_POST["addfine"]==true) {
$fine = $bitch["fine"];
$fine+=(1-$ratio);
mysql_query("UPDATE users SET fine=$fine WHERE id=$userid");
}

print("<tr>");
print("<td align=left><b><a href=/userdetails.php?id=".$userid.">".$bitch[username]."</a></b></td>\n");
print("<td align=left><a href=/details.php?id=".$arr[id].">".$arr[name]."</a></td>\n");
print("<td align=center>".number_format($bitch["fine"],3,".",",")."</a></td>\n");
print("<td align=right>".(round(($bitch["downloaded"]/(1024*1024*1024))*100)/100)." GB</td>\n");
print("<td align=center>". (($ratio == 0) ? "<font color=red>0.000</color>" : $ratio)."</td>\n");
if ($bitch[warned]=='yes') { $warned=true; } else $warned=false;
print("<td align=center>x".($warned ? "<font color=red>Yes</color>" : "No")."</td>\n");
print("</tr>");
}
$ratio = ($bitch["downloaded"] > 0 ? number_format($bitch["uploaded"] / $bitch["downloaded"], 3) : ($bitch["uploaded"] > 0 ? "Inf." : "---"));
$res = mysql_fetch_assoc(mysql_query("SELECT seedtime, leechtime FROM snatched WHERE torrentid = $id AND userid = $userid"));
$seedtimep = mkprettytime($res["seedtime"] - $res["leechtime"]);
$seedtime = $res["seedtime"] - $res["leechtime"];
if ((!$seeding) && ($seedtime < 259200)) {
if ($_POST["addfine"]==true) {
$fine = $bitch["fine"];
$f1 =0; if ($ratio < 1) $f1=(1-$ratio);
$f2 =0; $f2=((259200 - $seedtime)/259200);
$fine+= $f1 + $f2;
mysql_query("UPDATE users SET fine=$fine WHERE id=$userid");
}

print("<tr>");
if ($bitch[warned]=='yes') { $warned=true; } else $warned=false;
//print("<td align=center>".($warned ? "<font color=red>Yes</color>" : "No")."</td>\n");
print("<td align=left><b><a href=/userdetails.php?id=".$userid.">".$bitch[username]."</a>".($warned ? "<img src=/pic/warned.gif>" : "")."</img></b></td>\n");
print("<td align=left><a href=/details.php?id=".$arr[id].">".$arr[name]."</a></td>\n");
print("<td align=right>".(($seedtimep == 0) ? "<font color=red>$seedtimep</font>" : $seedtimep) ."</td>\n");

//print("<td align=right>".(round(($bitch["downloaded"]/(1024*1024*1024))*100)/100)." GB</td>\n");
print("<td align=center>". (($ratio == 0) ? "<font color=red>0.000</color>" : $ratio)."</td>\n");
print("<td align=right>".number_format($bitch["fine"],3,".",",")."</a></td>\n");


print("</tr>");

}
}
}
print("</table>");
stdfoot();
?>
