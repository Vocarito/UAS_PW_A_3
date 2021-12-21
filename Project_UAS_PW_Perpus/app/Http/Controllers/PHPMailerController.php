<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
 
class MailerController extends Controller {
 
    public function email() {
        return view("email");
    }

    public function composeEmailx(Request $request) {
        require base_path("vendor/autoload.php");
        $mail = new PHPMailer(true);     
 
        try {
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host = "dummy";        
            $mail->SMTPAuth = true;
            $mail->Username = "dummy"; 
            $mail->Password = 'dummy';    
            $mail->SMTPSecure = 'ssl';              
            $mail->Port = "456";                       
 
            $mail->setFrom('dummy', 'dummy');
            $mail->addAddress($request->emailRecipient);
            $mail->addCC($request->emailCc);
            $mail->addBCC($request->emailBcc);
 
            $mail->addReplyTo('dummy', 'dummy');
 
            if(isset($_FILES['emailAttachments'])) {
                for ($i=0; $i < count($_FILES['emailAttachments']['tmp_name']); $i++) {
                    $mail->addAttachment($_FILES['emailAttachments']['tmp_name'][$i], $_FILES['emailAttachments']['name'][$i]);
                }
            }
 
 
            $mail->isHTML(true);              
 
            $mail->Subject = $request->emailSubject;
            $mail->Body    = $request->emailBody;
 
            if( !$mail->send() ) {
                return back()->with("failed", "Email not sent.")->withErrors($mail->ErrorInfo);
            }
            
            else {
                return back()->with("success", "Email has been sent.");
            }
 
        } catch (Exception $e) {
             return back()->with('error','Message could not be sent.');
        }
    }
}