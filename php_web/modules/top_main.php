<?php
/**
 * Created by PhpStorm.
 * User: azizt
 * Date: 2/22/2017
 * Time: 1:25 AM
 */
?>

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
        <a class="info" href="<?php echo $TOP_REDIRECT_PAGE; ?>"
           style="color: rgb(57, 79, 74); font-weight: 300; font-size: 24px; border-bottom: 1px solid rgb(57, 79, 74);"><?php echo $TOP_REDIRECT_TITLE; ?></a>
    </div>

    <div class="timer_wrap" id="timer_wrap">
        <span id="timer">
            <?php if($is_overtime)echo "Time's Up!";else if($start_timestamp!=null)echo $remain_timestamp; ?>
        </span>
    </div>

    <div style="position: fixed; left: 10px; top: 10px">

        <div onclick="$('#logout_form').submit();" class="chip waves-effect z-depth-1 hoverable"
             style="background-color: rgba(64, 109, 96,0.5); cursor: pointer">
            <i class="close left material-icons white-text" style="margin-top: -1px; font-size: x-large; margin-left: -5px">reply</i>
            <span class=" white-text" style="font-size: 20px; vertical-align: super; margin-left: -5px">Logout</span>
            <form id="logout_form" method="post" action="<?php echo $THIS_PAGE; ?>">
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


<script>
    var remainTimestamp =<?php echo $remain_timestamp; ?>;
    $(document).ready(function () {
        <?php if(!$is_overtime && $start_timestamp!=null){

        ?>
        updateTimer();
        <?php
        }
        ?>
    });

    function updateTimer() {
        remainTimestamp--;
        var secs = remainTimestamp;
        if(remainTimestamp>=0){
            var hrs = parseInt(secs / (60 * 60));
            secs = secs % (60 * 60);
            var mins = parseInt(secs / (60));
            secs = parseInt(secs % (60));

            $("#timer").html(getFormattedValue(hrs) + ":" + getFormattedValue(mins) + ":" + getFormattedValue(secs));
            setTimeout(updateTimer, 1000);
        }else{
            window.location.reload();
        }
    }

    function getFormattedValue(v) {
        if (v < 10 && v >= 0) {
            return "0" + v;
        }
        return "" + v;
    }
</script>