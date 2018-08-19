<?php
/**
 * Created by PhpStorm.
 * User: bhansen
 * Date: 07/09/16
 * Time: 22:23
 */

session_start();
if ($_SESSION['loggedin']) {
setlocale(LC_ALL, "da_DK");
spl_autoload_register(function ($class) {
    include 'classes/' . $class . '.php';
});
include "config.php";


    $db = new DbModel();
    $uniqteam = $db->queryToArray("select concat(groups,id) cid, id from tivoli2018_teams where groups != 'N' order by groups, id");
    $uniqpost = $db->queryToArray("select pc.postid,group_concat(DISTINCT p.mobile SEPARATOR '<br />') mobile from tivoli2018_postcheckin_change_log pc left join tivoli2018_postcheckin p on pc.postid = p.postid GROUP BY pc.postid ORDER BY LPAD(lower(pc.postid), 10,0)");

    print("<h2>Post / Point Oversigt</h2><hr><table class='table table-striped table-bordered'><tr><th></th>");
    foreach ($uniqpost as $post) {
        print("<th>".$post['postid'].($post['mobile'] ? "<br/><span style='font-weight: normal; font-size: 10px'>Postmandskab:<br />".$post['mobile']."</span></th>" : ""));
    }
    print("</tr>");

    foreach($uniqteam as $team) {
        print("<tr><th>".$team['cid']."</th>");
        foreach ($uniqpost as $post) {
            $s = $db->queryToArray("select action, teamid, point, postid, creator, DATE_FORMAT(updated_at, '%H:%i:%S') updated_at1 from tivoli2018_score_change_log where postid = '".$post['postid']."' and teamid = '".$team['id']."' order by updated_at desc");
            if(isset($s[0])) {
                if($s[0]['point']) {
                    $history = "";
                    foreach ($s as $s1) {
                        $history .= $s1['updated_at1']." ".$s1['creator'].": ".$s1['point']."<br>";
                    }
                    $popover = "tabindex=\"0\" data-placement=\"top\" data-toggle=\"popover\" data-trigger=\"focus\" title=\"Pointhistorik\" data-html=\"true\" data-content=\"".$history."\"";
                    if ($s[0]['action'] == "INSERT") print ("<td class='bg-success' style='cursor: pointer' $popover>".$s[0]['point']."</td>");
                    else if ($s[0]['action'] == "UPDATE") print ("<td class='bg-warning' style='cursor: pointer' $popover>".$s[0]['point']."</td>");
                    else if ($s[0]['action'] == "DELETE") print ("<td class='bg-danger' style='cursor: pointer' $popover><del>".$s[0]['point']."</del></td>");
                }

            }
            else {
                print("<td></td>");
            }


        }
        print("</tr>\n");
    }
    print("</table>");
}