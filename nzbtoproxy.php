<?php
//get vars from url request...
$user = $_GET["user"];
$pass = $_GET["pass"];
$nid = $_GET["nid"];
$rel = rawurldecode($_GET["rel"]);

//if a var is missing, die and send a error
if(!$user or !$pass or !$nid) {
    die("Sorry check your user details");
}

$cookie = 'cookie.txt';
$url = "http://nzb.to/login.php";

$ch = curl_init();

curl_setopt ($ch, CURLOPT_URL, $url);
curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt ($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6");
curl_setopt ($ch, CURLOPT_TIMEOUT, 60);
curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt ($ch, CURLOPT_COOKIEJAR, $cookie);
curl_setopt ($ch, CURLOPT_COOKIEFILE, $cookie);
curl_setopt ($ch, CURLOPT_REFERER, $url);

curl_setopt ($ch, CURLOPT_POST, 1);
// Postdata to send for the login attempt
$postdata = array(
    'action' => 'login.php',
    'username'=> $user,
    'password'=> $pass,
    'bind_ip' => '1',
    'Submit' => '.%3AEinloggen%3A.',
    'ret_url' => '',
);
//Tell curl we're going to send $postdata as the POST data
curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
$login = curl_exec($ch);
$headers = curl_getinfo($ch);

//after login, call download page for the given NZB file
curl_setopt($ch, CURLOPT_URL, "http://nzb.to/download.php?nid=".$nid);
$result = curl_exec($ch);
curl_close($ch);

//if content is xml/nzb set content to xml and show it, else die and delete cookie file
if (substr($result,0,5) == '<?xml') {
    
    //set header for nzb-file
    header('Content-type: application/x-nzb');
    if ($rel) {
        //set filename to release name
        header('Content-Disposition: attachment; filename="'.$rel.'.nzb"');
    }
    ob_clean();
    flush();
    echo $result;
} else {
    echo "Sorry, unable to get NZB";
    echo unlink($cookie);
}
?>