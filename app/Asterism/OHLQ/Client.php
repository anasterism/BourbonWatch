<?php

namespace App\Asterism\OHLQ;

use App\Models\Bourbon;
use App\Asterism\OHLQ\Result;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client as GuzzleClient;

class Client
{

    public static function fetch(Bourbon $bourbon)
    {
        $guzzle = new GuzzleClient();
        $result = $guzzle->get(env('OHLQ_ENDPOINT') . $bourbon->id);
        $body   = $result->getBody()->getContents();
        $json   = self::jsToJson($body);
        $obj    = self::processResults($bourbon, $json);

        return $obj;
    }

    /**
     * OHLQ returns a JavaScript formatted string of results. We
     * need to break that down into a more digestable form. JSON.
     * 
     * @param string Raw js formatted response
     * @return array An array of json formatted strings
     */
    private static function jsToJson(string $js)
    {
        $jsonStrings = [];
        $return      = [];
        preg_match_all('/(?={)(.*?)(};)/', $js, $jsonStrings);

        foreach ($jsonStrings[0] as $match)
        {
            $match = preg_replace('/([a-zA-Z0-9]*):/', '"$1":', $match);
            $match = str_replace(';', '', $match);

            $return[] = $match;
        }

        return $return;
    }

    /**
     * More data cleanup and object generation.
     * 
     * @param array Array of JSON formatted strings.
     * @return array Array of stdObjects
     */
    private static function processResults(Bourbon $bourbon, array $json)
    {
        $return = [];

        foreach ($json as $match)
        {
            $match = json_decode($match);
            $arr   = (array)$match;

            if (count($arr)) {
                foreach($match as $prop => $obj) {
                    $agencyId = (int)str_replace('A', '', $prop);
                    $return[] = new Result($bourbon, $agencyId, $obj->f);
                }
            }
        }

        return $return;
    }
    
}
