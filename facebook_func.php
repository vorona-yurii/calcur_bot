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

function postFacebook($app_id, $app_secret, $app_token, $text='', $img_source=''){
    $fb = new \Facebook\Facebook([
        'app_id'  => $app_id,
        'app_secret' => $app_secret,
        'default_graph_version' => 'v2.10',
    ]);

    $page_id = getPageId($app_token);
    $data = [];

    if(!empty($text)){
        $data['message'] = $text;
    }
    if(!empty($img_source)){
        $data['source'] = $img_source;
    }

    var_dump($data);
    exit();

    try {
        // Returns a `Facebook\FacebookResponse` object
        if($data['source']){
            $response = $fb->post('/me/photos', $data, $app_token);
        }elseif($data['source'] && $data['message']){
            $response = $fb->post('/me/photos', $data, $app_token);
        }elseif($data['message']){
            $response = $fb->post("/{$page_id}/feed", $data, $app_token);
        }
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