<?
ob_start();
require_once("include/bittorrent.php");
dbconn(false);
loggedinorreturn();
if (get_user_class() < UC_ADMINISTRATOR)
die;

stdhead("Categories");

function autolink($al_url, $al_msg) // create autolink
{
echo "\n<meta http-equiv=\"refresh\" content=3; URL=".$al_url.">\n";
echo "<b>$al_msg</b>\n";
echo "<p>\n<b>Redirecting ...</b>\n";
echo "<p>\n[ <a href='$al_url'>link</a> ]\n";
echo "</td>\n</tr>\n</table>\n</td>\n</tr>\n</table>\n</body>\n</html>\n";
stdfoot();
exit;
}

if($do == "add_this_cat")
//add to db & create autolink
{
$error_ac == "";
if($new_cat_name == "") $error_ac .= "<li>Category Name was empty";
if($new_desc == "") $error_ac .= "<li>Category Descriptions was empty";

if($error_ac == "")
{
$sql = "INSERT INTO $table_cat (`name`, `cat_desc`, `image`) VALUES ('$new_cat_name', '$new_desc', '$new_image')";
$ok = MYSQL_QUERY($sql);
if($ok) autolink("$PHP_SELF?act=cat", "Thanks, New category added to the database");
else echo "<h4>Couldn't save into the database. Check your settings!</h4>";
}
}

//save edited data
if($do == "save_ed")
{
if($changed_cat != "" && $changed_cat_desc !="")
{
$sql1 = "UPDATE $table_cat SET name = '$changed_cat', cat_desc = '$changed_cat_desc', image = '$changed_image' WHERE id = '$id'";
$ok1 = MYSQL_QUERY($sql1);
if($ok1) autolink("$PHP_SELF?act=cat", "Thanks, category updated");
else echo "<h4>Couldn't save into the database. Check your settings!</h4>";
} else { echo "ERROR! Submit all fields!<p>:: <a href='java script:history.back()'>back</a>\n"; }
}

//finally delete cat
if($do == "delete_cat")
{
if($delcat != "")
{
$sql2 = "DELETE FROM $table_cat WHERE id = '$id'";
$ok2 = MYSQL_QUERY($sql2);
if($ok2) autolink("$PHP_SELF?act=cat", "Deleted category");
}}

begin_frame("Categories", center);
?>
<p>
<table align='center' width='80%' class=main border='1' cellspacing='0' cellpadding='2'>
<form action='<?=$PHP_SELF?>' method='post'>
<input type='hidden' name='sid' value='<?=$sid?>'>
<input type='hidden' name='act' value='sql'>
<input type='hidden' name='do' value='add_this_cat'>
<tr>
<td>Name Of Category:</td>
<td align='right'><input type='text' name='new_cat_name' size='30' maxlength='30' value='<?=$new_cat_name?>'></td>
</tr>
<tr>
<td>Description Of Category:</td>
<td align='right'><textarea cols='50' rows='5' name='new_desc'><?=$new_desc?></textarea></td>
</tr>
<tr>
<td>Name Of Category Image Example(movies.jpg)</td>
<td align='right'><input type="text" name="new_image" class="option" size="35" value="<? echo $r[image]; ?>"</td>
</tr>
<tr>
<td colspan='2' align='center'>
<input type='submit' value='Submit'>
<input type='reset' value='Reset'>
</td>
</tr>
<?
if($error_ac != "") echo "<tr><td colspan='2' align='center' style='background:#eeeeee;border:2px red solid'><b>Couldn't create category:</b><br />$error_ac</tr></td>\n";
?>
</table>
<p>
<table align='center' width='80%' class=main border='1' cellspacing='0' cellpadding='2'>
<h5>Manage Categories:</h5>
<?
print("<tr><td class=colhead align=left width='60'>ID</td><td class=colhead align=center width='90'>Category Name</td><td class=colhead align=center width='50'>Description</td><td class=colhead align=center width='20'>Image<td class=colhead align=center width='5'>Edit<td class=colhead align=center width='5'>Delete</td></tr>\n");
// get cats from db
$query = MYSQL_QUERY("SELECT * FROM $table_cat");
$allcats = MYSQL_NUM_ROWS($query);
if($allcats == 0) {
echo "<h4>None</h4>\n";
} else {
while($row =MYSQL_FETCH_ARRAY($query))
{
echo "<tr><td width='60'><font size='2'><b>ID($row[id])</b></td><td width='120'> $row[name]</td><td width='250'>$row[cat_desc]</td><td width='45'><img border='0' src=\"pic/" . $row["image"] . "\"></td></font>\n";
echo "<td width='18'><a href='category.php?do=edit_cat&id=$row[id]'><img src='pic/edit.gif' alt='Edit Category' width='17' height='17' border='0'></a></td>\n";
echo "<td width='18'><a href='category.php?do=del_cat&id=$row[id]'><img src='pic/delete.gif' alt='Delete Category' width='17' height='17' border='0'></a></td></tr>\n";
}
MYSQL_FREE_RESULT($query);
} //endif
echo "</table>\n";
end_frame();

//edit category
if($do == "edit_cat")
{
$q = MYSQL_QUERY("SELECT * FROM $table_cat WHERE id = '$id'");
$r = MYSQL_FETCH_ARRAY($q);
begin_frame("Modify Categories", center);
?>
<table align='center' width='80%' class=main border='1' cellspacing='0' cellpadding='2'>
<form action="<? echo $PHP_SELF; ?>" method="post">
<input type="hidden" name="do" value="save_ed">
<input type="hidden" name="id" value="<? echo $id; ?>">
<tr><td>Name Of Category:</td></tr>
<tr><td><input type="text" name="changed_cat" class="option" size="35" value="<? echo $r[name]; ?>"></td></tr>
<tr><td>Description:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>
<tr><td><textarea cols='50' rows='5' name='changed_cat_desc'><? echo $r[cat_desc]; ?></textarea></td></tr>
<tr><td>Name Of Category Image Example(movies.jpg):</td></tr>
<tr><td><input type="text" name="changed_image" class="option" size="35" value="<? echo $r[image]; ?>"</td></tr>
<tr><td colspan='2' align='center'>
<input type="submit" class="button" value="Update"></td></tr>
</form>
</table>
<?
end_frame();
}

//del category
if($do == "del_cat")
{
$t = MYSQL_QUERY("SELECT * FROM $table_cat WHERE id = '$id'");
$v = MYSQL_FETCH_ARRAY($t);
begin_frame("Delete category", center);
?>
<form action="<? echo $PHP_SELF; ?>" method="post">
<input type="hidden" name="do" value="delete_cat">
<input type="hidden" name="id" value="<? echo $id; ?>">
Are you sure you would like to <b>DELETE</b> <? echo "<b>$v[name]</b> - <b>ID$v[id]</b>"?>
<input type="submit" name="delcat" class="button" value="Delete">
</form>
<?
end_frame();
}
?> 