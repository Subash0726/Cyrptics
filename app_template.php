<?php
/**
 * Created by PhpStorm.
 * User: azizt
 * Date: 2/20/2017
 * Time: 6:17 PM
 */

session_start();
$msg = "";
$error_msg = "";
$current_score = 0;

include_once('php_web/include/common.php');
include_once('php_web/include/connector.php');

if (isset($_POST['logout']))
    ses_sign_out();

include_once('php_web/include/init_validate.php');


$con = connectAccordingly();

$query = "SELECT qsnid FROM answered WHERE userid=?";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $_SESSION[$ses_uid_key]);
$stmt->execute();
$stmt->bind_result($row['qsnid']);
while ($stmt->fetch()) {
    $qsnid = $row['qsnid'];
    array_push($answered_qsns, $qsnid);
}
$stmt->close();

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

if (isset($_POST['qsn_submitted'])) {
    //echo "ID".$_SESSION[$ses_uid_key];

    $qsnid = validateInteger($_POST['ctfqsnid']);
    $ans = validateText($_POST['ctfans']);
    $current_qsn = validateText($_POST['qsn_no']);

    $query = "SELECT id FROM answered WHERE userid=? AND qsnid=?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("ii", $_SESSION[$ses_uid_key], $qsnid);
    $stmt->execute();
    $stmt->bind_result($row['sno']);
    if ($stmt->fetch()) {
        $error_msg = "You have already answered this question!";
        $stmt->close();
    } else {
        $stmt->close();

        $query = "SELECT score FROM questions WHERE id=? AND ans=?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("is", $qsnid, $ans);
        $stmt->execute();
        $stmt->bind_result($row['score']);
        if ($stmt->fetch()) {
            $score = $row['score'];
            $stmt->close();

            $query = "INSERT INTO answered(userid,qsnid) VALUES(?,?);";
            $stmt = $con->prepare($query);
            $stmt->bind_param("ii", $_SESSION[$ses_uid_key], $qsnid);
            $stmt->execute();
            $stmt->close();

            $msg = "Correct Answer";
            header("Location: dashboard.php?showmsg=" . $msg."&curqsn=".$current_qsn);
        } else {
            $stmt->close();
            $error_msg = "Wrong Answer! Try Again!";
        }
    }
}

if (isset($_GET['showmsg'])) {
    $msg = $_GET['showmsg'];
}
if (isset($_GET['curqsn'])) {
    $current_qsn = $_GET['curqsn'];
}


