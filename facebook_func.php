<?php
/**
 * Created by PhpStorm.
 * User: yuv
 * Date: 30.03.2018
 * Time: 22:02
 */

require_once __DIR__ . '/vendor/autoload.php'; // change path as needed

function getPageId($app_token){
    $pageId = json_decode(file_get_contents('https://graph.facebook.com/v2.10/me?access_token='.$app_token), TRUE);

    return $pageId['id'];
}

function postFacebook($app_id, $app_secret, $app_token){
    $fb = new \Facebook\Facebook([
        'app_id'  => $app_id,
        'app_secret' => $app_secret,
        'default_graph_version' => 'v2.10',
    ]);

    $page_id = getPageId($app_token);

    // описание параметров есть в документации
    $data = [
        'message' => 'It works!',
        'source' => $fb->fileToUpload('uploads_img/hola-vpn.png'),
    ];

    try {
        // Returns a `Facebook\FacebookResponse` object
        $response = $fb->post('/me/photos', $data, '{access-token}');
    } catch(Facebook\Exceptions\FacebookResponseException $e) {
        echo 'Graph returned an error: ' . $e->getMessage();
        exit;
    } catch(Facebook\Exceptions\FacebookSDKException $e) {
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
    }

    $graphNode = $response->getGraphNode();

    return 'Posted with id: ' . $graphNode['id'];

}