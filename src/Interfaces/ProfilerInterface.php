<?php
declare(strict_types=1);

namespace Azonmedia\Apm\Interfaces;
interface ProfilerInterface
{
    public const PROFILE_STRUCTURE = [
        'worker_id'                     => 0,
        'coroutine_id'                  => 0,
        'execution_start_microtime'     => 0,
        'execution_end_microtime'       => 0,
        'cnt_used_connections'          => 0,
        'time_used_connections'         => 0,//for all connections - for how long the connection was held
        'time_waiting_for_connection'   => 0,//waiting time to obtain connection from the Pool
        'cnt_total_current_coroutines'  => 0,
        'cnt_subcoroutines'             => 0,
        'cnt_dql_statements'            => 0,
    ];

}