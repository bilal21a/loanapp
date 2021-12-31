<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
// use Intervention\Image\ImageManagerStatic as Image;
// use ImageOptimizer;

/**
 * Trait UploadAble
 * @package App\Traits
 */
trait UploadAble
{
    /**
     * @param UploadedFile $file
     * @param null $folder
     * @param string $disk
     * @param null $filename
     * @return false|string
     */
    public function uploadOne(UploadedFile $file, $folder = null, $disk = 'public', $filename = null)
    {
        $name = !is_null($filename) ? $filename : \Str::random(25);
        $fullpath = $file->storeAs(
            $folder,
            $name . "." . $file->getClientOriginalExtension(),
            $disk
        );
        //ImageOptimizer::optimize(asset('/storage/'.$fullpath));
        //Image::make(asset('/storage/'.$fullpath))->resize(710, 356);
        //\Log::info(asset('/storage/'.$fullpath));
        return $fullpath;
    }

    /**
     * @param null $path
     * @param string $disk
     */
    public function deleteOne($path = null, $disk = 'public_storage')
    {
        Storage::disk($disk)->delete($path);
    }

}
