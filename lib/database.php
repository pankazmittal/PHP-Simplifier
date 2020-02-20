<?php


$mysqli_connection = mysqli_connect( constant("host"), constant("db_user"), constant("db_pass"), constant("db_name"));

/** LAZY WAY COUNT ROWS IN QUERY DATA
 * @param $query is the query variable for mysqli database
 * @return int this is count of returned data
 */
function rows($query)
{
    return mysqli_num_rows($query);
}

/** LAZY WAY TO CALL MYSQLI QUERY
 * @param $query
 * @return bool|mysqli_result
 */
function query($query)
{
    return mysqli_query($mysqli_connection,$query);
}

/** LAZY WAY TO CALL FETCH ARRAY
 * @param $query
 * @return array|null
 */
function fetch($query)
{
    return mysqli_fetch_array($query);
}