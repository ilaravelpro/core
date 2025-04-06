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

    public static function findType($temp)
    {
        $type = explode('/', is_string($temp) ? $temp : $temp->getMimeType());
        $ext = is_string($temp) ? $type[1] : $temp->extension();
        $type = $type[0];
        $type = @$type[0] ?: 'other';
        foreach (static::$file_types as $name => $exts)
            if (in_array($ext, $exts))
                return $name;
        return $type;
    }

    public static function specialMove($options = [])
    {
        $temp = $options['temp'];
        $post = $options['post'];
        $data = isset($options['data']) ? $options['data'] : [];
        $disk = isset($options['disk']) ? $options['disk'] : null;
        $disk = config('filesystems.disks.' . $disk, config('filesystems.disks.public'));
        $type = static::findType($temp);
        $file_name = uniqid() . strtolower($post->serial) . uniqid();
        $folders = glob(_directory_separator(DIRECTORY_SEPARATOR, $disk['root'], 'upload', now()->format('Y/m/d'), '*'));
        $last_folder = last($folders);
        $files_count = count(glob(_directory_separator(DIRECTORY_SEPARATOR, $last_folder, '*')));
        $folder_int = $post->created_at->format('Y/m/d/') . md5(static::serial((ceil($files_count / 1000) * 1000)));
        $folder_name = "upload/{$type}/{$folder_int}";
        $folder = _directory_separator(DIRECTORY_SEPARATOR, $disk['root'], $folder_name);
        if (!file_exists($folder))
            mkdir($folder, 0777, true);
        $convertToWebP = $type == 'image' ? static::convertToWebP($temp->extension(), $temp, $folder, $file_name) : false;
        $file_name = $convertToWebP ?: ($folder_name . "." . $temp->extension());
        $file_slug = trim(str_replace(env('APP_URL'), '', _directory_separator('/', $disk['url'], $folder_name, $file_name)), '/');
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
                'mime' => $convertToWebP ? 'image/webp' : $temp->getMimeType(),
                'exec' => $convertToWebP ? 'webp' : $temp->extension(),
                'type' => $type,
                'name' => $original_name,
            ], $data)
        );
        $post->update(['title' => $original_name]);
        if (!$convertToWebP) {
            try {
                $temp->move($folder, $file_name);
            } catch (\Exception $e) {
                copy($temp->getPathName(), join(DIRECTORY_SEPARATOR, [$folder, $file_name]));
            }
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
            $file_name = pathinfo($original->slug, PATHINFO_FILENAME) . "-$mode." . $original->exec;
            static::where('slug', '')->delete();
            $expload_original = explode(DIRECTORY_SEPARATOR, _directory_separator(DIRECTORY_SEPARATOR, $original->slug));
            $folder_int = $post->created_at->format('Y/m/d/') . $expload_original[count($expload_original) - 2];
            $folder = 'storage/upload/' . static::findType($original->mime) . '/' . $folder_int;
            $file_slug = _directory_separator('/', "$folder/$file_name");
            if (!file_exists(public_path($folder)))
                mkdir(public_path($folder), 0777, true);

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

            $file->slug = $file_slug;
            $file->url = _directory_separator('/', asset($file_slug));
            $file->dir = public_path($file_slug);
            $file->save();
            $image = static::resizeImage(static::imageDriver()->read($original->dir), $width, $height)->save($file->dir);
            DB::commit();
        } catch (\Throwable $exception) {
            dd($exception);
        }
    }

    public static function imageDriver(): \Intervention\Image\ImageManager
    {
        $image_driver = in_array('Imagick', get_loaded_extensions()) || in_array('imagick', get_loaded_extensions()) ? "imagick" : "gd";
        return \Intervention\Image\ImageManager::$image_driver();
    }


    public static function convertToWebP($ext, $temp, $file_dir, $file_name)
    {
        try {
            $image = static::imageDriver()->read($temp);
            if ($image->driver()->supports($ext)) {
                $image = static::resizeImage($image->orient(), 4096);
                $is_webp = in_array($ext, ['webp', 'svg']);
                if (!$is_webp)
                    $image->toWebp(82);
                $file_name = $file_name . "." . ($is_webp ? $ext : 'webp');
                $image->save("{$file_dir}/" . $file_name);
                return $file_name;
            }
        } catch (\Throwable $exception) {
        }
        return false;
    }

    public static function resizeImage(\Intervention\Image\Interfaces\ImageInterface $image, $newWidth, $newHeight = null): \Intervention\Image\Interfaces\ImageInterface
    {
        $width = $image->width();
        $height = $image->height();
        if (!$newHeight) $newHeight = $newWidth;
        if ($width == $height) {
            $image->resizeDown($width > $newWidth ? $newWidth : $width, $height > $newHeight ? $newHeight : $height);
        } elseif ($height > $newHeight && $height > $width) {
            $image->resizeDown(ceil($width * $newHeight / $height), $newHeight);
        } elseif ($width > $newWidth)
            $image->resizeDown($newWidth, ceil($height * $newWidth / $width));
        return $image;
    }

    public static $file_types = [
        "archive" => ["7z", "a", "apk", "ar", "bz2", "cab", "cpio", "deb", "dmg", "egg", "gz", "iso", "jar", "lha", "mar", "pea", "rar", "rpm", "s7z", "shar", "tar", "tbz2", "tgz", "tlz", "war", "whl", "xpi", "zip", "zipx", "xz", "pak"],
        "audio" => ["aac", "aiff", "ape", "au", "flac", "gsm", "it", "m3u", "m4a", "mid", "mod", "mp3", "mpa", "pls", "ra", "s3m", "sid", "wav", "wma", "xm"],
        "book" => ["mobi", "epub", "azw1", "azw3", "azw4", "azw6", "azw", "cbr", "cbz"],
        "code" => ["1.ada", "2.ada", "ada", "adb", "ads", "asm", "bas", "bash", "bat", "c", "c++", "cbl", "cc", "class", "clj", "cob", "cpp", "cs", "csh", "cxx", "d", "diff", "e", "el", "f", "f77", "f90", "fish", "for", "fth", "ftn", "go", "groovy", "h", "hh", "hpp", "hs", "html", "htm", "hxx", "java", "js", "jsx", "jsp", "ksh", "kt", "lhs", "lisp", "lua", "m", "m4", "nim", "patch", "php", "pl", "po", "pp", "py", "r", "rb", "rs", "s", "scala", "sh", "swg", "swift", "v", "vb", "vcxproj", "xcodeproj", "xml", "zsh"],
        "exec" => ["exe", "msi", "bin", "command", "sh", "bat", "crx", "bash", "csh", "fish", "ksh", "zsh"],
        "font" => ["eot", "otf", "ttf", "woff", "woff2"],
        "image" => ["jpeg", "jpg", "png", "gif", "bmp", "webp", "tiff", "tif", "heic", "heif", "avif",
            "raw", "cr2", "cr3", "dng", "arw", "nef", "orf", "sr2", "raf", "mrw", "kdc", "pef",
            "jng", "jp2", "jpc", "jpe", "jps", "jpt", "jbg", "pbm", "pgm", "ppm", "pcx", "tga",
            "xpm", "ico", "cur", "xif", "jpf", "pict", "pix", "bpg", "hdri", "3dm", "3ds", "max", "bmp", "dds", "gif", "jpg", "jpeg", "png", "psd", "xcf", "tga", "thm", "tif", "tiff", "yuv", "ai", "eps", "ps", "svg", "dwg", "dxf", "gpx", "kml", "kmz", "webp"],
        "sheet" => ["ods", "xls", "xlsx", "csv", "ics", "vcf"],
        "slide" => ["ppt", "odp"],
        "document" => ["doc", "docx", "ebook", "log", "md", "msg", "odt", "org", "pages", "pdf", "rtf", "rst", "tex", "txt", "wpd", "wps"],
        "video" => ["3g2", "3gp", "aaf", "asf", "avchd", "avi", "drc", "flv", "m2v", "m4p", "m4v", "mkv", "mng", "mov", "mp2", "mp4", "mpe", "mpeg", "mpg", "mpv", "mxf", "nsv", "ogg", "ogv", "ogm", "qt", "rm", "rmvb", "roq", "srt", "svi", "vob", "webm", "wmv", "yuv"],
        "web" => ["html", "htm", "css", "js", "jsx", "less", "scss", "wasm", "php"]
    ];
}
