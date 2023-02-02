<?php
namespace App\Http\Controllers\Api\Google;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use App\Models\SettingsApiKey;
/**
 * @author : Suriya
 * @return \Illuminate\Http\JsonResponse
 */
class GoogleController extends Controller
{

	private	$Setting;
	public function __construct()
	{
		$this->Setting = SettingsApiKey::find(1);
	}

	function Auth()
	{
		$Setting = $this->Setting;
		$header = base64_encode('{"alg":"RS256","typ":"JWT"}');
		$iat = time();
		$now = $iat+3600;
		$claimSet = base64_encode('{
  						"iss": "'.$Setting->fcm_acc.'",
  						"scope": "https://www.googleapis.com/auth/firebase.messaging",
  						"aud": "https://oauth2.googleapis.com/token",
  						"exp": '.$now.',
  						"iat": '.$iat.'
					}');
		$key = $Setting->fcm_key;
		openssl_sign(
		    $header.".".$claimSet,
		    $jwtSig,
		    "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCxkPnksN/lyy8h\n3n//Y2+FA/IS+z1zSnHxi7vyFu6FQA/VBq3Pz1dHVUpp5cTBp2bzdxip1jQP6ieZ\n4mnghty+xK8JUEpiB8c5p6UlDNhq7w4mHbhzc7O8hxyhhIjM6zkE2Xa4V6z5FxN5\nBgvSboiMD2a4sbm/2YExp3CgXaKF/LxleWXtX9mejIcF8zMKWyjHnlN3VJqWZkd6\ncE3B/IeUpH1iOq6DVmouFYjCFx6xTckoZ+BhzSgHspLV9Vuquv45Asj/UvJi9OsU\nEUI9aAVo7liLT4rx+vQCXEwYH52lPw4se6sEq2wzA/OMouxMyxa/9Am7PjpXgxGZ\nNqkLdtHlAgMBAAECggEAKhQTi/S4UkHYnPuXtIMxWcGqG4JrOQeCwf1doAx8IJqi\nRdar2MunQLG/DSJUgpmzhW2LLDAlLUJNeSOU+I6tAA4m9puOzPVP6mNGeOW24Xxe\nC31OiRl5leskx0fbjUgOvo30GtI4HN0xkaPzqD3mYN6DdrAzNvoJqiUmRPJ7gamS\nQDXuKv9pt3puKGinyuzlKATeEbZEQFchhsq/5thm1LH+tKpXYmJ7upX6IviE8DS5\nchLLAK4YVJcmD7PAHwK1y9NpSxY62euCq8PpcHRp+dLlwMgfTOEoLKfXqi7wwz77\nV+9+Nowf+JbtWBsNKzLantjL+K+PXL1NqrMZ2QG40QKBgQDZUDBsktM+1OIlwdGM\nMhMMU65rZbdyp5jbspqNK9/fsRF+OV5MB0yudAVcRyZyf72Oqpr3cBmc9qTdTvXp\nUhYBKfP6h/mGxIJZRBIAaDfPexePB0E4vWuGlfwL0wIq2/K8ix5Z4blJgDs/p7fp\n2OSNvCU0jK+DWv+eXCxfW4qP2QKBgQDRLV1f5mMnIbgRQccBLJWdukfpWFCuNQy0\nQJ934ivCnZyuuYu0VrzTptkZGsFVPQiKW7VN1UHScjmrKVtpf5tCETSePkH09Wa7\nmBdekwRXKAD51bfc39W0a/STBIe6VsYMqfalU4jBRrXYsYpBB2moYUnP9PJKv+Af\nfjQ88YsW7QKBgFJ2eHmq2Zh9JffX7ZF6qvnBg21jotJSQNIVm0o4vPJgedfhIyRM\nnM+SXTpgEXnfeWn62WVN15pVicglH3HTYWA9sESAdrKqPSBskTwwUAVem1j+EsTa\nNERVA9jk0Gy9HAZp8DGBU1NN4q3MEiEd2dTi8WdYf32j+V35cikZTqI5AoGBAJaS\nCB/LEF6PuBa7+YsP09cIy1Dd4J211CJATkoWhRd/KtTe/QBgW2YjOS0IBFjeKTKn\nFxgixVG+JWLez01erJzfE7hA6mtw5nVs4o2SWFKAmks8mzAj84n/F7toTGdGpSNi\niQey3ML7qlSEYBe1RUoOVfqawHosKiGhT+r6l/t1AoGAV1qVMP4oCRralBM13Jbp\niMfKlnN+rlfl14wsyBuU5/qpyzX+FAQedrE5i/A8zDkyFSD98UFIovf997QbHz44\nQFxzrEasi738jF/kEZyBJJSORBQqbntIp1LbMtESVxc5yTb+c8AYN6i5y3t/b90a\ny2h7CFcwvr5NRj56qt4Z39Q=\n-----END PRIVATE KEY-----\n",
		    "sha256WithRSAEncryption"
		);
		$jwtSign = base64_encode($jwtSig);
		$jwtAssertion = /*urlencode*/($header.".".$claimSet.".".$jwtSign);
		$req = Array(
				"grant_type" => "urn:ietf:params:oauth:grant-type:jwt-bearer",
				"assertion" => $jwtAssertion,
				);
		$app 		= Http::post('https://oauth2.googleapis.com/token',$req);
		$response 	= json_decode($app->body());
		$code 		= 422; 
		if($app->status() == 200){
			$Setting->fcm_token = $response->access_token;
			$Setting->save();
			return $response->access_token;
		}
		return false;
	}

