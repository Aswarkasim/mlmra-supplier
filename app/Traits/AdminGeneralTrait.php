<?php

namespace App\Traits;

use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Ramsey\Uuid\Uuid;

trait AdminGeneralTrait
{
    public function uploadMedia($file, $mediaType, $categoryType, $code)
    {
        dd();
        $uuid = Uuid::uuid4()->toString();
        $uuid2 = Uuid::uuid4()->toString();
        $fileType = $file->getClientOriginalExtension();
        $fileSize = $file->getSize();
        // $filename = $uuid . '-' . $uuid2 . '.' . $fileType;
        $filename = time() . "_" . $file->getClientOriginalName();
        // $path = $file->storeAs('public', $filename);
        $storage = 'uploads/images/';
        $file->move($storage, $file);

        $media = Media::create([
            'file_name' => $filename,
            'media_type' => $mediaType,
            'file_size' => $fileSize,
            'code' => $code,
            'category_type' => $categoryType
        ]);
        // print_r($media);
        // die;

        return $media;
    }
}
