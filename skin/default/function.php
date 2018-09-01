<?php
function showStat()
{
    global $obj;
    echo "<div id='server_stats'>";
    echo "<blink><font color=#cbac57>{$obj->notice("notice")}</font></blink><br/>";
    echo "{$obj->notice("yourip")} {$_SERVER['REMOTE_ADDR']}. {$obj->notice("yourjob")} {$obj->notice("userjobs")}. {$obj->notice("youused")} {$obj->notice("used")}.<br/>";
    echo "{$obj->notice("sizelimit")} {$obj->notice("maxsize")}. {$obj->notice("totjob")} {$obj->notice("totjobs")}. {$obj->notice("serverload")} {$obj->notice("maxload")}. {$obj->notice("uonline")} {$obj->notice("useronline")}.<br/>";
    echo "</div>";
}
function showPlugin()
{
    global $obj;
    foreach ($obj->acc as $host => $value) {
        $xout = array('');
        $xout = $obj->acc[$host]['accounts'];
        $max_size = $obj->acc[$host]['max_size'];
        if (empty($xout[0]) == false && empty($host) == false) {
            if ($obj->hide_number_acc == true) {
                $hosts[] = '<span class="plugincollst">' . $host . '</span><br/>';
            } else {
                $hosts[] = '<span class="plugincollst">' . $host . ' ' . count($xout) . '</span><br/>';
            }
        }
    }
    if (isset($hosts)) {
        if (count($hosts) > 4) {
            for ($i = 0; $i < 5; $i++) {
                echo "$hosts[$i]";
            }

            echo "<div id=showacc style='display: none;'>";
            for ($i = 5; $i < count($hosts); $i++) {
                echo "$hosts[$i]";
            }

            echo "</div>";
        } else {
            for ($i = 0; $i < count($hosts); $i++) {
                echo "$hosts[$i]";
            }
        }

        if (count($hosts) > 4) {
            echo "<a onclick=\"showOrHide();\" href=\"javascript:void(0)\" style='TEXT-DECORATION: none'><font color=#FF6600><div id='moreacc'>" . $obj->lang['moreacc'] . "</div></font></a>";
        }

    }
    return false;
}