	function checkAccessToken($token)
	{
		$req = Array( "access_token" => $token );
		$app = Http::get('https://www.googleapis.com/oauth2/v1/tokeninfo',$req);
		$response 	= json_decode($app->body());
		$code 		= 422; 
		if(!empty($app->status())){
			if($app->status() == 200){
				$code 	= 200; 
				return $token;
			}else{
				return $this->Auth();
			}
		}else{
				return $this->Auth();
		}
	}

	function Fcm(Request $request)
	{
		$Setting = $this->Setting;
		$token   = $request->token;
		$access  = $Setting->fcm_token;
		$access  = $this->checkAccessToken($access);
		if(!$access){
			$result['message'] = "Try after Sometime";
			return \Response::json($result,422);
		}
		$title   = $request->title;
		$body    = $request->body;
		// $req = Array(
		// 	"message" => Array(
		// 		"token"			=> $token,
		// 		"notification"	=> Array(
		// 			"title"		=> $title,
		// 			"body"		=> $body,
		// 			// 'sound'		=> 'knosh_notify.mp3',
		// 		),
		// 		"data"			=> Array(
		// 			"title"		=> $title,
		// 			"message"	=> $body,
		// 			// 'sound'		=> 'knosh_notify.mp3',
		// 		),
		// 	),
		// );

		$req = Array(
			"message" => Array(
				"token"			=> $token,
				"notification"	=> Array(
					"title"		=> $title,
					"body"		=> $body,					
 				),
				"data"			=> Array(
					"title"		=> $title,
					"message"	=> $body,
					'sound'		=> 'knosh_notify.mp3',
				),
				"android"	=> Array(
					"notification"	=> Array(
						"title"     => $title,
						"body"     => $body,
						"icon"	   => 'ic_notification',
						"sound"		=> 'knosh_notify.mp3',
						"default_sound" => "false"
					),
				)

			),
		);


// print_r(json_encode($req, JSON_PRETTY_PRINT));die;

		/*$req	= Array(
			"message"	=> Array(
				"token"			=> $token,
				"notification"	=> Array(
					"title"		=> $title,
					"body"		=> $body,
				),
				"data"			=> Array(
					"title"		=> $title,
					"message"	=> $body,
				),
			),
		);*/
		$app	= Http::withHeaders([
			'Authorization' => "Bearer ".$access,
		])->post('https://fcm.googleapis.com/v1/projects/'.$Setting->fcm_project.'/messages:send',$req);
		$response	= json_decode($app->body());
		$code 		= 422; 
		if(!empty($app->status())){
			if($app->status() == 200){
				$code 			   = 200; 
				$result['message'] = $response->name; 
			}else{
				
				$result['message'] = $response->error->message;
			}
		}else{
				$result['message'] = "Try after Sometime";
		}
		return \Response::json($result,$code);
	}

}
?>