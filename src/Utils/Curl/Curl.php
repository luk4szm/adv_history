<?php

namespace App\Utils\Curl;

class Curl
{
    /**
     * Fetch data from given url
     *
     * @param string $url
     * @return string
     *
     * @throws \Exception
     */
    public static function get(string $url): string
    {
        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0');

        if (false === $data = curl_exec($curl)) {
            throw new \Exception('CURL error: ' . curl_error($curl));
        }

        curl_close($curl);

        return $data;
    }
}
