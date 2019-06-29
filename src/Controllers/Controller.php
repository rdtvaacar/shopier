<?php

namespace Acr\Ftr\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Auth;
use Mail;

class Controller extends BaseController
{
    function uyariMsj($msj)
    {
        return '<div class="col-md-2"></div><div class="alert alert-danger col-md-8" style=" margin-left:auto; margin-right:auto;  text-align:center; ">' . $msj . '</div>';
    }

    static function basariliMsj($msj)
    {
        return '<div class="col-md-2"></div><div class="alert alert-success col-md-8"  style=" margin-left:auto; margin-right:auto; text-align:center; ">' . $msj . '</div>';
    }

    static function basarili()
    {
        return '<div class="alert alert-success" style=" padding:4px; margin-left:auto; margin-right:auto; width:400px; text-align:center; ">Başarıyla Güncellendi</div>';
    }

    function eklendi()
    {
        return '<div class="alert alert-success" style=" padding:4px; margin-left:auto; margin-right:auto; width:400px; text-align:center; ">Başarıyla Eklendi</div>';
    }

    function ftr_mail($mail, $isim = null, $subject = null, $view = null, $ekMesaj = null)
    {
        $user = array(
            'email' => $mail,
            'name' => $isim,
            'subject' => $subject
        );
// the data that will be passed into the mail view blade template
        $data = array(
            'ek' => $ekMesaj,
            'name' => $user['name'],
        );
// use Mail::send function to send email passing the data and using the $user variable in the closure
        Mail::send($view, $data, function ($message) use ($user) {
            if (Auth::check()) {
                $message->from('info@mobilogrencitakip.com', @Auth::user()->ad);
            } else {
                $message->from('info@mobilogrencitakip.com', 'Mobil Öğrenci Takip');
            }
            $message->to($user['email'], $user['name'])->subject($user['subject']);
        });
    }
}
