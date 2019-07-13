<?php

namespace App\Helpers;

class DataSourceHelper
{
    private const BASE_URI='http://datasource.sportscity.com.ph';
    private const AUTHORIZATION='Bearer $2y$10$5YeGyEwK5Q22Ow96p7LgUegf4ijK.vBcNf0GG862ks3jqi8rtKxm6';
    private const BEARER_NAME='employee';


    private static function request($url)
    {
        $basicauth = new \GuzzleHttp\Client(['base_uri' =>static::BASE_URI]);
        return $basicauth->request(
            'GET',
            $url,
            ['headers' => 
                [
                    'Authorization' => static::AUTHORIZATION,
                    'bearer-name' => static::BEARER_NAME
                ]
            ]
        );
    }

    /**
     * FIND THE EMPLOYEE FROM DATA SOURCE USING ID NUMBER
     * @param $id_number
     */
    public static function find_employee($id_number=null)
    {
        if($id_number!=null)
        {
            $result=static::request('/api/employee/find?q='.$id_number)->getBody()->getContents();
            return \json_decode($result);
        }
        return null;
    }




    public static function create_employee($id)
    {
        $data=static::find_employee($id)[0];
        
        $username=str_replace(" ","",(strtolower($data->first_name.'.'.$data->last_name)));

        $user=\App\User::create([
            'username'=>$username,
            'email'=>$username.'@sportscity.com.ph',
            'first_name'=>$data->first_name,
            'middle_name'=>$data->middle_name,
            'last_name'=>$data->last_name,
            'id_number'=>$data->id_number,
            'factory'=>$data->factory,
            'department'=>$data->department,
            'section'=>$data->section,
            'position'=>$data->position,
            'date_hired'=>$data->date_hired,
            'status'=>$data->status,
            'password'=>bcrypt($username),
        ]);

        return $user;
    }






    
    


}