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