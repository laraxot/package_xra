<?php

namespace XRA\XRA\Controllers\Admin\XRA;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Facebook\Facebook;

use NotificationChannels\Facebook\FacebookChannel;
use NotificationChannels\Facebook\FacebookMessage;
use NotificationChannels\Facebook\Component\Button;
use NotificationChannels\Facebook\Enums\NotificationType;

use Illuminate\Notifications\Notification;

//--- services
use XRA\Extend\Services\ThemeService;


//----- traits -----
use XRA\Extend\Traits\CrudSimpleTrait as CrudTrait;

class FBController extends Controller
{
    protected $access_token='EAACEdEose0cBALpb6CtkPH23QHg7xWFp2dHfxxkTZCPefR3QmzN4fShHZBXLsYQVLE06YnjAP5clU4fsoJ2ATosxUUKl9J1pY16jZC9UpjvBevPE2ZBCZAuCBjeyVpRP5zGWYBDZA5uiU8YPOt9wD6yLyiLiAZC8BZB6fUZBpP1NnJI0L5QXkfIxGoF2Q1ZB1cZAr3Y3lPAWVOZCpAZDZD';

    public function fbinit()
    {
        $this->app_id=config('services.facebook.client_id');
        $this->app_secret=config('services.facebook.client_secret');
        //$appsecret_proof= hash_hmac('sha256', $this->access_token, $app_secret);
        $fb_params=[
            'app_id' => $this->app_id,
            'app_secret' => $this->app_secret,
            'default_graph_version' => 'v3.0',
        ];
        $fb = new Facebook\Facebook($fb_params);
        return $fb;
    }

    public function token(Request $request)
    {
        return $this->test04($request);
    }//token

    public function messenger(Request $request)
    {
        if ($request->getMethod()=='POST') {
            return $this->do_messenger($request);
        }
        $view=ThemeService::getView();
        return view($view)->with('row', $request);
    }

    public function do_messenger(Request $request)
    {
        $fb_id='638953667';
        return FacebookMessage::create('TESTO DI PROVA')
            ->to($fb_id);
        /*
        curl -X POST -H "Content-Type: application/json" -d '{
  "messaging_type": "<MESSAGING_TYPE>",
  "recipient": {
    "id": "<PSID>"
  },
  "message": {
    "text": "hello, world!"
  }
}' "https://graph.facebook.com/v2.6/me/messages?access_token=<PAGE_ACCESS_TOKEN>"
    */
    }


