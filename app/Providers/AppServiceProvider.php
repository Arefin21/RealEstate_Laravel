<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\SmtpSetting;
use Config;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        // if (\Schema::hasTable('smtp_setting')) {
        //     $smtpSetting = SmtpSetting::first();

        //     if ($smtpSetting) {
        //         Config::set('mail.driver', $smtpSetting->mailer);
        //         Config::set('mail.host', $smtpSetting->host);
        //         Config::set('mail.port', $smtpSetting->port);
        //         Config::set('mail.username', $smtpSetting->username);
        //         Config::set('mail.password', $smtpSetting->password);
        //         Config::set('mail.encryption', $smtpSetting->encryption);
        //         Config::set('mail.from.address', $smtpSetting->from_address);
        //         Config::set('mail.from.name', 'Realestate');
        //     }
        // }


        if(\Schema::hasTable('smtp_setting')){
            $smtpsetting=SmtpSetting::first();

            if($smtpsetting){
                $data=[
                    'driver'=>$smtpsetting->mailer,
                    'host'=>$smtpsetting->host,
                    'port'=>$smtpsetting->port,
                    'username'=>$smtpsetting->username,
                    'password'=>$smtpsetting->password,
                    'encryption'=>$smtpsetting->encryption,
                    'from'=>[
                        'address'=>$smtpsetting->from_address,
                        'name'=>'Realestate'
                    ]
                ];
                Config::set('mail',$data);
            }
        }
    }
}
