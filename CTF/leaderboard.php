<?php
/**
 * Created by PhpStorm.
 * User: azizt
 * Date: 2/20/2017
 * Time: 6:17 PM
 */

$THIS_PAGE="leaderboard.php";
$TOP_REDIRECT_PAGE="dashboard.php";
$TOP_REDIRECT_TITLE="Go to Dashboard";



session_start();
$msg = "";
$error_msg = "";
$current_score = 0;
$max_leaders=10;

include_once('php_web/include/common.php');
include_once('php_web/include/connector.php');
include_once('php_web/include/timer.php');

if (isset($_POST['logout']))
    ses_sign_out();

include_once('php_web/include/init_validate.php');


$con = connectAccordingly();

$query = "SELECT sum(q.score) FROM `answered` a INNER JOIN questions q ON q.id=a.qsnid WHERE userid=?";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $_SESSION[$ses_uid_key]);
$stmt->execute();
$stmt->bind_result($row['cur_score']);
if ($stmt->fetch()) {
    $current_score = $row['cur_score'];
}
$stmt->close();

if($current_score==""){
    $current_score=0;
}

?>

    <html>
    <head>
        <title>Leaderboard - <?php echo $ctf_title_addon; ?></title>

        <link rel="shortcut icon" href="">
        <link href="css/material-icons.css" rel="stylesheet">
        <link href="css/materialize.min.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" type="text/css" href="css/common.css"/>
        <link rel="stylesheet" type="text/css" href="css/top_main.css"/>


        <script src="js/jquery-3.1.1.min.js"></script>
        <script src="js/materialize.min.js"></script>
        <script src="js/common.js"></script>

<!--        <link href="css/materialize.min.css" rel="stylesheet" type="text/css"/>-->

        <style>
            .leaderboards {
                font-size: 26px;
                background-color: #333;
                color: white;
                padding: 10px 10px;
                margin-bottom: 0px;
            }

            .leaderboards .teamname {
                font-size: 20px;
                letter-spacing: 3px;
                padding: 5px 60px 5px 20px;
            }

            .leaderboards .teamscore {
                font-size: 20px;
                font-weight: 300;
                letter-spacing: 3px;
                padding: 5px 20px 5px 60px;
            }

            .leaderboards .ans {
                color: #ddd;
                font-size: 16px;
            }

            .leaderboards .ans_wrap {
                margin: 20px 20px 0px 20px;
            }

            .leaderboards .btn-floating {
                width: 30px;
                height: 30px;
                vertical-align: bottom;
            }

            .leaderboards .btn-floating i {
                line-height: 31px;
            }

            input[type=text], input[type=password] {
                color: rgba(255, 255, 255, 0.8);
                font-size: 18px;
                border-bottom-width: 2px;
                border-bottom-color: rgba(22, 224, 189, 0.7);
            }

            input[type=submit] {
                margin-top: 7px;
            }

            .input-field > label {
                left: 0;
                color: #bbb;
            }

            .input-field .material-icons {
                color: rgba(22, 224, 189, 0.7);
                font-size: 26px;
                margin-top: 10px
            }

            .input-field > .material-icons.active {
                color: rgba(22, 220, 100, 0.7);
            }

            .fixedTop * {
                z-index: 999;
            }


        </style>
    </head>
    <body class="ctf">
    <div class="blurrable">
        <div id="top-image"
             style="background-image: url('res/imgs/slide-bg.jpg'); background-color:rgba(0,0,0,0.5); background-size: 100%; background-blend-mode: multiply;  background-position: center">

            <div id="cover" style="width: 100%; height: 100%; text-align: center">
                <?php include_once ("php_web/modules/top_main.php");?>
                <div id="content"
                     style="background-color: rgba(0,0,0,0.5); padding: 40px 50px 40px 50px">
                    <div class="row">
                        <div class="col s12" style="">
                            <div class="center"
                                 style="width: 100%; background-color: rgba(57, 79, 74,0.8); padding-bottom: 5px">
                                <div class="row green darken-2" style="border-radius: 4px 4px 0 0; padding: 5px; margin-bottom: 0">
                                <span class="white-text"
                                      style="font-size: 26px; letter-spacing: 3px; font-weight: 300;">Leaderboard</span>
                                </div>

                                <div class="row leaderboards no-padding">
                                    <div class="col s6 right-align green teamname" style="font-size: 24px">Team Name</div>
                                    <div class="col s6 left-align green darken-1 teamscore" style="font-size: 24px">Score</div>
                                </div>

                                <?php
                                $query="SELECT a.userid userid,u.username username,sum(q.score) score FROM `answered` a INNER JOIN questions q ON q.id=a.qsnid LEFT JOIN users u ON a.userid=u.id GROUP BY a.userid ORDER BY score DESC, username ASC LIMIT ".$max_leaders;
                                $result=$con->query($query);

                                $i=1;
                                while($row=$result->fetch_assoc()){
                                    if($i%2==0){
                                        $dark1="dark";
                                        $dark2="normal_dark";
                                    }else{
                                        $dark2="dark";
                                        $dark1="normal_dark";
                                    }

                                    $user_icon="";
                                    if($_SESSION[$ses_uid_key]==$row['userid']){
                                        $user_icon="<i class='material-icons' style='margin-right: 20px; vertical-align: sub'>person_pin</i>";
                                    }else{
                                        $user_icon="";
                                    }
                                    ?>
                                    <div class="row leaderboards no-padding">
                                        <div class="col s6 right-align <?php echo $dark1 ?> teamname"><?php echo $user_icon.$row['username'] ?></div>
                                        <div class="col s6 left-align <?php echo $dark2 ?> teamscore"><?php echo $row['score'] ?></div>
                                    </div>

                                    <?php
                                    $i++;
                                }
                                $result->free();
                                ?>

                                <?php
                               /* $query="SELECT u.id userid,u.username username FROM users u WHERE u.id NOT IN (SELECT a.userid FROM answered a GROUP BY a.userid) ORDER BY username ASC";
                                $result=$con->query($query);

                                while($row=$result->fetch_assoc()){
                                    if($i%2==0){
                                        $dark1="dark";
                                        $dark2="normal_dark";
                                    }else{
                                        $dark2="dark";
                                        $dark1="normal_dark";
                                    }

                                    $user_icon="";
                                    if($_SESSION[$ses_uid_key]==$row['userid']){
                                        $user_icon="<i class='material-icons' style='margin-right: 20px; vertical-align: sub'>person_pin</i>";
                                    }else{
                                        $user_icon="";
                                    }
                                    */?><!--
                                    <div class="row leaderboards no-padding">
                                        <div class="col s6 right-align <?php /*echo $dark1 */?> teamname"><?php /*echo $user_icon.$row['username'] */?></div>
                                        <div class="col s6 left-align <?php /*echo $dark2 */?> teamscore">0</div>
                                    </div>

                                    --><?php
