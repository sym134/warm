<?php
return [
    'name'                    => 'Task Name',
    'task_type'               => 'Task Type',
    'execution_cycle'         => 'Execution Cycle',
    'target'                  => 'Target',
    'parameter'               => 'Parameter',
    'rule'                    => 'Rule',
    'week'                    => 'Week',
    'day'                     => 'Day',
    'hour'                    => 'Hour',
    'minute'                  => 'Minute',
    'second'                  => 'Second',
    'remark'                  => 'Remark',
    'created_by'              => 'Created By',
    'task_status'             => 'Task Status',
    'name_description'        => '* The name of the mission must be unique',
    'target_description'      => 'Class assignment reference: xxx\xxx\class:method name',
    'execution_log'           => 'Execution log',
    'run'                     => 'Run',
    'execution_cycle_options' => [
        'day'      => 'Every Day',
        'day-n'    => 'Every N Days',
        'hour'     => 'Every Hour',
        'hour-n'   => 'Every N Hours',
        'minute-n' => 'Every N Minutes',
        'week'     => 'Every Week',
        'month'    => 'Every Month',
        'second-n' => 'Every N Seconds',
    ],

    'crontab_log' => [
        'crontab_id'       => 'Task ID',
        'task_name'        => 'Task Name',
        'task_type'        => 'Task Type',
        'execution_cycle'  => 'Execution Cycle',
        'target'           => 'Target',
        'exception_info'   => 'Error Message',
        'parameter'        => 'Parameter',
        'execution_status' => 'Execution Status',
    ],
];
