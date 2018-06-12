<?php 
//https://stackoverflow.com/questions/18477639/php-how-to-connect-with-sockets-and-read-the-response?utm_medium=organic&utm_source=google_rich_qa&utm_campaign=google_rich_qa
//https://stackoverflow.com/questions/4779963/how-can-i-access-my-localhost-from-my-android-device?utm_medium=organic&utm_source=google_rich_qa&utm_campaign=google_rich_qa
// Om - Jun 12 2018 19:28 - works-gets ESP32 data into MySQL on PC - need to test on Heroku

$query="";
$link = mysqli_connect("localhost","root","hariom","test");
       if (mysqli_connect_error()){
        die( "Connection error");
        }
//$conn = stream_socket_server('tcp://127.0.0.1:1337');  // Om - 12th Jun 2018 works with getesp32datac.php
$conn = stream_socket_server('tcp://192.168.43.118:1337');  // Om - from ipconfig command run on PC
while ($socket = stream_socket_accept($conn)) {
    $pkt = stream_socket_recvfrom($socket, 1500, 0, $peer);
    if (false === empty($pkt)) {
        //stream_socket_sendto($socket, 'Received pkt ' . $pkt, 0, $peer);  // Om - 12th Jun 2018 - works
        parse_str($pkt); //Om - get $id, $ts and $val parts of message from client
    }
    fclose($socket);
    $query = "INSERT INTO ecgdata (`Id`, `Timestamp`,`Value`) 
        VALUES ($id, $ts,$val)"; 
    /*$query = "INSERT INTO patientdata (`Id`, `Systolic`, `Diastolic`, `Sugar`, `Temp`) 
        VALUES ($id, $sys, $dia, $sugar, $temp);";  */
        //echo $query;
    //$result = mysqli_query($link,$query);
   // if ($result) {
       if (mysqli_query($link,$query)){

        //echo "Query was successful";
       
            echo 1;
           }    
        else  {
        echo -1;  //does not work Apr 23 2017
    }
    
    usleep(10000); //100ms delay
}
stream_socket_shutdown($conn, \STREAM_SHUT_RDWR);
mysqli_close($link);
?>