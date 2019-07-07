<?php

defined('BASEPATH') or die('No direct script access.');

class PushNotifications {

    // (Android)API access key from Google API's Console.
    //private static $API_ACCESS_KEY = 'AIzaSyAA7u6WKobC57OhG0XcgCgQTIs8KEpr2JM';

    private static $API_ACCESS_KEY = 'API KEY';
    // (iOS) Private key's passphrase.
    private static $passphrase = 'joashp';
    // (Windows Phone 8) The name of our push channel.
    private static $channelName = "joashp";

    // Change the above three vriables as per your app.

    public function __construct() {
        // exit('Init function is not allowed');
    }

    function sendPushNotification($data, $token) {

        $path_to_firebase_cm = 'https://fcm.googleapis.com/fcm/send';

        $fields = array(
            'registration_ids' => $token,
            'data' => array('title' => $title, 'data' => $data)
        );

        $headers = array(
            'Authorization:key=' . $API_ACCESS_KEY,
            'Content-Type:application/json'
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $path_to_firebase_cm);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    // Sends Push notification for Android users
    public function android($data, $reg_id) {
        $url = 'https://fcm.googleapis.com/fcm/send';
//        $message = array(
//            'title' => $data['mtitle'],
//            'message' => $data['mdesc'],
//            'subtitle' => '',
//            'tickerText' => '',
//            'msgcnt' => 1,
//            'vibrate' => 1
//        );

        $headers = array(
            'Authorization: key=' . self::$API_ACCESS_KEY,
            'Content-Type: application/json'
        );

        $fields = array(
            'registration_ids' => array($reg_id),
            'data' => $data,
        );

        return self::useCurl($url, json_encode($headers), json_encode($fields));
    }

    // Curl
    private function useCurl(&$model, $url, $headers, $fields = null) {
        // Open connection

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;


//        $ch = curl_init();
//        if ($url) {
//            // Set the url, number of POST vars, POST data
//            curl_setopt($ch, CURLOPT_URL, $url);
//            curl_setopt($ch, CURLOPT_POST, true);
//            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//
//            // Disabling SSL Certificate support temporarly
//            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//            if ($fields) {
//                curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
//            }
//
//            // Execute post
//            $result = curl_exec($ch);
//            if ($result === FALSE) {
//                die('Curl failed: ' . curl_error($ch));
//            }
//
//            // Close connection
//            curl_close($ch);
//
//            return $result;
//        }
    }

}

// END Push notification Class