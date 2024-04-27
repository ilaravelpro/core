<?php

/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 12/20/20, 8:27 AM
 * Copyright (c) 2020. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Traits;


trait SaveAttachments
{
    public function save_attachments($data, $request) {
        if (method_exists($this, 'attachments') && _get_value($data, 'attachments')) {
            $attachments = [];
            $modelAttachment = imodal('Attachment');
            $deletesAttachments = [];
            foreach (_get_value($data, 'attachments', []) as $index => $tgallery) {
                if (isset($tgallery['file'])){
                    $attachment = $this->saveAttachment("attachments.$index.file", $request);
                    if ($attachment)
                        $attachments[$attachment->id] = [
                            'type' => $index,
                        ];
                    unset($attachment);
                    if (isset($tgallery['id']))
                        $deletesAttachments[] = $modelAttachment::id($tgallery['id']);
                }else{
                    if (isset($tgallery['uploads']) && count((array) $tgallery['uploads'])){
                        foreach ($tgallery['uploads'] as $key => $gallery) {
                            $attachment = $this->saveAttachment("attachments.$index.uploads.$key", $request);
                            if ($attachment)
                                $attachments[$attachment->id] = [
                                    'type' => $index,
                                ];
                            unset($attachment);
                        }
                    }
                    if (isset($tgallery['deletes']) && count((array) $tgallery['deletes'])){
                        foreach ($tgallery['deletes'] as $adelete) {
                            $deletesAttachments[] = $modelAttachment::id($adelete);
                        }
                    }
                }
            }
            $this->attachments()->detach($deletesAttachments);
            $modelAttachment::destroy($deletesAttachments);
            $this->attachments()->attach($attachments);
        }
    }
}
