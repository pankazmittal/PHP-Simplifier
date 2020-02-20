<?php


/** NEW SHORT AND EFFECTIVE PRINT METHOD
 * @param $string string that you want to out
 * @param bool $type if 2nd param in out is true it will not remove html attrs
 */
function out($string, $type=false)
{
    if ($type)
        echo $string;
    else
        echo strip_tags($string);
}

/** Extract POST or GET request with removing elements which can block a mysql query to execute
 *
 */
function xtract()
{
    extract(str_replace('"','\"',str_replace("'","\'",$_REQUEST)));
}


/** EXTRACT POST OR GET WITH AUTO SANITIZATION
 * @param array $array this is your typical array
 * @param bool $filter this is OPTIONAL and decide that array will sanitize or not
 * @return array this is the output
 */

function allout(array &$array, $filter = false)
{
    array_walk_recursive($array, function (&$value) use ($filter) {
        $value = trim($value);
        if ($filter) {
            $value = filter_var($value, FILTER_SANITIZE_STRING);
        }
    });

    return $array;
}

/** UPLOAD FILES
 * @param $path upload path
 * @param $name name of file
 */
function upload($path, $name)
{
    $file=$_FILES[$name]['name'];
    $expfile = explode('.',$file);
    $fileexptype=$expfile[count($expfile)-1];
    date_default_timezone_set( constant("zone"));
    $date = date('m/d/Yh:i:sa', time());
    $rand=rand(10000,999999);
    $encname=$date.$rand;
    $filename=md5($encname).'.'.$fileexptype;
    $filepath=$path.$filename;
    move_uploaded_file($_FILES[$name]["tmp_name"],$filepath);
}

/** GET SESSION DATA
 * @param $key is sessions key
 * @return mixed the value session have
 */
function gets($key)
{
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    return $_SESSION[$key];
}

/** SET DATA IN SESSION
 * @param $key is session's key
 * @param $value is value you want to set in session
 * @return bool always return true
 */
function sets($key, $value)
{
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    $_SESSION[$key] = $value;

    return true;
}

/** CHECK IF KEY EXIST IN SESSION OR NOT
 * @param $key is session's key
 * @return bool define value exist in session or not
 */
function checks($key)
{
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if (isset($_SESSION[$key]))
    {
        return true;
    }
    else
    {
        return false;
    }
}

/** REDIRECT PAGE
 * @param $destination path for new location
 */
function redirect($destination)
{
    header("location:".$destination);
}

/** Returns the first $num words of $str with $suffix you wanted*/
function max_words($str, $num, $suffix = '')
{
    $words = explode(' ', $str);
    if(count($words) < $num)
        return $str;
    else
        return implode(' ', array_slice($words, 0, $num)) . $suffix;
}


/** Outputs a filesize in human readable format. */
function bytes2str($val, $round = 0)
{
    $unit = array('','K','M','G','T','P','E','Z','Y');
    while($val >= 1000)
    {
        $val /= 1024;
        array_shift($unit);
    }
    return round($val, $round) . array_shift($unit) . 'B';
}

/** Tests for a valid email address and optionally tests for valid MX records, too. */
function valid_email($email, $test_mx = false)
{
    list($user, $domain) = explode('@', $email);
    if(strlen($user) > 0 && strlen($domain) > 0) {
        $parts = explode('.', $domain);
        if($parts >= 2) {
            if($test_mx) {
                return getmxrr($domain, $mxrecords);
            } else {
                return true;
            }
        }
    }

    return false;
}

/** Returns an English representation of a past date within the last month
// Graciously stolen from http://ejohn.org/files/pretty.js
 */
function prettytime($ts)
{
    if(!ctype_digit($ts)) {
        $ts = strtotime($ts);
    }

    $diff = time() - $ts;
    if($diff == 0) {
        return 'now';
    } else if($diff > 0) {
        $day_diff = floor($diff / 86400);
        if($day_diff == 0)
        {
            if($diff < 60) return 'just now';
            if($diff < 120) return '1 minute ago';
            if($diff < 3600) return floor($diff / 60) . ' minutes ago';
            if($diff < 7200) return '1 hour ago';
            if($diff < 86400) return floor($diff / 3600) . ' hours ago';
        }
        if($day_diff == 1) return 'Yesterday';
        if($day_diff < 7) return $day_diff . ' days ago';
        if($day_diff < 31) return ceil($day_diff / 7) . ' weeks ago';
        if($day_diff < 60) return 'last month';
        $ret = date('F Y', $ts);
        return ($ret == 'December 1969') ? '' : $ret;
    } else {
        $diff = abs($diff);
        $day_diff = floor($diff / 86400);
        if($day_diff == 0)
        {
            if($diff < 120) return 'in a minute';
            if($diff < 3600) return 'in ' . floor($diff / 60) . ' minutes';
            if($diff < 7200) return 'in an hour';
            if($diff < 86400) return 'in ' . floor($diff / 3600) . ' hours';
        }
        if($day_diff == 1) return 'Tomorrow';
        if($day_diff < 4) return date('l', $ts);
        if($day_diff < 7 + (7 - date('w'))) return 'next week';
        if(ceil($day_diff / 7) < 4) return 'in ' . ceil($day_diff / 7) . ' weeks';
        if(date('n', $ts) == date('n') + 1) return 'next month';
        $ret = date('F Y', $ts);
        return ($ret == 'December 1969') ? '' : $ret;
    }
}
