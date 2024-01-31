<?php

/**
 * Thực thi 1 câu lệnh SQL đến CSDL ghi
 * @param string $sql
 * @return resultset
 */
function Gnud_Db_write_query($sql)
{
    $result = Gnud_Db_Query($sql, 'write');
    return $result;
}

//----------------------------------------------------------------------------------------
function Gnud_Db_query_multi($sql, $connection='read')
{
    $rs = Gnud_Db_call_store_multi($sql, $connection);
    $result = Gnud_Db_fetch_store_multi($rs, $connection);
    return $result;
}

/**
 * Thực hiện store cho CSDL chỉ đọc
 * @param $sql Store cần thực hiện
 * @return resultset
 */
function Gnud_Db_call_store_multi($sql, $connection='read')
{
    global $fw24h_connections;
    $fw24h_connections[$connection] = Gnud_Db_connect($connection);
    $time_start = microtime(true);
    $rs = mysqli_multi_query($fw24h_connections[$connection], $sql) or trigger_error( mysqli_error($fw24h_connections[$connection]).'###'.$sql.'###'.$connection, E_USER_ERROR);
    $time_end = microtime(true);
    $time = $time_end - $time_start;
    return $rs;
}

function Gnud_Db_fetch_store_multi($rs, $connection='read')
{
    global $fw24h_connections;
    $count = 0;
    $result2 = array();
    do {
        /* store first result set */
        if ($result = mysqli_store_result($fw24h_connections[$connection])) {
            while ($row = mysqli_fetch_assoc($result)) {
                $result2['record'.$count][] = $row;
            }
            mysqli_free_result($result);
        }
        /* print divider */
        if (mysqli_more_results($fw24h_connections[$connection])) {
            $count++;
        }
    } while (mysqli_next_result($fw24h_connections[$connection]));
    if(sizeof($result2) == 1) {
        $result2 = $result2['record0'];
    }
    return $result2;
}


function Gnud_Db_connect($connection='read', $new_link=FALSE)
{
    global $fwDbConfig;
    global $fwDbReadConn;
    global $fwDbReadConnNewLink;
    global $fw24h_connections;
    if (is_object($fw24h_connections[$connection])) {
        return $fw24h_connections[$connection];
    }

    $fw24h_connections[$connection] = mysqli_connect($fwDbConfig[$connection]['server'], $fwDbConfig[$connection]['username'], $fwDbConfig[$connection]['password'],$fwDbConfig[$connection]['database']) or trigger_error( mysqli_connect_errno(), E_USER_ERROR);

    if ($fwDbConfig[$connection]['names']) {
        $sql = 'SET NAMES '.$fwDbConfig[$connection]['names'];
        mysqli_query($fw24h_connections[$connection], $sql) or trigger_error( mysqli_error($fw24h_connections[$connection]).'###'.$sql.'###'.$connection, E_USER_ERROR);
    }
    return $fw24h_connections[$connection];
}

function Gnud_Db_close($connection='read')
{
    global $fw24h_connections;
    if ($fw24h_connections[$connection]) {
        mysqli_close($fw24h_connections[$connection]);
        unset($fw24h_connections[$connection]);
    }
}

function Gnud_Db_read_close()
{
    Gnud_Db_close();
}

function Gnud_Db_write_close()
{
    Gnud_Db_close('write');
}
