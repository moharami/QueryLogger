<?php

namespace Moharami\QueryLogger;

class QueryLogger
{

    private string $sql;
    private array $bindings;
    private string $file_name = 'query.sql';

    public function __construct()
    {
        $this->file_path = storage_path($this->file_name);

        if (env('APP_DEBUG', true)) {
            $this->startQueryLogger();
        }
    }

    private function startQueryLogger()
    {
        \DB::listen(
            function ($data) {
                if (!empty($data->bindings)) {
                    $data->sql = vsprintf(str_replace('?', "'%s'", $data->sql), $data->bindings);
                }
                file_put_contents($this->file_path, $data->sql . "\n", FILE_APPEND);
            }
        );
    }

}