/*                                    $i++;
                                }
                                $result->free();*/
                                ?>

                            </div>
                        </div>

                    </div>
                </div>


            </div>

        </div>
    </div>

    <script src="js/classie.js"></script>
    <script src="js/transit.js"></script>
    <script>


        $(document).ready(function () {
            var newvalueX = $(window).width() / 16 * -1;
            var newvalueY = $(window).height() / 16 * -1;


            /*$('#top-image').css("background-position", newvalueX+"px "+newvalueY+"px");
             $("*").mousemove(function(e){

             var newvalueX=(e.pageX)/8*-1;
             var newvalueY=(e.pageY)/8*-1;
             $('#top-image').css("background-position", newvalueX+"px "+newvalueY+"px");
             //$('#top-image').stop().animate({'background-position-x': newvalueX+"px", 'background-position-y': newvalueY+"px"},50,"linear");

             });*/

            redrawShapes();
            $(window).resize(redrawShapes);


            <?php
            if($msg !== ""){
            ?>
            setMessageWindow("<?php echo $msg; ?>", TOAST_SUCCESS);
            <?php
            }
            if($error_msg !== ""){
            ?>
            setMessageWindow("<?php echo $error_msg; ?>", TOAST_FAIL);
            <?php
            }

            ?>
        });


        function redrawShapes() {
            $(".triangle-down").css("border-left", ($(document).width()) + "px solid transparent");
            $(".triangle-down:not(.shade)").css("border-right", ($(document).width()) + "px solid transparent");


            $(".triangle-down-inv").css("border-right", ($(document).width()) + "px solid transparent");
        }

        function submitCurrentForm(a) {
            $(a).closest('form').submit();
        }
    </script>
    </body>
    </html>
<?php
$con->close();
?>