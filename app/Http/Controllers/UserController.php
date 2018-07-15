<?php

namespace App\Http\Controllers;

use App\Http\Models\Cart;
use App\Http\Models\Favorit;
use App\Http\Models\Users;
use App\Http\Models\City;
use App\Http\Models\GuestCity;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

class UserController extends Controller
{
    public function __construct()
    {
        $this->users = New Users;
        $this->favorit = New Favorit();
        $this->cart = New Cart();
        $this->city = New City();
        // $this->guestcity = New GuestCity();
    }

    public function Login($lang)
    {
        $baseurl = 'http://mosanedproperty.com/';
        $input = Request()->all();
        $Email = $input['Email'];
        $Token = $input['TokenID'];
        $realbath = '/var/www/html/mosaned/public';
        $Password = MD5($input['Password']);


        $output = $this->users
            ->where('Email', '=', $Email)
            ->where('Password', '=', $Password)
            ->where('UseType', '=', 2)
            ->get();

        $output2 = $this->users
            ->where('Email', '=', $Email)
            ->where('UserState', '=', 1)
            ->get();

        if (count($output2) == 0) {

            if ($lang == 'ar') {
                $output = ['Error' => 'عفوا ..العضويه لم تفعل  '];

            } else if ($lang == 'en') {
                $output = ['Error' => 'This Account Not Activated'];

            }
            return Response()->json($output);
        } else if (Count($output) > 0) {

            $output = $this->users->where('Email', '=', $Email)->get();
            $id = $output['0']['UserID'];
            $this->users->where($this->users->getTable() . '.UserID', '=', $id)->update(['IsActive' => '1']);
            $this->SetUserIDInsteadIFToke($Token, $id);
            $this->SetUserIDInsteadIFTokeCart($Token, $id);
            return Response()->json($output['0']);


            return Response()->json($output);

        } else {
           
            if ($lang == 'ar') {
                $output = ['Error' => 'البريد الالكترونى او كلمة السر غير صحيحه'];

            } elseif ($lang == 'en') {
                $output = ['Error' => 'Thie Email Or Password Incorrect'];
            }


            return Response()->json($output);
        }
    }

    private function CreateChanles()
    {

        $length = 10;
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;

    }

    public function sendforgetmail(){


        $input = Request()->all();
        $Email = $input['Email'];
        $Name = $input['Name'];
        $randomString = $input['randomString'];
        $to = $Email;
        $subject = "New Password";
        $txt = "Dear" . ' ' . $Name . "There is a New Password" . ' :' . $randomString;
        $headers = "From: info@Ar5ss.com" . "\r\n";
        $headers .= "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        mail($to, $subject, $txt, $headers);

    }

    public function ForgetPassword($lang)
    {
        $input = Request()->all();
        $Email = $input['Email'];
        $output = $this->users->where('email', '=', $Email)->get();
        foreach ($output as $output) {
            $Name = $output->Name;
        }

        if (Count($output) > 0) {

            $randomString = $this->CreateChanles();
//            $to = $Email;
//            $subject = "New Password";
//            $txt = "Dear" . ' ' . $Name . "There is a New Password" . ' :' . $randomString;
//            $headers = "From: info@Ar5ss.com" . "\r\n";
//
//            mail($to, $subject, $txt, $headers);

            $url = "http://www.zadalsharq.com/ar5ss/public/api/mailforg";
            $postlength = array(
                'Email' => $Email,
                'Name' => $Name,
                'randomString' => $randomString
            );
            $ch = curl_init($url);
            # Setup request to send json via POST.
            $payload = json_encode($postlength);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
            # Return response instead of printing.
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            # Send request.
            echo $result = curl_exec($ch);
            curl_close($ch);



            $Newpassword = MD5($randomString);
            $this->users->where('Email', '=', $Email)->update(['password' => $Newpassword]);
            if ($lang == 'ar') {
                $outputMsg = ['Message' => 'تم ارسال كلمة السر الجديدة الى بريدك الالكترونى'];
            } else if ($lang == 'en') {
                $outputMsg = ['Message' => 'new password sent to your email'];
            }

            return Response()->json($outputMsg);

        } else {
            if ($lang == 'ar') {
                $output = ['Erorr' => 'هذا البريد الالكترونى غير مسجل لدينا'];
            } else if ($lang == 'en') {
                $output = ['Erorr' => 'This Email is NotExist'];
            }

        }
        return Response()->json($output);
    }

    public function create($lang)
    {
        $input = Request()->all();
        $Email = $input['Email'];

        $check = $this->users->where('Email', '=', $Email)->get();
        $checkNumber = $this->users->where('Mobile', '=', $input['Mobile'])->get();
        if (Count($check) > 0) {
            if ($lang == 'ar') {
                $output = ['Erorr' => 'هذا البريد الالكترونى  مسجل لدينا'];
            } else if ($lang == 'en') {
                $output = ['Erorr' => 'This Email is Exist'];
            }
            return Response()->json($output);
        } else if (Count($checkNumber) > 0) {
            if ($lang == 'ar') {
                $output = ['Error' => 'هذا الجوال  مسجل لدينا'];
            } else if ($lang == 'en') {
                $output = ['Error' => 'This Mobile is Exist'];
            }

            return Response()->json($output);

        } else {

            $baseurl = 'http://ar5ss.com/';
            $realbath = '/var/www/html/ar5ss/public';
            $input['UserState'] = 0;
            $output = $this->users->create($input);
            $input['Password'] = MD5($input['Password']);
            $id = $output['UserID'];
            $Token = $output['Token'];
            $Email = $output['Email'];
            $this->SetUserIDInsteadIFToke($Token, $id);
            $this->SetUserIDInsteadIFTokeCart($Token, $id);
            $image = $input["Image"];
            $jpg_name = "photo-" . time() . ".jpg";
            $path = $realbath . "/UserImages/" . $jpg_name;
            $input["Image"] = $baseurl . "UserImages/" . $jpg_name;
            $img = substr($image, strpos($image, ",") + 1);//take string after ,
            $imgdata = base64_decode($img);
            $success = file_put_contents($path, $imgdata);
            $output = Users::find($id)->update($input);
            $output = $this->users->where($this->users->getTable() . '.UserID', '=', $id)->get();


            $url = "http://www.zadalsharq.com/ar5ss/public/api/virfiyaccount";
            $postlength = array(
                'UserID' => $id,
                'Email' => $Email
            );
            $ch = curl_init($url);
            # Setup request to send json via POST.
            $payload = json_encode($postlength);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
            # Return response instead of printing.
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            # Send request.
            echo $result = curl_exec($ch);
            curl_close($ch);

            return Response()->json($output['0']);


        }

    }