    public function test01(Request $request)
    {
        $app_id=config('services.facebook.client_id');
        $app_secret=config('services.facebook.client_secret');
        $fb=$this->fbinit();
        $helper = $fb->getRedirectLoginHelper();
        //$_SESSION['FBRLH_state']=$_GET['state'];

        try {
            $accessToken = $helper->getAccessToken();
        } catch (Facebook\Exceptions\FacebookResponseException $e) {
            echo '<br/>['.__LINE__.']Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            echo '<br/>['.__LINE__.']Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        if (! isset($accessToken)) {
            echo '<br/>['.__LINE__.']No OAuth data could be obtained from the signed request. User has not authorized your app yet.';
        //exit;
        } else {
            $this->access_token=$accessToken->getValue();
        }
        
        $fb->setDefaultAccessToken($this->access_token);
        $appsecret_proof= hash_hmac('sha256', $access_token, $app_secret);

        try {
            $response = $fb->get('me/accounts', $this->access_token);
            $response = $response->getDecodedBody();
        } catch (Facebook\Exceptions\FacebookResponseException $e) {
            echo '<br/>['.__LINE__.']Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            echo '<br/>['.__LINE__.']Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        echo "<pre>";
        print_r($response);
        echo "</pre>";
    }

    public function test02(Request $request)
    {
        $fb=$this->fbinit();
        $fb->setDefaultAccessToken($this->access_token);
        $helper = $fb->getCanvasHelper();
        
        try {
            $accessToken = $helper->getAccessToken();
        } catch (Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo '<br/>Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo '<br/>Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        if (! isset($accessToken)) {
            echo '<br/>No OAuth data could be obtained from the signed request. User has not authorized your app yet.';
            //exit;
        }

        // Logged in
        echo '<h3>Signed Request</h3>';
        var_dump($helper->getSignedRequest());
        if (isset($accessToken)) {
            echo '<h3>Access Token</h3>';
            var_dump($accessToken->getValue());
        }
        $fb->setDefaultAccessToken($this->access_token);

        $requestUserName = $fb->request('GET', '/me?fields=id,name');
        $batch = [
            'user-profile' => $requestUserName,
        ];



        try {
            $responses = $fb->sendBatchRequest($batch);
        } catch (Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }
    }

    public function test03(Request $request)
    {
        $app_id=config('services.facebook.client_id');
        $app_secret=config('services.facebook.client_secret');
        $fb=$this->fbinit();
        $token = $fb->get(
            '/oauth/access_token?client_id=' . $app_id . '&'.
            'client_secret=' . $app_secret . '&'.
            'grant_type=client_credentials'
        );

        $get_token = $token -> getdecodedBody();
        $access_token = $get_token['access_token'];

        try {
            // Returns a `Facebook\FacebookResponse` object
            $response = $fb->get('/me', $access_token);
        } catch (Facebook\Exceptions\FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        $body = $response->getBody();

        ddd($body);
    }

    public function test04(Request $request)
    {
        $fb=$this->fbinit();
        $appsecret_proof= hash_hmac('sha256', $this->access_token, $this->app_secret);
        $params=[];
        $params['access_token']=$this->access_token;
        $params['appsecret_proof']=$appsecret_proof;
        // Since all the requests will be sent on behalf of the same user,
        // we'll set the default fallback access token here.
        //$fb->setDefaultAccessToken('user-access-token');
        $fb->setDefaultAccessToken($this->access_token);

        /**
        * Generate some requests and then send them in a batch request.
        */

        // Get the name of the logged in user
        $requestUserName = $fb->request('GET', '/me?fields=id,name', $params);

        // Get user likes
        $requestUserLikes = $fb->request('GET', '/me/likes?fields=id,name&amp;limit=1', $params);

        // Get user events
        $requestUserEvents = $fb->request('GET', '/me/events?fields=id,name&amp;limit=2', $params);

        // Post a status update with reference to the user's name
        $message = 'My name is {result=user-profile:$.name}.' . "\n\n";
        $message .= 'I like this page: {result=user-likes:$.data.0.name}.' . "\n\n";
        $message .= 'My next 2 events are {result=user-events:$.data.*.name}.';
        $statusUpdate = ['message' => $message];
        $requestPostToFeed = $fb->request('POST', '/me/feed', $statusUpdate);

        // Get user photos
        $requestUserPhotos = $fb->request('GET', '/me/photos?fields=id,source,name&amp;limit=2', $params);

        $batch = [
        'user-profile' => $requestUserName,
        'user-likes' => $requestUserLikes,
        'user-events' => $requestUserEvents,
        'post-to-feed' => $requestPostToFeed,
        'user-photos' => $requestUserPhotos,
        ];

        echo '<h1>Make a batch request</h1>' . "\n\n";

        try {
            $responses = $fb->sendBatchRequest($batch);
        } catch (Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        foreach ($responses as $key => $response) {
            if ($response->isError()) {
                $e = $response->getThrownException();
                echo '<p>Error! Facebook SDK Said: ' . $e->getMessage() . "\n\n";
                echo '<p>Graph Said: ' . "\n\n";
                var_dump($e->getResponse());
            } else {
                echo "<p>(" . $key . ") HTTP status code: " . $response->getHttpStatusCode() . "<br />\n";
                echo "Response: " . $response->getBody() . "</p>\n\n";
                echo "<hr />\n\n";
            }
        }
    }
}
