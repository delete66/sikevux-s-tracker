<?
require "include/bittorrent.php";

dbconn();

loggedinorreturn();
 if (get_user_class() < UC_MODERATOR)
stderr("Error", "Only Mods + can Search the logs");
stdhead("Search Log Page");


$res = mysql_query("SELECT * FROM sitelog WHERE txt LIKE '%$query%' ORDER BY txt DESC") or sqlerr();
$num = mysql_num_rows($res);

print("<table border=1 cellspacing=0 width=115 cellpadding=5>\n");
print("<form method=get action=log.php><input type=submit value=Back >GO back to the main log.</form>\n");
print("<tr><td class=tabletitle align=center><b>Search Log</b></td></tr>\n");
print("<tr><td class=tableb align=left><form method=\"get\" action=searchlog.php>\n");
print("<input type=\"text\" name=\"query\" size=\"40\" value=\"" . htmlspecialchars($searchstr) . "\">\n");
print("<input type=submit value=" . SEARCH . " style='height: 20px' /></form>\n");
print("</td></tr></table>\n");
  print("<table border=1 cellspacing=0 cellpadding=5>\n");
  print("<tr><td class=tabletitle align=left>Date</td><td class=tabletitle align=left>Time</td><td class=tabletitle align=left>Event</td></tr>\n");
  while ($arr = mysql_fetch_assoc($res))
  {
$color = 'white';
if (strpos($arr['txt'],'was created')) $color = "#CC9966";
if (strpos($arr['txt'],'was invited by')) $color = "#CC9966";
if (strpos($arr['txt'],'was invited to the site.')) $color = "#CC9966";
if (strpos($arr['txt'],'was deleted by')) $color = "#CC6666";
if (strpos($arr['txt'],'was updated by')) $color = "#6699FF";
if (strpos($arr['txt'],'was edited by')) $color = "#BBaF9B";
    $date = substr($arr['added'], 0, strpos($arr['added'], " "));
    $time = substr($arr['added'], strpos($arr['added'], " ") + 1);


    print("<tr class=tableb><td style=background-color:$color><font color=black>$date</td><td style=background-color:$color><font color=black>$time</td><td style=background-color:$color align=left><font color=black>".$arr['txt']."</font></font></font></td></tr>\n");
}
  print("</table>");



stdfoot();
die;

?>