    public function VirfiyAccount()
    {

        $input = Request()->all();
        $Email = 'info@ar5ss.com';
        $UserEmail = $input['Email'];
        $id = $input['UserID'];
        $Title = 'مرحبا بك فى أرخص .. هذه الرساله لتفعيل العضويه';

        $body = '<html>';
        $body .= '<head>';
        $body .= '<title>' . "Ar5ss app اهلا بك فى" . '</title>';
        $body .= '</head>';
        $body .= '<body>';
        $body .= '<h1> Ar5ss app شكرا لتسجيلك فى </h1>';
        $body .= ' <h3>  برجاء الضغط على الرابط ادناه  Ar5ss app للمتابعة وتفعيل العضويه الخاصه بك فى    </h3>';
        $body .= '<a href="http://188.226.135.249/api/acativateaccount/' . $id . '" target="_blank">اضغط هنا</a>';
        $body .= '<h4>شكرا ..</h4>';
        $body .= '</body>';
        $body .= '</html>';
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= 'From: Ar5ssApp<' . $Email . '>' . "\r\n";
        $to = $UserEmail;
        $subject = ".$Title.";
        echo mail($to, $subject, $body, $headers);

    }

    public function AcativateAccount($id)
    {

        $this->users->where($this->users->getTable() . '.UserID', $id)->update([
            "UserState" => 1
        ]);

        return view('welcome');


    }

    public function update($id)
    {
        $baseurl = 'http://ar5ss.com/';
        $realbath = '/var/www/html/ar5ss/public';
        $input = Request()->all();
        if ($input['Image'] == '') {
            $output = Users::find($id)->update([
                "Name" => $input["Name"],
                "Email" => $input["Email"],
                "Mobile" => $input["Mobile"],
                "CityID" => $input["CityID"],
                "Password" => md5($input["Password"]),
            ]);
            $output = Users::where('UserID', '=', $id)->get();
            return Response()->json($output['0']);

        } else {
            $image = $input["Image"];
            $jpg_name = "photo-" . time() . ".jpg";
            $path = $realbath . "/UserImages/" . $jpg_name;
            $input["Image"] = $baseurl . "UserImages/" . $jpg_name;
            $img = substr($image, strpos($image, ",") + 1);//take string after ,
            $imgdata = base64_decode($img);
            $success = file_put_contents($path, $imgdata);
            $output = Users::find($id)->update($input);
            $output = Users::where('UserID', '=', $id)->get();
            return Response()->json($output['0']);

        }


    }

    public function ContactUs()
    {

        $input = Request()->all();
//        $Email = $input['Email'];
//        $Title = $input['Title'];
//        $Body = $input['Body'];
//        $to = 'info@Ar5ss.com';
//        $subject = ".$Title.";
//        $txt = "'Dear'  . $to . ':'.$Body .'";
//        $headers = "from" . $Email;
//        mail($to, $subject, $txt, $headers);

        $url = "http://www.zadalsharq.com/ar5ss/public/api/contact";
        $postlength = array(
            'Email' => $input['Email'],
            'Title' => $input['Title'],
            'Body' => $input['Body'],
        );
        $ch = curl_init($url);
        # Setup request to send json via POST.
        $payload = json_encode($postlength);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        # Return response instead of printing.
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        # Send request.
        echo $result = curl_exec($ch);
        curl_close($ch);

        return ['stuts' => 'true'];


    }


    public function NotificationStutes($id)
    {
        $input = Request()->all();
        $Notification = $input['Notification'];
        $this->users->where($this->users->getTable() . '.UserID', '=', $id)
            ->update(['Notification' => $Notification]);
        return ['stutes' => 'true'];
    }

    private function SetUserIDInsteadIFToke($Token, $UserID)
    {
        $this->favorit->where($this->favorit->getTable() . '.TokenID', '=', $Token)->update(['UserID' => $UserID]);
    }

    private function SetUserIDInsteadIFTokeCart($TokenID, $UserID)
    {
        $this->cart->where($this->cart->getTable() . '.TokenID', '=', $TokenID)->update(['UserID' => $UserID]);
    }

    public function City()
    {
        $output = $this->city->all();
        return Response()->json($output);
    }

    public function setGuestCity()
    {
        $input = Request()->all();
        $TokenID = $input['TokenID'];
        $check = $this->guestcity->where($this->guestcity->getTable() . '.TokenID', '=', $TokenID)->get();
        if (count($check) > 0) {
            return Response()->json($check);
        } else {
            $output = $this->guestcity->create($input);
            return Response()->json($output);

        }


    }


}
