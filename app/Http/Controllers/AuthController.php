<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function send(Request $request)
    {
        $phone = $request->post('number');
        if(!is_numeric($phone) || strlen($phone) !== 10)
        {
            $msg = 'Номер телефона не валиден!';
            $code = false;
            return response()->json(['msg'=> $msg, 'code' => $code], 200);
        }

        $pin = '';
        for($i = 0; $i < 4; $i++) {
            $pin .= mt_rand(0, 9);
        }
        $request->session()->put('pin', $pin);

        $url ='https://sms.ru/sms/send?api_id=[id]&to=7' . $phone
            .'&msg='. $pin .'&json=1';
        $response = Http::get($url);
        Log::info($response->status() . $phone);

        $msg = $pin;
        $code = true;
        return response()->json(['msg'=> $msg, 'code' => $code], 200);

    }

    public function login(Request $request)
    {
        $inputPin = $request->post('number');
        $pin = $request->session()->get('pin');
        if(strlen($pin) !== 4 || (int)$pin !== (int)$inputPin)
        {
            $msg =  "Код не валиден!\n";
            $code = false;
            return response()->json(['msg'=> $msg, 'code' => $code], 200);
        }
        $msg =  'Ok';
        $code = true;
        return response()->json(['msg'=> $msg, 'code' => $code], 200);
    }

    public function auth()
    {
        return view('auth');
    }
}
