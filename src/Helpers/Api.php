<?php

namespace Turbo\Helpers;

class Api {


    public static function callApi($method, $url, $data = false) {

        $curl = curl_init();

        if ($method == "POST") {

            curl_setopt($curl, CURLOPT_POST, 1);

            if ($data) {
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            }
        }

        if ($method == "PUT") {
            curl_setopt($curl, CURLOPT_PUT, 1);
        }

        if ($method == "GET") {
            if ($data) {
                $url = sprintf("%s?%s", $url, http_build_query($data));
            }
        }

        // Optional Authentication:
        // curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        // curl_setopt($curl, CURLOPT_USERPWD, "username:password");

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.66 Safari/537.36',
            'Accept: application/json',
        ]);

        // $result = curl_exec($curl);
        $result = json_decode(curl_exec($curl), true);

        curl_close($curl);

        return $result;
    }
}