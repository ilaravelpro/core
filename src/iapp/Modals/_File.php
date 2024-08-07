<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 2/2/21, 6:58 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Modals;

use iLaravel\Core\iApp\Exceptions\iException;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Http\UploadedFile;
use iLaravel\Core\iApp\Http\Controllers\API\v1\AttachmentController;
use DB;

class _File extends Eloquent
{
    use Modal;

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
        return $attachmentController->store($attachmentRequest);
    }

    public static function uploadFromUrl($request, string $url, string $originalName = '', string $mimeType = null, int $error = null, bool $test = false)
    {
        if (!$stream = @fopen($url, 'r'))
            throw new iException('Not found :url', ['url' => $url]);
        $tempFile = tempnam(sys_get_temp_dir(), 'url-file-');
        file_put_contents($tempFile, $stream);
        $attachmentController = new AttachmentController($request);
        $attachmentRequest = new $request;
        $attachmentRequest->files->add(['file' => new UploadedFile($tempFile, $originalName, $mimeType, $error, $test)]);
        $attachment = $attachmentController->store($attachmentRequest);
        unlink($tempFile);
        return $attachment;
    }

    public static function move(\iLaravel\Core\iApp\Attachment $post, UploadedFile $temp, $disk = null, $data = [])
    {
        return static::specialMove([
            'post' => $post,
            'temp' => $temp,
            'disk' => $disk,
            'data' => $data,
        ]);
    }

    public static function specialMove($options = [])
    {
        $temp = $options['temp'];
        $post = $options['post'];
        $data = isset($options['data']) ? $options['data'] : [];
        $disk = isset($options['disk']) ? $options['disk'] : null;
        $disk = config('filesystems.disks.' . $disk, config('filesystems.disks.public'));
        $type = explode('/', $temp->getMimeType());
        $type = $type[0];

        $file_name = $post->serial . '_original' . '.' . $temp->extension();
        $folders = glob(_directory_separator(DIRECTORY_SEPARATOR, $disk['root'], 'upload', now()->format('Y/m/d'), 'f_*'));
        $last_folder = last($folders);
        $files_count = count(glob(_directory_separator(DIRECTORY_SEPARATOR, $last_folder, '*')));
        $folder_int = $post->created_at->format('Y/m/d/') . 'f_' . md5(static::serial((ceil($files_count / 1000) * 1000)));
        $folder_name = 'upload/' . $folder_int;
        $folder = _directory_separator(DIRECTORY_SEPARATOR,  $disk['root'], $folder_name);
        $file_slug = trim(str_replace(env('APP_URL'), '', _directory_separator('/', $disk['url'], $folder_name, $file_name)), '/');
        if (!file_exists($folder))
            mkdir($folder, 0777, true);
        $original_name = $temp->getClientOriginalName();
        $original_name = explode('.', $original_name);
        unset($original_name[count($original_name) - 1]);
        $original_name = ucfirst(str_replace(['-', '_', '.'], ' ', join('.', $original_name)));
        $file = static::create(
            array_merge_recursive([
                'post_id' => $post->id,
                'mode' => 'original',
                'slug' => $file_slug,
                'url' => _directory_separator('/', $disk['url'], $folder_name, $file_name),
                'dir' => _directory_separator(DIRECTORY_SEPARATOR, $folder, $file_name),
                'mime' => $temp->getMimeType(),
                'exec' => $temp->extension(),
                'type' => $type,
                'name' => $original_name,
            ], $data)
        );
        $post->update(['title' => $original_name]);
        try {
            $temp->move($folder, $file_name);
        } catch (\Exception $e) {
            copy($temp->getPathName(), join(DIRECTORY_SEPARATOR, [$folder, $file_name]));
        }

        return $file;
    }


    public static function imageSize($post, $width, $height = null, $mode = null)
    {
        try {
            if (!$mode) {
                $mode = "{$width}x";
                if ($height) {
                    $mode .= $height;
                }
            }
            $height = $height ?: $width;
            $original = static::where('post_id', $post->id)->where('mode', 'original')->first();
            DB::beginTransaction();
            $file_name = $post->serial . "_$mode." . $original->exec;
            static::where('slug', '')->delete();
            $file = static::create([
                'post_id' => $post->id,
                'mode' => $mode,
                'slug' => '',
                'url' => '',
                'dir' => '',
                'mime' => $original->mime,
                'exec' => $original->exec,
                'type' => $original->type,
                'name' => $original->name . " $mode",
            ]);
            $expload_original = explode(DIRECTORY_SEPARATOR, _directory_separator(DIRECTORY_SEPARATOR, $original->slug));
            $folder_int = $post->created_at->format('Y/m/d/') . $expload_original[count($expload_original) - 2];
            $folder = 'storage/upload/' . $folder_int;
            $file_slug = _directory_separator('/', "$folder/$file_name");
            if (!file_exists(public_path($folder))) {
                mkdir(public_path($folder), 0777, true);
            }
            $file->slug = $file_slug;
            $file->url = _directory_separator('/', asset($file_slug));
            $file->dir = public_path($file_slug);
            $file->save();
            $image_driver = in_array('Imagick', get_loaded_extensions()) || in_array('imagick', get_loaded_extensions()) ? "imagick" : "gd";
            $image = \Intervention\Image\ImageManager::$image_driver()->read($original->dir)
                ->scale($width, $height)
                ->save($file->dir);
            DB::commit();
        } catch (\Throwable $exception) {
        }
    }
}
