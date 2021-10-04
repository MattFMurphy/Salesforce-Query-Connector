<?php
class SOQLQuery
{
    public static function execute($query)
    {
        $base_uri = "YOUR_SALESFORCE_URL/services/data/v49.0/query/?q=";
        $ch = curl_init($base_uri . $query);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_ENCODING, "");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer ' . self::getToken(),
            'Content-Type: application/json'
        ));

        $response = json_decode(curl_exec($ch));
        curl_close($ch);

        return $response;
    }

    private static function getToken()
    {
        $apiCredentials = [
            'client_id' => 'YOUR_CLIENT_ID',
            'client_secret' => 'YOUR CLIENT SECRET',
            'security_token' => 'YOUR SECURITY TOKEN',
        ];
        $userCredentials = [
            'username' => 'YOUR_USERNAME',
            'password' => 'YOUR_PW',
        ];
        $ch = curl_init("https://login.salesforce.com/services/oauth2/token");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, array(
            "grant_type" => "password",
            'client_id' => $apiCredentials['client_id'],
            'client_secret' => $apiCredentials['client_secret'],
            'username' => $userCredentials['username'],
            'password' => $userCredentials['password'] . $apiCredentials['security_token'],
        ));

        $response = json_decode(curl_exec($ch));
        //logModx('Salesforce token: ' . $response);

        return $response->access_token;
    }
}
