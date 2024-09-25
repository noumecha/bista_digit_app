<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageUploadController extends Controller
{
    /**
     * @inheritdoc
     */
    public function upload (Request $request)
    {
        if($request->hasFile('image')){
            $image = $request->file('image');
            $imageName = $image->getClientOriginalName();
            $destinationPath = public_path('ckeditor-images');
            $image->move($destinationPath, $imageName);

            return response()->json([
                'success' => true,
                'fileName' => $imageName,
                'url' => asset('ckeditor-images/'.$imageName)
            ]);
        }
    }

    /**
     * @inheritdoc
     */
    public function storeImage(Request $request)
    {
        if($request->hasFile('upload'))
        {
            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $ext = $request->file('upload')->getClientOriginalExtension();
            $fileName = $fileName.'_'.time().'.'.$ext;

            $request->file('upload')->move(public_path('ckeditor-images'), $fileName);
            $url = asset('ckeditor-images/'.$fileName);

            return response()->json([
                'fileName' => $fileName,
                'uploaded' => 1,
                'url' => $url
            ]);
        }
    }
}
