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
    if(isset($_GET['start'])) $graphstart = $_GET['start'];
    else $graphstart = date('Y-m-d H:m',strtotime("-1 day"));
    if(isset($_GET['end'])) $graphend = $_GET['end'];
    else $graphend = date('Y-m-d H:m');

    print($graphstart."<br>".$graphend);
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
        <link rel="canonical" href="http://haugemedia.net/tivoli2018/">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.0-RC3/css/bootstrap-datepicker.min.css">
        <style>
            .tab-pane {
                padding-top: 80px !important;
            }
            .site-wrapper {
                margin: auto;
                margin-top: 25vh;
                width: 200px;
            }

            .string { color: green; }
            .number { color: darkorange; }
            .boolean { color: blue; }
            .null { color: magenta; }
            .key { color: red; }
            .highcharts-credits {
                display: none;}

            .collapsing {
                position: relative;
                height: 0;
                overflow: hidden;
                -webkit-transition: height .10s ease;
                -o-transition: height .10s ease;
                transition: height .10s ease;
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
                    <a class="nav-link" href="#postmandskaber" aria-controls="postmandskaber" role="tab" data-toggle="tab">Postmandskab</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" href="#log" aria-controls="log" role="tab" data-toggle="tab">Log</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" href="#postoverview" aria-controls="postoverview" role="tab" data-toggle="tab">Post / Point Oversigt</a>
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
                            <div id="containerteams" style="min-width: 310px; height: 200px; margin: 0 auto"></div>
                            <div id="container" style="min-width: 310px; height: 200px; margin: 0 auto"></div>

                        </div>


                        <div role="tabpanel" class="tab-pane" id="trace">
                            <h2>SMS Trafik</h2>
                            <hr>
                            <?php
                            // Trace
                            $sql = "select created_at as Tid,Direction,msisdn as Fra,`to` as Til,text,`remaining-balance`,messageId,Status from tivoli2018_smsgw ORDER BY created_at desc limit 100";
                            $result = $db->printResultTable($sql);
                            print($result['table']);
                            ?>
                        </div>


                        <div role="tabpanel" class="tab-pane" id="overview">
                            <?php
                            // Team Overview
                            $sql = "select concat(t.groups,t.id) id, t.name holdnavn, t.kreds 'kreds / gruppe', t.leader holdleder, t.mobile mobil, t.email, t.numberofmembers antal, if(sum(s.point), sum(s.point), 0) point from tivoli2018_teams t left join tivoli2018_score s on s.teamid = t.id group by t.id order by t.groups, t.id asc";
                            $result = $db->printResultTable($sql);
                            print("<h2>Antal hold: ".$result['count']." - Antal deltagere: ".$db->getMemberCount()."</h2><hr>");
                            print($result['table']);
                            ?>
                        </div>

                        <div role="tabpanel" class="tab-pane" id="postmandskaber">
                            <?php
                            // Team Overview
                            $sql = "select * from tivoli2018_crew order by gametype";
                            $result = $db->printResultTable($sql);
                            print("<h2>Antal postmandskaber: ".$result['count']."</h2><hr>");
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



                        <div role="tabpanel" class="tab-pane" id="sendsms">
                            <h2>Send SMS</h2>
                            <hr>
                            <form class="form-horizontal" id="sendSMS" method="post">
                                <div class="form-group">
                                    <label for="team" class="col-sm-2 control-label">Modtagere</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="receivers" id="receivers" placeholder="Modtagere">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="team" class="col-sm-2 control-label">Fra</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="from" id="from" placeholder="Modtagere" value="<?php print(SMS_FROMNAME) ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="leader" class="col-sm-2 control-label">Besked</label>
                                    <div class="col-sm-10">
                                        <textarea rows="5" type="text" class="form-control" name="message" id="message" placeholder="Besked"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <button type="submit" class="btn btn-default">Send</button>
                                    </div>
                                </div>
                            </form>
                            <div class="col-sm-offset-2 col-sm-10">
                                <hr>
                                <div id="sendSmsResult"></div>
                            </div>
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
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.0-RC3/js/bootstrap-datepicker.min.js"></script>


    <script type="text/javascript">

        $().ready(function() {
            getGraphdata(chart, '<?php print($graphstart); ?>','<?php print($graphend); ?>','');
            getGraphdata(chartTeams, '<?php print($graphstart); ?>','<?php print($graphend); ?>','teams');
            $("#postoverview").load('overview.php');
            $('#postoverview').on('focus', '.bg-success, .bg-warning, .bg-danger', function () {
                $( this ).popover('show');
            });
            setInterval(function(){ $("#postoverview").load('overview.php'); }, 600000);
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

            $("ul.navbar-nav > li > a").on("click", function(e) {
                $("button.navbar-toggler").click();
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

        $("#sendSMS").submit(function( event ){
            event.preventDefault();
            $.post('outgoingEndpoint.php', $('#sendSMS').serialize(), function (data) {
                obj = JSON.parse(data);
            })
            .done( function() {
                var str = JSON.stringify(obj, undefined, 4);
                if(obj.status && (obj.message.code == "201" || obj.message.code == "200")) { state = "success"; icon = "fa fa-check-circle"; }
                else if(obj.message.code == "500") { state = "warning"; icon = "fa fa-exclamation-triangle"; }
                else { state = "danger"; icon = "fa fa-exclamation-triangle"; }

                $( "#sendSmsResult" ).html( "<div class='alert alert-"+state+"' role='alert'><span class='"+icon+"' aria-hidden='true'></span></div><pre>"+syntaxHighlight(str)+"</pre>" );
            })
            .fail( function () {
                $("#sendSmsResult").html("fejl");
            });
        });


        function syntaxHighlight(json) {
            json = json.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
            return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function (match) {
                var cls = 'number';
                if (/^"/.test(match)) {
                    if (/:$/.test(match)) {
                        cls = 'key';
                    } else {
                        cls = 'string';
                    }
                } else if (/true|false/.test(match)) {
                    cls = 'boolean';
                } else if (/null/.test(match)) {
                    cls = 'null';
                }
                return '<span class="' + cls + '">' + match + '</span>';
            });
        }



Highcharts.setOptions({
    global: {
        useUTC: false
    }
});
var chart = Highcharts.chart('container', {
    chart: {
        type: 'spline',
        zoomType: 'xy'
    },
    title: {
        text: 'SMS'
    },

    yAxis: {
        title: {
            text: ''
        },
        labels: {
            formatter: function () {
                return this.value;
            }
        }
    },

    xAxis: {
        type: 'datetime'
    },

    plotOptions: {
        spline: {
            marker: {
                enabled: false,
                symbol: 'circle',
                radius: 2,
                states: {
                    hover: {
                        enabled: true
                    }
                }
            }
        }
    },
    exporting: { enabled: false },
    tooltip: {
        useHTML: true,
        headerFormat: '{point.key}: <span style="color: {series.color}; font-weight: bold">{point.y}</span>',
        pointFormat: '',
        footerFormat: ''
    },
    series: [{showInLegend: false}]
});

var chartTeams = Highcharts.chart('containerteams', {
    chart: {
        type: 'spline',
        zoomType: 'xy'
    },
    title: {
        text: 'Tilmeldte hold'
    },

    yAxis: {
        title: {
            text: ''
        },
        labels: {
            formatter: function () {
                return this.value;
            }
        }
    },

    xAxis: {
        type: 'datetime',

    },

    plotOptions: {
        spline: {
            marker: {
                enabled: false,
                symbol: 'circle',
                radius: 2,
                states: {
                    hover: {
                        enabled: true
                    }
                }
            }
        }
    },
    exporting: { enabled: false },
    tooltip: {
        useHTML: true,
        headerFormat: '{point.key}: <span style="color: {series.color}; font-weight: bold">{point.y}</span>',
        pointFormat: '',
        footerFormat: ''
    },
    series: [{showInLegend: false}]
});

        function getGraphdata(chart, start, end, type) {
            $.get( "graphdata.php", { type: type, start: start, end: end } )
                .done(function( data ) {
                    var data1 = JSON.parse(data);
                    chart.update({
                        chart: {
                            inverted: false,
                            polar: true
                        },
                        series: {
                            name: start+' - '+end,
                            data: data1
                        }
                    });
                });
        }
    </script>
    </body>
    </html>

<?php } ?>