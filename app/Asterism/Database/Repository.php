<?php

namespace App\Asterism\Database;

use DB;
use ReflectionObject;
use Illuminate\Database\Eloquent\Collection;

class Repository
{
    protected $connection;
    private   $logging;

    public function __construct(bool $queryLog = false)
    {
        $this->logging    = $queryLog;

        $this->connection = (isset($this->connectionString))
            ? DB::connection($this->connectionString)
            : DB::connection();

        if (env('APP_DEBUG') || $queryLog) {
            $this->connection->enableQueryLog();
        }
    }

    public function collect(array $data)
    {
        return new Collection($data);
    }

    public function hydrate($result, $objectReference)
    {
        $output = false;

        if (is_array($result)) {
            $casted = [];

            foreach($result as $r) {
                $casted[] = $this->castObject($r, $objectReference);
            }

            $output = $this->collect($casted);
        } else {
            $output = $this->castObject($result, $objectReference);
        }

        return $output;
    }

    public function queryLog()
    {
        return $this->connection->getQueryLog();
    }

    public function lastQuery()
    {
        $log = $this->connection->getQueryLog();
        
        return end($log);
    }

    private function castObject(stdObject $object, $reference)
    {
        $sourceObject = new ReflectionObject($object);
        $destObject   = new $reference();

        foreach ($sourceObject->getProperties() as $prop)
        {
            $propName                = $prop->getName();
            $destObject->{$propName} = $object->$propName;
        }

        return $destObject;
    }
}
