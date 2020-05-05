<?php
session_start();
include_once('php_web/include/common.php');
include_once('php_web/include/connector.php');
include_once('php_web/include/password_hash.php');

$con = connectAccordingly();

$error_msg = "";


if (isset($_SESSION[$ses_uname_key])) {
    header("Location: dashboard.php");
} else {
    if (isset($_POST['sign_up_request'])) {
        $uname = validateText($_POST['new_username']);
        $pass = validateText($_POST['new_password']);
        $passcon = validateText($_POST['new_con_password']);
        $fname = validateText($_POST['new_fullname']);
        $fname2 = validateText($_POST['new_fullname2']);
        $kid = validateText($_POST['new_kid']);
        $kid2 = validateText($_POST['new_kid2']);

        if ($uname == "") {
            $error_msg = "Enter a team name";
        } else if ($pass == "") {
            $error_msg = "Enter a password";
        } else if ($passcon == "") {
            $error_msg = "Confirm your password";
        } else if ($pass !== $passcon) {
            $error_msg = "Passwords don't match";
        } else if ($fname == "") {
            $error_msg = "Enter Member 1's full name";
        } else if ($kid == "") {
            $error_msg = "Enter Member 1's Kriya ID";
        } else if ($fname2 == "") {
            $error_msg = "Enter Member 2's full name";
        } else if ($kid2 == "") {
            $error_msg = "Enter Member 2's Kriya ID";
        } else {
            //If validated and no errors found
            $user_exists = false;

            $query = "SELECT id FROM users WHERE username=?;";
            $stmt = $con->prepare($query);
            $stmt->bind_param("s", $uname);
            if ($stmt->execute()) {
                $stmt->store_result();
                if ($stmt->num_rows == 1) {
                    $user_exists = true;
                    $error_msg = "Team name already exists!";
                }
                $stmt->free_result();
            }
            $stmt->close();

            if (!$user_exists) {
                $hash = create_hash($pass);

                $query = "INSERT INTO users(fullname, fullname2, kriya_id, kriya_id2, username, password) VALUES(?,?,?,?,?,?);";
                $stmt = $con->prepare($query);
                $stmt->bind_param("ssssss", $fname, $fname2, $kid, $kid2, $uname, $hash);
                if ($stmt->execute()) {
                    //Registration Successful
                    $_POST['sign_in_request'] = true;
                    $_POST['username'] = $uname;
                    $_POST['password'] = $pass;
                } else {
                    $error_msg = "Error creating user";
                }

                $stmt->close();
            }

        }

    }

    if (isset($_POST['sign_in_request'])) {
        $username = validateText($_POST['username']);
        $password = validateText($_POST['password']);

        if ($username == "") {
            $error_msg = "Enter a team name";
        } else if ($password == "") {
            $error_msg = "Enter a password";
        } else {
            $query = "SELECT id,username,fullname,password,start_time from users WHERE username=?";
            $stmt = $con->prepare($query);
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->bind_result($row['id'], $row['username'], $row['fullname'], $row['password'], $row['start_time']);
            if ($stmt->fetch()) {
                if (validate_password($password, $row['password'])) {
//                    echo "STime: ".$row['start_time'];
                    ses_sign_in($row['id'], $row['username'], $row['start_time']);
                    header("Location: dashboard.php");
                } else {
                    $error_msg = "Wrong teamname or password";
                }
            } else {
                $error_msg = "Wrong teamname or password";
            }
            $stmt->close();
        }
    }
}

