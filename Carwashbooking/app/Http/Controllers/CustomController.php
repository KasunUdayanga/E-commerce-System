<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use File;

class CustomController extends Controller
{
    public function uploadImage($image)
    {
        $file = $image;
        $fileName = uniqid() . '.' .$image->getClientOriginalExtension();
        $path = public_path() . '/images/upload';
        $file->move($path, $fileName);
        return $fileName;
    }

    public function deleteImage($image)
    {
        if($image != 'noimage.jpg')
        {
            if(File::exists(public_path('images/upload/'.$image))){
                File::delete(public_path('images/upload/'.$image));
            }
        }
    }
}
