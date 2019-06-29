<?php

namespace Acr\Ftr\Controllers;

use Acr\Ftr\Model\AcrUser;
use Mail;
use Auth;
use View;

class MailController
{
    function mailGonder($view = null, $mail, $isim = null, $subject = null, $ekMesaj = null)
    {
        $user_model = new AcrUser();
        $admin      = $user_model->find(1)->first();
        $email      = empty(Auth::user()->email) ? Auth:: user()->username : Auth::user()->email;
        $admin_mail = empty($admin->email) ? $admin->username : $admin->email;
        $user       = array(
            'email'   => $mail,
            'isim'    => $isim,
            'subject' => $subject
        );
// the data that will be passed into the mail view blade template
        $data = array(
            'ek'   => $ekMesaj,
            'isim' => $user['isim'],
        );

        $user_name = empty(Auth::user()->name) ? Auth::user()->ad : Auth::user()->$email;
        $from      = $admin_mail;
// use Mail::send function to send email passing the data and using the $user variable in the closure
        Mail::send('acr_shopier::' . $view, $data, function ($message) use ($user, $user_name, $from) {
            $message->from($from, $user_name);
            $message->to($user['email'], $user['isim'])->subject($user['subject']);
        });
    }
}

?>