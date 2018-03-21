<?
	if (!session_id()) {
		session_start();
	}
	
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	
	error_reporting(E_ALL);
	date_default_timezone_set('America/Sao_Paulo');
	require_once __DIR__ . '/Facebook/autoload.php';
	include_once "classes/usuario.php";
	include_once("classes/empresa.php");
	$fb = new Facebook\Facebook([
	  'app_id' => '992301767545937', // Replace {app-id} with your app id
	  'app_secret' => '8a3178131aa42764aa321c2605d6adb3',
	  'default_graph_version' => 'v2.2',
	  ]);
	$helper = $fb->getRedirectLoginHelper();
	if (isset($_GET['state'])) {
		$helper->getPersistentDataHandler()->set('state', $_GET['state']);
	}
	$uri = $_SERVER['REQUEST_URI'];
	$uris = explode("/",$uri);
	$empresaUrl = $uris[1];
	if($empresaUrl == "app"){
		$empresaId = 0;
	}else{
		$empresaId = Empresa::getUrl($empresaUrl);
	}
	//print_r($helper);
	try {
	  $accessToken = $helper->getAccessToken();
	} catch(Facebook\Exceptions\FacebookResponseException $e) {
	  // When Graph returns an error
	  echo 'Graph returned an error: ' . $e->getMessage();
	  exit;
	} catch(Facebook\Exceptions\FacebookSDKException $e) {
	  // When validation fails or other local issues
	  echo 'Facebook SDK returned an error: ' . $e->getMessage();
	  exit;
	}
	if (! isset($accessToken)) {
	  if ($helper->getError()) {
		header('HTTP/1.0 401 Unauthorized');
		echo "Error: " . $helper->getError() . "\n";
		echo "Error Code: " . $helper->getErrorCode() . "\n";
		echo "Error Reason: " . $helper->getErrorReason() . "\n";
		echo "Error Description: " . $helper->getErrorDescription() . "\n";
	  } else {
		header('HTTP/1.0 400 Bad Request');
		echo 'Bad request';
	  }
	  exit;
	}
	// Logged in
	//echo '<h3>Access Token</h3>';
	//var_dump($accessToken->getValue());
	// The OAuth 2.0 client handler helps us manage access tokens
	$oAuth2Client = $fb->getOAuth2Client();

	// Get the access token metadata from /debug_token
	$tokenMetadata = $oAuth2Client->debugToken($accessToken);
	//echo '<h3>Metadata</h3>';
	//var_dump($tokenMetadata);

	// Validation (these will throw FacebookSDKException's when they fail)
	$tokenMetadata->validateAppId('992301767545937'); // Replace {app-id} with your app id
	// If you know the user ID this access token belongs to, you can validate it here
	//$tokenMetadata->validateUserId('123');
	
	$tokenMetadata->validateExpiration();

	try {
	  // Returns a `Facebook\FacebookResponse` object
	  $response = $fb->get('/me?fields=id,name,email', $accessToken->getValue());
	} catch(Facebook\Exceptions\FacebookResponseException $e) {
	  echo 'Graph returned an error: ' . $e->getMessage();
	  exit;
	} catch(Facebook\Exceptions\FacebookSDKException $e) {
	  echo 'Facebook SDK returned an error: ' . $e->getMessage();
	  exit;
	}

	$user = $response->getGraphUser();

	$user_id = Usuario::getEmail($user['email'],$empresaId);
	if($user_id <= 0){
		$seed = str_split('abcdefghijklmnopqrstuvwxyz'
                 .'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
                 .'0123456789'); // and any other characters
		shuffle($seed); // probably optional since array_is randomized; this may be redundant
		$codigo = '';
		foreach (array_rand($seed, 7) as $k) $codigo .= $seed[$k];
		Usuario::cadastrar($user['email'],$codigo,$empresaId,3,$user['id'],$user['name']); //cadastrar($email,$senha,$facebook="",$nome="",$nascimento="")
		$user_id = Usuario::getEmail($user['email'],$empresaId);
		if($user_id > 0){
			$_SESSION["user_" . $empresaUrl] = $user_id;
			header('Location: '.'index.php');
		}else{
			$erro = 4;
		}
	}else{
			$_SESSION["user_" . $empresaUrl] = $user_id;
			header('Location: '.'index.php');
	}
	//echo 'Name: ' . $user['name'];
	//echo 'ID: ' . $user['id'];
	//echo 'Email: ' . $user['email'];
	/*if (! $accessToken->isLongLived()) {
	  // Exchanges a short-lived access token for a long-lived one
	  try {
		$accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
	  } catch (Facebook\Exceptions\FacebookSDKException $e) {
		echo "<p>Error getting long-lived access token: " . $helper->getMessage() . "</p>\n\n";
		exit;
	  }

	  echo '<h3>Long-lived</h3>';
	  var_dump($accessToken->getValue());
	}
	$_SESSION['fb_access_token'] = (string) $accessToken;*/

	// User is logged in with a long-lived access token.
	// You can redirect them to a members-only page.
	//header('Location: https://example.com/members.php');
?>