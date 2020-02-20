<?php

/** GET IP ADDRESS OF USER
 * https://github.com/zamnuts/ean-api-client/blob/master/src/zamnuts/EANAPIClient/Util/Utils.php
 * @param null $customHeaders
 * @return string
 */

function getIP($customHeaders=null) {
    $headers = array(
        'HTTP_X_CLUSTER_CLIENT_IP', // rackspace load balancer detection
        'HTTP_X_FORWARDED_FOR', // proxy detection
        'HTTP_X_REAL_IP', // fcgi detection (e.g. nginx)
        'REMOTE_ADDR' // standard fallback
    );
    if ( isset($customHeaders) ) {
        if ( is_array($customHeaders) ) {
            $headers = array_merge($customHeaders,$headers);
        } else if ( is_string($customHeaders) ) {
            array_unshift($headers,$customHeaders);
        }
    }
    foreach ( $headers as $header ) {
        if ( isset($_SERVER[$header]) && trim($_SERVER[$header]) ) {
            return trim($_SERVER[$header]);
        }
    }
    return '0.0.0.0';
}


/** Sends an HTML formatted email */
function sendmail($to, $subject, $msg, $from, $plaintext = '')
{
    if(!is_array($to)) $to = array($to);

    foreach($to as $address)
    {
        $boundary = uniqid(rand(), true);

        $headers  = "From: $from\n";
        $headers .= "MIME-Version: 1.0\n";
        $headers .= "Content-Type: multipart/alternative; boundary = $boundary\n";
        $headers .= "This is a MIME encoded message.\n\n";
        $headers .= "--$boundary\n" .
            "Content-Type: text/plain; charset=ISO-8859-1\n" .
            "Content-Transfer-Encoding: base64\n\n";
        $headers .= chunk_split(base64_encode($plaintext));
        $headers .= "--$boundary\n" .
            "Content-Type: text/html; charset=ISO-8859-1\n" .
            "Content-Transfer-Encoding: base64\n\n";
        $headers .= chunk_split(base64_encode($msg));
        $headers .= "--$boundary--\n" .

            mail($address, $subject, '', $headers);
    }
}


/** Serves an external document for download as an HTTP attachment. */
function download($filename, $mimetype = 'application/octet-stream')
{
    if(!file_exists($filename) || !is_readable($filename)) return false;
    $base = basename($filename);
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Content-Disposition: attachment; filename=$base");
    header("Content-Length: " . filesize($filename));
    header("Content-Type: $mimetype");
    readfile($filename);
    exit();
}

/** Retrieves the filesize of a remote file. */
function remote_filesize($url, $user = null, $pw = null)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_NOBODY, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    if(!is_null($user) && !is_null($pw))
    {
        $headers = array('Authorization: Basic ' .  base64_encode("$user:$pw"));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }

    $head = curl_exec($ch);
    curl_close($ch);

    preg_match('/Content-Length:\s([0-9].+?)\s/', $head, $matches);

    return isset($matches[1]) ? $matches[1] : false;
}