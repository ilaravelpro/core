<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/1/20, 7:24 AM
 * Copyright (c) 2020. Powered by iamir.net
 */

namespace iLaravel\Core\iApp;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Http\UploadedFile;
use iLaravel\Core\iApp\Http\Controllers\API\v1\AttachmentController;
use Image;
use DB;

class File extends Eloquent
{
    use Modals\Modal;

    protected $guarded = [
        'id'
    ];

    public static $s_prefix = 'BF';
    public static $s_start = 24300000;
    public static $s_end = 728999999;

    public static function upload($request, $file)
    {
        $attachmentController = new AttachmentController($request);
        $attachmentRequest = new $request;
        $attachmentRequest->files->add(['file' => UploadedFile::createFromBase($request->file($file))]);
        $attachment = $attachmentController->store($attachmentRequest);
        return $attachment;
    }

    public static function move(Post $post, UploadedFile $temp, $disk = null, $data = [])
    {
        return static::specialMove([
            'post' => $post,
            'temp' => $temp,
            'disk' => $disk,
            'data' => $data,

        ]);
    }

    public static function specialMove($options = []){
        $temp = $options['temp'];
        $post = $options['post'];
        $data = isset($options['data']) ? $options['data'] : [];
        $disk = isset($options['disk']) ? $options['disk'] : null;
        $disk = config('filesystems.disks.' . $disk, config('filesystems.disks.public'));

        $type = explode('/', $temp->getMimeType());
        $type = $type[0];

        $file_name = $post->serial . '_original.' . $temp->extension();
        $folders = glob(join(DIRECTORY_SEPARATOR, [$disk['root'], 'Files_*']));
        $last_folder = last($folders);
        $files_count = count(glob(join(DIRECTORY_SEPARATOR, [$last_folder, '*'])));
        $folder_int = (string) (ceil($files_count / 1000) * 1000);

        $folder_name = 'Files_' . $folder_int;
        $folder = join(DIRECTORY_SEPARATOR, [$disk['root'], $folder_name]);
        $file_slug = trim(str_replace(env('APP_URL'), '', join('/', [$disk['url'], $folder_name, $file_name])), '/');
        if (!file_exists($folder)) {
            mkdir($folder, 0777, true);
        }
        $file = static::create(
            array_merge_recursive([
                'post_id' => $post->id,
                'mode' => 'original',
                'slug' => $file_slug,
                'url' => join('/', [$disk['url'], $folder_name, $file_name]),
                'dir' => join(DIRECTORY_SEPARATOR, [$folder, $file_name]),
                'mime' => $temp->getMimeType(),
                'exec' => $temp->extension(),
                'type' => $type,
            ], $data)
        );

        try{
            $temp->move($folder, $file_name);
        }catch (\Exception $e)
        {
            copy($temp->getPathName(), join(DIRECTORY_SEPARATOR, [$folder, $file_name]));
        }

        return $file;
    }


    public static function imageSize($post, $width, $height = null, $mode = null){
        if(!$mode)
        {
            $mode = "{$width}x";
            if($height)
            {
                $mode .= $height;
            }
        }
        $height = $height ?: $width;
        $original = static::where('post_id', $post->id)->where('mode', 'original')->first();
        DB::beginTransaction();
        $file_name = $post->serial . "_$mode." . $original->exec;
        $file = static::create([
            'post_id' => $post->id,
            'mode' => $mode,
            'slug' => '',
            'url' => '',
            'dir' => '',
            'mime' => $original->mime,
            'exec' => $original->exec,
            'type' => $original->type,
        ]);
        $folder_int = (string) (ceil($file->id / 1000) * 1000);
        $folder = 'storage/Files_' . $folder_int;
        $file_slug = "$folder/$file_name";
        if (!file_exists(public_path($folder))) {
            mkdir(public_path($folder), 0777, true);
        }

        $file->slug = $file_slug;
        $file->url = asset($file_slug);
        $file->dir = public_path($file_slug);
        $file->save();
        $image = Image::make($original->dir)
            ->resize($width, $height)
            ->save($file->dir);
        DB::commit();
    }
    public static function imageSize2($post, $width, $height = null, $mode = null){
        if(!$mode)
        {
            $mode = "{$width}x";
            if($height)
            {
                $mode .= $height;
            }
        }
        $height = $height ?: $width;
        $original = static::where('post_id', $post->id)->where('mode', 'original')->first();
        DB::beginTransaction();
        $file_name = $post->serial . "_$mode." . $original->exec;
        $file = static::create([
            'post_id' => $post->id,
            'mode' => $mode,
            'slug' => '',
            'url' => '',
            'dir' => '',
            'mime' => $original->mime,
            'exec' => $original->exec,
            'type' => $original->type,
        ]);
        $folder_int = (string) (ceil($file->id / 1000) * 1000);
        $folder = 'storage/Files_' . $folder_int;
        $file_slug = "$folder/$file_name";
        if (!file_exists(public_path($folder))) {
            mkdir(public_path($folder), 0777, true);
        }

        $file->slug = $file_slug;
        $file->url = asset($file_slug);
        $file->dir = public_path($file_slug);
        $file->save();

        $image = Image::make($original->dir)
            ->resize($width, $height)
            ->save($file->dir);
        DB::commit();
    }
}