?>
    <html>
    <head>
        <title><?php echo $ctf_title_addon; ?> - Welcome</title>
        <link rel="shortcut icon" href="">

        <link rel="stylesheet" type="text/css" href="css/common.css"/>
        <link href="css/material-icons.css" rel="stylesheet">
        <link href="css/materialize.min.css" rel="stylesheet" type="text/css"/>

        <script src="js/jquery-3.1.1.min.js"></script>
        <script src="js/materialize.min.js"></script>
        <script src="js/common.js"></script>




        <style>
            .ctf .title {
                position: absolute;
                font-weight: 300;
                font-size: 35px;
                margin-left: -217px;
                margin-top: 40px;
                color: #000;
                letter-spacing: 20px
            }

            .ctfcol {
                background-color: rgba(22, 229, 170, 0.7);
            }

            .ctfcol_border {
                border-top: 200px solid rgba(22, 229, 170, 0.7);
            }

            input[type=text], input[type=password] {
                color: rgba(255, 255, 255, 0.8);
                font-size: 18px;
                border-bottom-width: 2px;
                border-bottom-color: rgba(22, 224, 189, 0.7);
            }

            .input-field .material-icons {
                color: rgba(22, 224, 189, 0.7);
                font-size: 26px;
                margin-top: 10px
            }

            .input-field > .material-icons.active {
                color: rgba(22, 220, 100, 0.7);
            }

        </style>
    </head>
    <body class="ctf">
    <div class="blurrable">
        <div id="top-image"
             style="background-image: url('res/imgs/slide-bg.jpg'); background-color:rgba(0,0,0,0.7); background-blend-mode: multiply;  background-position: center">

            <div id="cover" style="width: 100%; height: 100%; text-align: center">
                <div id="top" style="height: 240px">
                    <div class="ctfcol" style="position:fixed; width: 100%; height: 40px;"></div>
                    <div class="triangle-down ctfcol_border"
                         style="margin-top: 40px"></div>

                    <div style="position:fixed; width: 50%; height: 40px; background: rgba(0,0,0,0.08)"></div>
                    <div class="triangle-down shade"
                         style="width:50%; border-top: 200px solid rgba(0,0,0,0.08); margin-top: 40px"></div>

                    <div style="position:fixed; width: 100%; height: 24px; background: rgba(0,0,0,0.2)"></div>
                    <div class="triangle-down" style="border-top: 200px solid rgba(0,0,0,0.2); margin-top: 24px"></div>

                    <div class="ctfcol" style="position:fixed; width: 100%; height: 20px;"></div>
                    <div class="triangle-down ctfcol_border"
                         style="margin-top: 20px"></div>

                    <div style="position:fixed; width: 50%; height: 20px; background: rgba(0,0,0,0.08)"></div>
                    <div class="triangle-down shade"
                         style="width:50%; border-top: 200px solid rgba(0,0,0,0.08); margin-top: 20px"></div>


                    <center>
                        <span class="title" style="">CAPTURE THE<br/>FLAG</span>
                    </center>

                </div>
                <div id="main_form" class="row" style="margin-top: 50px; margin-left: 50px; margin-right: 50px">
                    <div id="sign_in_form" class="col s12 l4 push-l4 no-padding"
                         style="border: 2px solid rgba(22,224,189,0.7); border-radius: 5px; background: rgba(0,0,0,0.5)">
                        <form method="post" action="index.php">
                            <div class="row" style="margin-bottom: 0">
                                <div class="center col s12 no-padding"
                                     style="text-align: center; background: rgba(22,224,189,0.5)">
                                    <div class="col s6 push-s3">
                                        <span
                                            style="font-size: 30px; color: rgba(255,255,255,0.7); font-weight: 400; letter-spacing: 10px;">LOGIN</span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div style="height: 2px; width: 100%; background: rgba(22,224,189,0.7)"></div>
                            </div>

                            <div style="width: 80%; margin-left: 10%">
                                <div class="row">
                                    <div class="input-field col s12">
                                        <i class="material-icons prefix" style="">perm_identity</i>
                                        <input name="username" id="username" type="text" class="">
                                        <label class="active" for="username">Team Name</label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="input-field col s12">
                                        <i class="material-icons prefix" style="">lock_outline</i>
                                        <input name="password" id="password" type="password" class="">
                                        <label for="password">Password</label>
                                    </div>
                                </div>

                                <div class="row" style="margin-top: 30px">
                                    <div class="col s12">
                                        <input style="display: none" type="submit" value="true">
                                        <input type="hidden" name="sign_in_request" value="true">
                                        <span onclick="submitCurrentForm(this)"
                                              class="btn waves-effect ">LOGIN</span>
                                    </div>
                                </div>

                                <div class="row" style="margin-top: 40px">
                                    <div class="col s12">
                                        <span id="sign_up_direct" class="" onclick="show_signup()"
                                              style="font-size:18px; color: rgba(22,224,189,0.8); cursor: pointer">Don't have an account? Click here to register</span>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>


                    <div id="sign_up_form" class="col s12 l4 push-l4 no-padding"
                         style="display:none; transform: scaleX(-1); border: 2px solid rgba(22,224,189,0.7); border-radius: 5px; background: rgba(0,0,0,0.5)">
                        <form method="post" action="index.php">
                            <div class="row" style="margin-bottom: 0">
                                <div class="center col s12 no-padding"
                                     style="text-align: center; background: rgba(22,224,189,0.5)">
                                    <div class="col s6 push-s3">
                                        <span
                                            style="font-size: 30px; color: rgba(255,255,255,0.7); font-weight: 400; letter-spacing: 10px;">REGISTER</span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div style="height: 2px; width: 100%; background: rgba(22,224,189,0.7)"></div>
                            </div>

                            <div style="width: 80%; margin-left: 10%">
                                <div class="row">
                                    <div class="input-field col s12">
                                        <i class="material-icons prefix" style="">perm_identity</i>
                                        <input name="new_username" id="new_username" type="text" class="">
                                        <label class="active" for="new_username">Team Name</label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="input-field col s12">
                                        <i class="material-icons prefix" style="">lock_outline</i>
                                        <input name="new_password" id="new_password" type="password" class="">
                                        <label for="new_password">Password</label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="input-field col s12">
                                        <i class="material-icons prefix" style="">lock</i>
                                        <input name="new_con_password" id="new_con_password" type="password" class="">
                                        <label for="new_con_password">Confirm Password</label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="input-field col s12">
                                        <i class="material-icons prefix" style="">contacts</i>
                                        <input name="new_fullname" id="new_fullname" type="text" class="">
                                        <label class="active" for="new_fullname">Member 1 - Fullname</label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="input-field col s12">
                                        <i class="material-icons prefix" style="">turned_in</i>
                                        <input name="new_kid" id="new_kid" type="text" class="">
                                        <label class="active" for="new_kid">Member 1 - Kriya ID</label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="input-field col s12">
                                        <i class="material-icons prefix" style="">contacts</i>
                                        <input name="new_fullname2" id="new_fullname2" type="text" class="">
                                        <label class="active" for="new_fullname2">Member 2 - Fullname</label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="input-field col s12">
                                        <i class="material-icons prefix" style="">turned_in</i>
                                        <input name="new_kid2" id="new_kid2" type="text" class="">
                                        <label class="active" for="new_kid2">Member 2 - Kriya ID</label>
                                    </div>
                                </div>


                                <div class="row" style="margin-top: 30px">
                                    <div class="col s12">
                                        <input style="display: none" type="submit" value="true">
                                        <input type="hidden" name="sign_up_request" value="true">
                                        <span onclick="submitCurrentForm(this)"
                                              class="btn waves-effect ">REGISTER</span>
                                    </div>
                                </div>

                                <div class="row" style="margin-top: 40px">
                                    <div class="col s12">
                                        <span id="sign_in_direct" class="" onclick="show_signin()"
                                              style="font-size:18px; color: rgba(22,224,189,0.8); cursor: pointer">Already have an account? Click here to login</span>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <script src="js/classie.js"></script>
    <script src="js/transit.js"></script>
    <script>
        $(document).ready(function () {
            <?php
            if(isset($_POST['sign_up_request'])){
            ?>
            show_signup(0);
            $("#new_username").val("<?php echo $uname ?>");
            $("#new_password").val("<?php echo $pass ?>");
            $("#new_con_password").val("<?php echo $passcon ?>");
            $("#new_fullname").val("<?php echo $fname ?>");
            $("#new_fullname2").val("<?php echo $fname2 ?>");
            $("#new_kid").val("<?php echo $kid ?>");
            $("#new_kid2").val("<?php echo $kid2?>");
            <?php
            }
            else {
            ?>
            $("#sign_in_form").show();
            $("#sign_up_form").hide();
            <?php
            }
            if($error_msg !== ""){
            ?>

            setMessageWindow("<?php echo $error_msg; ?>", TOAST_FAIL);

            <?php
            }
            ?>

            var newvalueX = $(window).width() / 16 * -1;
            var newvalueY = $(window).height() / 16 * -1;


            $('#top-image').css("background-position", newvalueX + "px " + newvalueY + "px");
            $("*").mousemove(function (e) {

                var newvalueX = (e.pageX) / 8 * -1;
                var newvalueY = (e.pageY) / 8 * -1;
                $('#top-image').css("background-position", newvalueX + "px " + newvalueY + "px");
                //$('#top-image').stop().animate({'background-position-x': newvalueX+"px", 'background-position-y': newvalueY+"px"},50,"linear");

            });

            redrawShapes();
            $(window).resize(redrawShapes);
        });

        function redrawShapes() {
            $(".triangle-down").css("border-left", ($(document).width() / 2) + "px solid transparent");
            $(".triangle-down:not(.shade)").css("border-right", ($(document).width() / 2) + "px solid transparent");

        }


        function show_signup(t=300) {
            $("#main_form").transition({perspective: '500px', rotateY: "90deg"}, t, "linear", function () {
                $("#sign_in_form").hide();
                $("#sign_up_form").show();
                $("#main_form").transition({perspective: '500px', rotateY: "180deg"}, t, "linear");
            })
        }

        function show_signin(t=300) {
            $("#main_form").transition({perspective: '500px', rotateY: "90deg"}, t, "linear", function () {
                $("#sign_up_form").hide();
                $("#sign_in_form").show();
                $("#main_form").transition({perspective: '500px', rotateY: "0deg"}, t, "linear");
            })
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