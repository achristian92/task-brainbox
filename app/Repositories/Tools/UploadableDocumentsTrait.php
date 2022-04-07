<?php


namespace App\Repositories\Tools;


use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

trait UploadableDocumentsTrait
{
    public function handleDocumentS3($file,string $filename,$folder = 'documents/', $visibility = 'public')
    {
        Excel::store($file, $folder.$filename,'s3',null,[
            'visibility' => $visibility,
        ]);
        return  Storage::disk('s3')->url($folder.$filename);

    }
}
