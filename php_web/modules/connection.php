<?php


/*
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
*/

include_once('../../php_web/include/connector.php');

$conn = connectAccordingly();

$query = "Select ans from questions";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo " " . $row["ans"] . "<br>";
    }
}