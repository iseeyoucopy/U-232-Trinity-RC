<?php

/* Port Checking PHP Script
   Created by Jonesy44
   Released: 30 November, 2008  */

echo '<title>Port Availability Checker';
//Please leave the next line :)
echo ', Writen by Jonesy44';
echo '</title>';

$addr = $_SERVER["REMOTE_ADDR"];
$port = "80";
if ($_GET["addr"]) {
  $addr = $_GET["addr"];
}
if ($_GET["port"]) {
  $port = $_GET["port"];
}

echo '<form action="' .$_SERVER["PHP_SELF"]. '" method="get">
  <div style="width:300px;background:#f1f1f1;padding:10px;font-family:arial;">
    <table width="100%" border="0" cellspacing="0" cellpadding="2">
      <tr>
        <td colspan="2" style="font-size:12px;">Please enter the Address/IP and port of the website or IP address you wish to test (enter the second IP if you want so scan to that port range)</td>
      </tr>
      <tr>
        <td width="30%" style="font-size:12px;">Address/IP</td>
        <td width="80%"><input type="text" name="addr" value="' .$addr. '"></td>
      </tr>
      <tr>
        <td width="30%" style="font-size:12px;">Port</td>
        <td width="80%"><input type="text" name="port" value="' .$port. '"></td>
      </tr>
        <td width="30%">&nbsp;</td>
        <td width="80%"><input type="submit" value="Check/Scan Port(s)"></td>
      </tr>
    </table>
  </div>
</form>
';

if ($_GET["addr"]) {
  if ($_GET["port"]) {
      $fp = @fsockopen($addr, $port, $errno, $errstr, 2);
      $success = "#FF0000";
      $success_msg = "is closed and cannot be used at this time";
      if ($fp) {
        $success = "#99FF66";
        $success_msg = "is open and ready to be used";
      }
      @fclose($fp);
      echo '<div style="width:300px;background:' .$success. ';padding:10px;font-family:arial;font-size:12px;">
    The address <b>"' .$addr. ':' .$port. '"</b> ' .$success_msg. '
  </div>';
  } elseif ($_GET["port"]) {
      $p1 = $_GET["port"];
      $p2 = $_GET["port2"];
      if ($p1 == $p2) {
        $fp = @fsockopen($addr, $port, $errno, $errstr, 2);
        $success = "#FF0000";
        $success_msg = "is closed and cannot be used at this time";
        if ($fp) {
          $success = "#99FF66";
          $success_msg = "is open and ready to be used";
        }
        @fclose($fp);
        echo '<div style="width:300px;background:' .$success. ';padding:10px;font-family:arial;font-size:12px;">
      The address <b>"' .$addr. ':' .$port. '"</b> ' .$success_msg. '
      </div>';
      }
  }
}
?>
