<?php
/**
 * Created by PhpStorm.
 * User: azizt
 * Date: 2/20/2017
 * Time: 6:17 PM
 */

$THIS_PAGE = "dashboard.php";
$TOP_REDIRECT_PAGE = "leaderboard.php";
$TOP_REDIRECT_TITLE = "Go to Leaderboard";


session_start();
$msg = "";
$error_msg = "";
$current_score = 0;
$answered_qsns = [];
$current_qsn = -1;

include_once('php_web/include/common.php');
include_once('php_web/include/connector.php');
include_once('php_web/include/timer.php');

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

if ($current_score == "") {
    $current_score = 0;
}

if (isset($_POST['start_capturing'])) {

    $time_str = date_format(date_create(), "Y-m-d H:i:s");

    $query = "UPDATE users SET start_time=? WHERE id=?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("si", $time_str, $_SESSION[$ses_uid_key]);
    if ($stmt->execute()) {
        ses_update_stime($time_str);
        $msg = "Let's Go!!";
        header("Location: dashboard.php?showmsg=" . $msg);

    } else {
        $error_msg = "Error while trying to start capturing";
    }
    $stmt->close();

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
            header("Location: dashboard.php?showmsg=" . $msg . "&curqsn=" . $current_qsn);
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
        <link href="css/material-icons.css" rel="stylesheet">
        <link href="css/materialize.min.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" type="text/css" href="css/common.css"/>
        <link rel="stylesheet" type="text/css" href="css/top_main.css"/>


        <script src="js/jquery-3.1.1.min.js"></script>
        <script src="js/materialize.min.js"></script>
        <script src="js/common.js"></script>
        <script src="js/material_funcs.js"></script>


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
             style="background-image: url('res/imgs/bg.jpg'); background-color:rgba(0,0,0,0.5); background-size: 100%; background-blend-mode: multiply;  background-position: center">

            <div id="cover" style="width: 100%; height: 100%; text-align: center">
                <?php include_once("php_web/modules/top_main.php"); ?>

                <?php
                if ($start_timestamp == null) {
                    ?>

                    <form method="post" action="dashboard.php">
                        <div id="start_capturing center" style="margin-top: 100px; padding-left: 5%; padding-right: 5%">
                            <div class="row" style="margin-bottom: 50px">
                                <span class="cyan-text" style="font-size: 32px; letter-spacing: 3px; ">Welcome to Capture the Flags 2017</span>
                            </div>

                            <div class="row">
                            <span class="white-text" style="font-size: 20px; font-weight: 400; letter-spacing: 3px">
                                Here, you will have 6 HOURS to answer a set of questions once you press the button below.<br/><br/> Remember that once you click on "START CAPTURING", the timer will keep running even when you are logged out.<br/><br/> Once you are ready, press the button below to start capturing.<br/>
                            </span>
                            </div>
                            <input type="hidden" name="start_capturing" value="true">
                            <span onclick="submitCurrentForm(this)" class="green white-text waves-effect hoverable"
                                  style="font-size: 26px; padding: 16px 24px; font-weight: 300; letter-spacing: 3px; cursor: pointer; border-radius: 5px;margin-top: 100px">Start Capturing</span>
                        </div>
                    </form>
                    <?php
                } else if ($is_overtime) {
                    ?>
                    <div id="start_capturing center" style="margin-top: 100px; padding-left: 5%; padding-right: 5%">
                        <div class="row" style="margin-bottom: 50px">
                            <span class="cyan-text" style="font-size: 32px; letter-spacing: 3px; ">Thank you for participating!</span>
                        </div>

                        <div class="row">
                                <span class="white-text" style="font-size: 20px; font-weight: 400; letter-spacing: 3px">

                                </span>
                        </div>
                    </div>
                    <?php
                } else {

                    ?>

                    <div id="content"
                         style="background-color: rgba(0,0,0,0.5); padding: 40px 50px 40px 50px">
                        <div class="row">
                            <div class="col s12" style="">
                                <div class="center"
                                     style="width: 100%; background-color: rgba(57, 79, 74,0.8); padding-bottom: 5px">
                                    <div class="row green darken-1"
                                         style="border-radius: 4px 4px 0 0; padding: 5px">
                                <span class="white-text"
                                      style="font-size: 24px; letter-spacing: 3px; font-weight: 300;">Challenges</span>
                                    </div>

                                    <?php
                                    $query = "SELECT id, qsn, score, hint, link, filepath FROM questions ORDER BY score ASC, id ASC";
                                    $result = $con->query($query);
                                    $i = 1;
                                    while ($row = $result->fetch_assoc()) {
                                        $is_answered = in_array($row['id'], $answered_qsns);
                                        ?>

                                        <form method="post" action="dashboard.php">
                                            <div class="row z-depth-1-half hoverable qsn left-align"
                                                 id="qsn<?php echo $i; ?>">
                                                <div class="row" style="margin-bottom: 10px">
                                                <span class="chal_title yellow-text darken-2"
                                                      style="">Challenge <?php echo $i; ?>
                                                </span>
                                                    <?php
                                                    if ($is_answered) {

                                                        ?>
                                                        <span
                                                            class="btn-floating cyan accent-4 waves-effect hoverable"
                                                            style="cursor: default">
                                                        <i class="material-icons white-text">done</i>
                                                    </span>
                                                        <?php

                                                    }
                                                    ?>
                                                    <span
                                                        class="right chal_title <?php if ($is_answered) echo "cyan-text accent-4"; ?>"
                                                        style="font-size: 18px; font-weight: 300px">Score: <?php echo $row['score']; ?></span>

                                                </div>
                                                <div class="divider yellow darken-2" style=""></div>
                                                <div class="row ans_wrap" style="">
                                                    <div class="row"><span
                                                            class="prompt"><?php echo $row['qsn']; ?></span>
                                                    </div>
                                                    <?php
                                                    if ($row['link'] != "") {
                                                        ?>
                                                        <div class="row">
                                                        <span style="font-weight: 500; letter-spacing: 2px;"><span
                                                                class="orange-text">Link:</span>
                                                        <a target="_blank" href="<?php echo $row['link']; ?>"><?php echo $row['link']; ?></a>
                                                        </div>
                                                        <?php
                                                    }
                                                    if ($row['filepath'] != "") {
                                                        ?>
                                                        <div class="row">
                                                        <span style="font-weight: 500; letter-spacing: 2px;"><span
                                                                class="orange-text">Download File:</span>
                                                        <a target="_blank" href="<?php echo $row['filepath']; ?>"><?php echo $row['filepath']; ?></a>
                                                        </div>
                                                        <?php
                                                    }
                                                    ?>
                                                    <div class="row" style="margin-top: 40px">
                                                        <div class="input-field ans">
                                                            <input name="ctfans" id="ans<?php echo $i; ?>"
                                                                   type="text" onkeyup="console.log('');"
                                                                   autocomplete="off" <?php if ($is_answered) echo "disabled"; ?>>
                                                            <label for="<?php echo $i; ?>">Answer</label>
                                                        </div>
                                                    </div>
                                                    <div class="row">

                                                        <div class="col s10 left-align no-padding">
                                                            <span class="cyan-text"
                                                                  style="font-weight: 500; letter-spacing: 2px;"><span
                                                                    class="orange-text">HINT: </span><?php echo $row['hint']; ?></span>
                                                        </div>
                                                        <div class="col s2 right-align no-padding">
                                                            <?php
                                                            if (!$is_answered) {

                                                                ?>
                                                                <span class="btn waves-effect"
                                                                      onclick="submitCurrentForm(this)">
                                                                SUBMIT
                                                                </span>
                                                                <input type="submit"
                                                                       style="margin-top: 8px; display: none"
                                                                       name="qsn" value="SUBMIT">
                                                                <input type="hidden" name="qsn_no"
                                                                       value="<?php echo $i; ?>">
                                                                <input type="hidden" name="qsn_submitted"
                                                                       value="true">
                                                                <input type="hidden" name="ctfqsnid"
                                                                       value="<?php echo $row['id']; ?>">

                                                                <?php

                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
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

                    <?php
                }
                ?>

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
            if($current_qsn > 0){
            ?>
            try {
                $('#top-image').animate({
                    scrollTop: $("#qsn<?php echo $current_qsn; ?>").offset().top - 250
                }, 1000);
            } catch (e) {

            }


            $("#qsn<?php echo $current_qsn; ?>").find("#ans<?php echo $current_qsn; ?>").focus();
            setTimeout(function () {
                <?php if(isset($_POST['qsn_submitted'])){
                ?>
                $("#qsn<?php echo $current_qsn; ?>").find("label").addClass("active");
                <?php

                }?>
            }, 500);

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