?>

    <html>
    <head>
        <title>Dashboard - <?php echo $ctf_title_addon; ?></title>

        <link rel="shortcut icon" href="">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.5/css/materialize.min.css" type="text/css"
              rel="stylesheet" media="screen,projection"/>
        <link rel="stylesheet" type="text/css" href="css/common.css"/>


        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.5/js/materialize.min.js"></script>
        <script src="js/Vague.js"></script>
        <script src="js/common.js"></script>

        <link href="css/materialize.min.css" rel="stylesheet" type="text/css"/>

        <style>
            .qsn {
                margin-left: 20px;
                margin-right: 20px;
                background-color: #333;
                color: white;
                padding: 10px 0px;
            }

            .qsn .chal_title {
                font-size: 22px;
                letter-spacing: 3px;
                padding: 0 20px;
            }

            .qsn .prompt {
                color: #ddd;
                font-size: 18px;
            }

            .qsn .ans {
                color: #ddd;
                font-size: 16px;
            }

            .qsn .ans_wrap {
                margin: 20px 20px 0px 20px;
            }

            .qsn .btn-floating {
                width: 30px;
                height: 30px;
                vertical-align: bottom;
            }

            .qsn .btn-floating i {
                line-height: 31px;
            }

            .ctf .title {
                font-weight: 300;
                font-size: 42px;
                color: rgb(46, 234, 179);
                letter-spacing: 10px;
            }

            .ctf .title_wrap {
                margin-top: 110px
            }

            @media only screen and (max-width: 1000px) {
                .ctf .title {
                    font-size: 24px;
                    font-weight: 400;
                }

                .ctf .title_wrap {
                    margin-top: 150px
                }
            }

            .ctf .info {
                font-weight: 400;
                font-size: 32px;
                color: rgb(78, 102, 95);
                letter-spacing: 3px;
            }

            .ctfcol {
                background-color: rgba(22, 229, 170, 0.7);
            }

            .ctfcol_border {
                border-top: 120px solid rgba(22, 229, 170, 0.7);
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
                <div id="top" class="fixedTop" style="height: 190px">

                    <div
                        style="position:fixed; width: 100%; height: 190px; background-color: rgba(59, 91, 79,0.8);"></div>

                    <div class="ctfcol" style="position:fixed; width: 100%; height: 70px;"></div>
                    <div class="triangle-down ctfcol_border"
                         style="margin-top: 70px"></div>

                    <div style="position:fixed; width: 100%; height: 42px; background: rgba(0,0,0,0.2)"></div>
                    <div class="triangle-down" style="border-top: 150px solid rgba(0,0,0,0.2); margin-top: 42px"></div>

                    <div class="ctfcol" style="position:fixed; width: 100%; height: 50px;"></div>
                    <div class="triangle-down ctfcol_border"
                         style="margin-top: 50px"></div>

                    <div class="right-align" style="position: fixed; right: 30px; margin-top: 5px; margin-right: 0px">
                    <span class="info">
                        Hey, Team <?php echo $_SESSION[$ses_uname_key]; ?>!<br/>
                    </span>
                        <span class="info" style="color:rgb(57, 79, 74); font-weight: 300">
                        Score: <?php echo $current_score; ?><br/>
                    </span>
                        <a class="info" href="leaderboard.php"
                           style="color: rgb(57, 79, 74); font-weight: 300; font-size: 24px; border-bottom: 1px solid rgb(57, 79, 74);">Go
                            To Leaderboard</a>
                    </div>

                    <div style="position: fixed; left: 10px; top: 10px">

                        <div onclick="$('#logout_form').submit();" class="chip waves-effect z-depth-1 hoverable"
                             style="background-color: rgba(64, 109, 96,0.5); cursor: pointer">
                            <i class="close mdi-content-reply small white-text" style="margin-top: -1px"></i>
                            <span class=" white-text" style="font-size: 20px; vertical-align: super; margin-left: 5px">Logout</span>
                            <form id="logout_form" method="post" action="dashboard.php">
                                <input type="hidden" name="logout">
                            </form>
                        </div>
                    </div>

                    <div class="title_wrap" style="position: fixed; left: 20px;">
                    <span class="title" style="">
                        Capture The Flag
                    </span>
                    </div>


                </div>
                <div id="content"
                     style=" min-height: 80%;  background-color: rgba(0,0,0,0.5); padding: 40px 50px 40px 50px">
                    <div class="row">
                        <div class="col s12" style="">
                            <div class="center"
                                 style="width: 100%; background-color: rgba(57, 79, 74,0.8); padding-bottom: 5px">
                                <div class="row green darken-1" style="border-radius: 4px 4px 0 0; padding: 5px">
                                <span class="white-text"
                                      style="font-size: 24px; letter-spacing: 3px; font-weight: 300;">Challenges</span>
                                </div>

                                <?php
                                $query = "SELECT id, qsn, score FROM questions";
                                $result = $con->query($query);
                                $i = 1;
                                while ($row = $result->fetch_assoc()) {
                                    $is_answered = in_array($row['id'], $answered_qsns);
                                    ?>

                                    <form method="post" action="dashboard.php">
                                        <div class="row z-depth-1-half hoverable qsn left-align" id="qsn<?php echo $i; ?>">
                                            <div class="row" style="margin-bottom: 10px">
                                                <span class="chal_title yellow-text darken-2"
                                                      style="">Challenge <?php echo $i; ?>
                                                </span>
                                                <?php
                                                if ($is_answered) {

                                                    ?>
                                                    <span class="btn-floating cyan accent-4">
                                                        <i class="material-icons white-text">done</i>
                                                    </span>
                                                    <?php

                                                }
                                                ?>
                                                <span class="right chal_title <?php if($is_answered)echo "cyan-text accent-4"; ?>" style="font-size: 18px; font-weight: 300px">Score: <?php echo $row['score']; ?></span>

                                            </div>
                                            <div class="divider yellow darken-2" style=""></div>
                                            <div class="row ans_wrap" style="">
                                                <div class="row"><span class="prompt"><?php echo $row['qsn']; ?></span>
                                                </div>
                                                <div class="row" style="margin-top: 40px">
                                                    <div class="input-field ans">
                                                        <input name="ctfans" id="ans<?php echo $i; ?>" type="text" <?php if($is_answered)echo "disabled"; ?>>
                                                        <label for="<?php echo $i; ?>">Answer</label>
                                                    </div>
                                                </div>
                                                <?php
                                                if (!$is_answered) {

                                                    ?>
                                                    <div class="row right-align">

                                                    <span class="btn waves-effect" onclick="submitCurrentForm(this)">
                                                        SUBMIT
                                                    </span>
                                                        <input type="submit" style="margin-top: 8px; display: none"
                                                               name="qsn" value="SUBMIT">
                                                        <input type="hidden" name="qsn_no"
                                                               value="<?php echo $i; ?>">
                                                        <input type="hidden" name="qsn_submitted"
                                                               value="true">
                                                        <input type="hidden" name="ctfqsnid"
                                                               value="<?php echo $row['id']; ?>">
                                                    </div>
                                                    <?php

                                                }
                                                ?>
                                            </div>

                                        </div>
                                    </form>

                                    <?php
                                    $i++;
                                }
                                $result->free();

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
            if($current_qsn > 0){
            ?>
            console.log($("#qsn<?php echo $current_qsn; ?>").offset().top);
            $('#top-image').animate({
                scrollTop: $("#qsn<?php echo $current_qsn; ?>").offset().top-250
            },1000);
            <?php
            }
            ?>



            redrawShapes();
            $(window).resize(redrawShapes);

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