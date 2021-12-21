<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

//require '/home/uas.vocarito.com/public_html/UAS/Project_UAS_PW_Perpus/vendor/phpmailer/phpmailer/src/Exception.php';
//require '/home/uas.vocarito.com/public_html/UAS/Project_UAS_PW_Perpus/vendor/phpmailer/phpmailer/src/PHPMailer.php';
//require '/home/uas.vocarito.com/public_html/UAS/Project_UAS_PW_Perpus/vendor/phpmailer/phpmailer/src/SMTP.php';

class AuthController extends Controller
{

    public function register(Request $request)
    {
        $registrationData = $request->all();
        $validate = Validator::make($registrationData, [
            'nama_pengunjung' => 'required|max:60',
            'email' => 'required|email:rfc,dns|unique:users',
            'username' => 'required|max:10',
            'password' => 'required',
            'status' => 'required'
        ]);

        if($validate->fails()) 
            return response(['message' => $validate->errors()], 400);

        $registrationData['password'] = bcrypt($request->password);
        $user = User::create($registrationData);
        $hash = md5($user->id.$user->created_at);
        $user->remember_token = $hash;
        $user->save();
        
        $mail = new PHPMailer(true);
        
        try {
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;            
            $mail->isSMTP();                                          
            $mail->Host       = '1k.bytes.cloud';                  
            $mail->SMTPAuth   = true;                                   
            $mail->Username   = 'dummy';                 
            $mail->Password   = 'dummy';                          
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;         
            $mail->Port       = 465;                                   
        
            //Recipients
            $mail->setFrom('dummy', 'dummy');
            $mail->addAddress($user->email, $user->nama_pengunjung);     //Add a recipient
            $mail->addReplyTo('dummy', 'dummy');
        
        
            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = 'Email Verification';
            $mail->Body    = 'Selamat datang di Perpustakaan. Silahkan <a href="http://uas.vocarito.com/UAS/Project_UAS_PW_Perpus/public/verifikasi/' . $user->remember_token . '">Verifikasi akun</a> anda.<br><br>Terima kasih';
            //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
        
            $mail->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
        
        return response([
            'message' => 'Register Success',
            'user' => $user
        ], 200);
        
    }

    public function login(Request $request)
    {
        $loginData = $request->all();
        $validate = Validator::make($loginData, [
            'username' => 'required|max:10',
            'password' => 'required'
        ]);

        if($validate->fails()) 
            return response(['message' => $validate->errors()], 400);

        if (!Auth::attempt($loginData)) 
            return response(['message' => 'Invalid Credential'], 401);
        
        $user = Auth::user();
        $token = $user->createToken('Authentication Token')->accessToken;

        return response([
            'message' => 'Authenticated',
            'user' => $user,
            'token_type' => 'Bearer',
            'access_token' => $token
        ]);
    }

    public function logout(Request $request)
    {
        $token = $request->user()->token();
        $token->revoke();
        
        return response([
            'message' => 'Logout Success',
        ],200);
    }

    public function show($id){
        $user = User::find($id);

        if(!is_null($user)){
            return response([
                'message' => 'Retrive User Success',
                'data' => $user
            ],200);
        }

        return response([
            'message' => 'User Not Found',
            'data' => null
        ],404);
    }

    public function update(Request $request, $id){
        $user = User::find($id);

        if(is_null($user)){
            return response([
                'message' => 'User Not Found',
                'data' => $user
            ],404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData,[
            'nama_pengunjung' => 'required|max:60',
            'email' => ['required','email:rfc,dns', Rule::unique('users')->ignore($user)],
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()],400);
        
        $user->nama_pengunjung = $updateData['nama_pengunjung'];
        $user->email = $updateData['email'];
        
        if($user->save()){
            return response([
                'message' => 'Update User Success',
                'data' => $user
            ],200);
        }

        return response([
            'message' => 'Update User Failed',
            'data' => null
        ],400);
    }

    
}
