<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 1/18/21, 2:10 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\API\v1\Attachment;

use iLaravel\Core\iApp\Attachment;
use iLaravel\Core\iApp\Exceptions\iException;
use iLaravel\Core\iApp\File;

use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;

trait Editors
{
    public function editors(Request $request, $type = "ckeditor")
    {
        $sizes = iconfig('attachments.uploader.editors.sizes', []);
        $name = iconfig("attachments.uploader.editors.$type.filename", 'upload');
        $fileattachment = \request()->file($name);
        $error = ["message" => _t('There was a problem uploading, please try again.')];
        try {
            if($fileattachment){
                \request()->files->remove($name);
                \request()->request->remove($name);
                $file = imodal('File');
                $attachment = $file::upload($request, $name);
                if ($attachment) {
                    $has_sizes = is_array($sizes) && count($sizes);
                    if ($has_sizes && preg_match(' /(?:image)/', $fileattachment->getClientMimeType())){
                        foreach ($sizes as $size)
                            try {
                                $file::imageSize($attachment, $size);
                            }catch(\Throwable $exception) {}
                    }
                    $original_name = iconfig("attachments.uploader.editors.$type.original_name", 'default');
                    $attachments = $attachment->attachments->groupBy('mode');
                    if ($original = $attachments->get('original')->first()) {
                        if ($has_sizes) {
                            $items = [];
                            foreach ($attachments as $index => $item)
                                $items[$index == "original" ? $original_name : rtrim($index, 'x')] = $item->url;
                            return ['urls' => $items];
                        }
                        return ['url' => $original->url];
                    }
                }
            }
        }catch (\Throwable $exception) {}
        return ['error' => $error];
    }
}
