<?php

/**
 * Created by PhpStorm.
 * User: bhansen
 * Date: 08/08/16
 * Time: 20:24
 */


session_start();
if(!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
}

else if($_SESSION['loggedin'] != 1) header('Location: login.php');

else {
setlocale(LC_ALL, "da_DK");
spl_autoload_register(function ($class) {
    include 'classes/' . $class . '.php';
});

    ?>
    <!DOCTYPE html>
    <html lang="da">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="author" content="Brian Hauge Hansen">
        <meta name="description" content="FDF og spejderne indtager Tivoli">
        <title>FDF og spejderne indtager Tivoli - Score</title>
        <link rel="canonical" href="http://haugemedia.net/tivoli2016/">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
        <style>
            .tab-pane {
                padding-top: 80px !important;
            }
            .site-wrapper {
                margin: auto;
                margin-top: 25vh;
                width: 200px;
            }
        </style>
    </head>
    <body>

    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
        <a class="navbar-brand" href="admin.php">Dashboard</a>
        <button class="navbar-toggler d-lg-none" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsExampleDefault">
            <ul class="navbar-nav mr-auto" role="tablist" id="myTab">
                <li class="nav-item" role="presentation">
                    <a class="nav-link" href="#trace" aria-controls="trace" role="tab" data-toggle="tab">SMS Trafik <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" href="#overview" aria-controls="overview" role="tab" data-toggle="tab">Hold Oversigt</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" href="#log" aria-controls="log" role="tab" data-toggle="tab">Log</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" href="#postoverview" aria-controls="postoverview" role="tab" data-toggle="tab">Post / Point Oversigt</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" href="#smsflow" aria-controls="smsflow" role="tab" data-toggle="tab">SMS Flow</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" href="#sendsms" aria-controls="sendsms" role="tab" data-toggle="tab">Send SMS</a>
                </li>
            </ul>
            <form id="userform" class="form-inline mt-2 mt-md-0">
                <button class="btn btn-outline-success my-2 my-sm-0" type="button"><?php print(ucfirst($_SESSION['userinfo']['user'])); ?></button>
            </form>
        </div>
    </nav>

    <div class="container-fluid mainbody">
        <div class="row">
            <div class="col-md-12">
                <?php


                $db = new DbModel();

                ?>
                <div>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="dash">
                            <h2>My Dashboard</h2>
                            <hr>
                            <?php
                            ?>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="trace">
                            <h2>SMS Trafik</h2>
                            <hr>
                            <?php
                            // Trace
                            $sql = "select DATE_FORMAT(tstamp, '%Y-%m-%d %H:%i:%S') tid,msisdn mobil,input modtaget,output sendt from tivoli2016_trace ORDER BY tstamp desc limit 20";
                            $result = $db->printResultTable($sql);
                            print($result['table']);
                            ?>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="overview">
                            <?php
                            // Team Overview
                            $sql = "select concat(t.groups,t.id) id, t.name holdnavn, t.kreds 'kreds / gruppe', t.leader holdleder, t.mobile mobil, t.email, t.numberofmembers antal, if(sum(s.point), sum(s.point), 0) point from tivoli2016_teams t left join tivoli2016_score s on s.teamid = t.id group by t.id order by t.groups, t.id asc";
                            $result = $db->printResultTable($sql);
                            print("<h2>Antal hold: ".$result['count']." - Antal deltagere: ".$db->getMemberCount()."</h2><hr>");
                            print($result['table']);
                            ?>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="log">
                            <h2>Log fra denne server:</h2>
                            <hr>
                            <pre style='font-size: 10px'>
                                <?php
                                    $cmd = "tail -n50 logs/log_".date("Y-m-d").".txt";
                                    print(str_replace(PHP_EOL, '<br />', shell_exec($cmd)));
                                ?>
                            </pre>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="postoverview">
                            <div class="site-wrapper">
                                 <div><img src="dist/gears.gif" ></div>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="smsflow">
                            <p class="bg-primary" style="padding: 15px;"><a href="Tivoli_2016_SMS_Flow.pdf" style="color: #FFF">Download Tivoli 2016 SMS Flow.pdf</a></p>
                            <p><img src="Tivoli_2016_SMS_Flow.png" width="800" /></p>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="sendsms">
                            <h2>Send SMS</h2>
                            <hr>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="userinfo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Brugerinfo</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                </div>
                <div class="modal-body">
                    <table class="table">
                        <tr><th>Brugernavn</th><td><?php print($_SESSION['userinfo']['user']); ?></td></tr>
                        <tr><th>Oprettet</th><td><?php print($_SESSION['userinfo']['created']); ?></td></tr>
                        <tr><th>Opdateret</th><td><?php print($_SESSION['userinfo']['updated']); ?></td></tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="logout">Logud</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Luk</button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.1.0.min.js" integrity="cCueBR6CsyA4/9szpPfrX3s49M9vUU5BgtiJj06wt/s=" crossorigin="anonymous" ></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>
    <script type="text/javascript">
        $().ready(function() {
            $("#postoverview").load('overview.php');

            $('#postoverview').on('focus', '.bg-success, .bg-warning, .bg-danger', function () {
                $( this ).popover('show');
            });

            setInterval(function(){ $("#postoverview").load('overview.php'); }, 60000);

            $('#myTab a').click(function(e) {
                e.preventDefault();
                $(this).tab('show');
            });

            // store the currently selected tab in the hash value
            $("ul.navbar-nav > li > a").on("shown.bs.tab", function(e) {
                var id = $(e.target).attr("href").substr(1);
                window.location.hash = id;
                $("ul.navbar-nav > li > a").removeClass('active');
                $(e.target).addClass('active');
            });



            // on load of the page: switch to the currently selected tab
            var hash = window.location.hash;
            $('#myTab a[href="' + hash + '"]').tab('show');

            $('#userform').on('focus', 'button', function () {
                $('#userinfo').modal('show');
            });
            
            $('.modal-footer').on('focus','#logout', function () {
                window.location.href = "login.php?logout";
            })

        });

    </script>
    </body>
    </html>

<?php } ?>