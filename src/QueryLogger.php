<?php

namespace Moharami\QueryLogger;

use Illuminate\Support\Facades\DB;
use Moharami\QueryLogger\writer\TextLogFileWriter;

class QueryLogger
{

    private $sql;
    private $bindings;
    private $file_name = 'query.sql';
    /**
     * @var string
     */
    private $file_path;
    /**
     * @var int
     */
    protected $total_query;
    /**
     * @var int
     */
    private $total_time;
    /**
     * @var array
     */
    public $final;

    private $queries;

    public function __construct()
    {
        $this->file_path = storage_path($this->file_name);
        $this->total_query = 0;
        $this->total_time = 0;
        $this->final = [];

        if (env('APP_DEBUG', true)) {
            $this->startQueryLogger();
        }

    }

    private function startQueryLogger()
    {
        DB::listen(function ($data) {
            $this->total_query++;
            $this->total_time += $data->time;
            $this->addQuery($data);
            $this->addMeta();
            $this->write();
        });
    }

    /**
     * add each query in a specific array format
     *
     * @param object $query
     * @return void
     */
    protected function addQuery($query)
    {
        $queryStr = $this->getSqlWithBindings($query);
        $time = $query->time;
        $this->data['queries'] = [
            'sl' => $this->total_query,
            'query' => $query->sql,
            'bindings' => $query->bindings,
            'final_query' => $queryStr,
            'time' => $time,
        ];
    }

    /**
     * Make final query from sql bindings
     *
     * @param $query
     * @return string
     */
    private function getSqlWithBindings($query)
    {
        return vsprintf(str_replace('?', '%s', $query->sql), collect($query->bindings)
            ->map(function ($binding) {
                return is_numeric($binding) ? $binding : "'{$binding}'";
            })->toArray());
    }

    private function addMeta()
    {
        $this->final['meta'] = [
            'url' => request()->url(),
            'method' => request()->method(),
            'total_query' => $this->total_query,
            'total_time' => $this->total_time,
        ];
//        app()->terminating(function () {
//
//        });
    }

    private function write()
    {
        (new TextLogFileWriter)->write($this->file_path, $this->final);
    }
}
