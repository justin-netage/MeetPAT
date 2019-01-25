<?php

$fb = new \Facebook\Facebook([
    'app_id' => env('FACEBOOK_APP_ID'),
    'app_secret' => env('FACEBOOK_APP_SECRET'),
    'default_graph_version' => 'v3.2',
  ]);
  
  // Use one of the helper classes to get a Facebook\Authentication\AccessToken entity.
  //   $helper = $fb->getRedirectLoginHelper();
  //   $helper = $fb->getJavaScriptHelper();
  //   $helper = $fb->getCanvasHelper();
  //   $helper = $fb->getPageTabHelper();
  
  try {
    // Returns a `Facebook\FacebookResponse` object
    $response = $fb->get(
      '/act_' . env('FACEBOOK_APP_ID') . '/customaudiences?fields=id',
      env('FACEBOOK_SANDBOX_ACCESS_TOKEN')
    );
  } catch(Facebook\Exceptions\FacebookResponseException $e) {
    echo 'Graph returned an error: ' . $e->getMessage();
    
  } catch(Facebook\Exceptions\FacebookSDKException $e) {
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    
  }
  $graphNode = $response->getGraphNode();

  ?>