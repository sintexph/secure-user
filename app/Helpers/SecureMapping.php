<?php

namespace App\Helpers;
use App\User;

class SecureMapping
{
    private const BASE_URI='http://127.0.0.1:8888';
    private const AUTHORIZATION='Bearer Gc5XSsWSRSpEdZc8n66NUQ6LtUoXyMYHUZLQAcvO';
    
    private static function request($url)
    {
        $basicauth = new \GuzzleHttp\Client(['base_uri' =>static::BASE_URI]);
        return $basicauth->request(
            'GET',
            $url,
            ['headers' => 
                [
                    'Authorization' => static::AUTHORIZATION,
                ]
            ]
        );
    }

    /**
     * FIND THE EMPLOYEE FROM DATA SOURCE USING ID NUMBER
     * @param $id_number
     */
    public static function get_users()
    {
        $ou=config('securemapping')['ou'];
        if($ou!=null)
        {
            $result=static::request('/get/'.$ou)->getBody()->getContents();
            return \json_decode($result);
        }
        return null;
    }

    public static function users_sync()
    {
        # Get the configuration
        $config=config('securemapping');

        $secure_users=static::get_users();
        foreach ($secure_users as $secure_user) {

            # Get the secure user information
            $su_details=$secure_user->user; 

            # Get the user from the database based on the identification
            $user=User::where($config['identification'],$su_details->{$config['identification']})->first();

            if($user!==null)
            {
                # Update the existing user
                foreach ($config['main_fields'] as $key => $value) {
                    $user->{$value}=$su_details->{$key};
                }
                $user->save();

            }else
            {
                # Create the user
                $user=new User;
                dump('create user');
            }
            

        }
    }


    





    
    


}