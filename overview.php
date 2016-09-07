<?php
/**
 * Created by PhpStorm.
 * User: bhansen
 * Date: 07/09/16
 * Time: 22:23
 */


setlocale(LC_ALL, "da_DK");
spl_autoload_register(function ($class) {
    include 'classes/' . $class . '.php';
});
include "config.php";

if ($_GET['postoverview'] == LOGCODE) {
    $db = new DbModel();
    $uniqteam = $db->queryToArray("select concat(groups,id) cid, id from tivoli2016_teams order by groups, id");
    $uniqpost = $db->queryToArray("select pc.postid,group_concat(DISTINCT p.mobile SEPARATOR '<br />') mobile from tivoli2016_postcheckin_change_log pc left join tivoli2016_postcheckin p on pc.postid = p.postid GROUP BY pc.postid ORDER BY LPAD(lower(pc.postid), 10,0)");

    print("<h3>Post / Point Oversigt</h3><table class='table table-striped table-bordered'><tr><th></th>");
    foreach ($uniqpost as $post) {
        print("<th>".$post['postid'].($post['mobile'] ? "<br/><span style='font-weight: normal; font-size: 10px'>Postmandskab:<br />".$post['mobile']."</span></th>" : ""));
    }
    print("</tr>");

    foreach($uniqteam as $team) {
        print("<tr><th>".$team['cid']."</th>");
        foreach ($uniqpost as $post) {
            $s = $db->queryToArray("select point from tivoli2016_score where postid = ".$post['postid']." and teamid = ".$team['id']);
            if($s[0]['point']) {
                print("<td class='bg-success'>".$s[0]['point']."</td>");
            }
            else
            {
                print("<td class='bg-warning'></td>");
            }

        }
        print("</tr>\n");
    }
    print("</table>");
}