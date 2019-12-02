<?php

namespace Modules\Attachments\Traits;

use Modules\Attachments\Entities\Attachment;

trait AttachableTrait
{
    public static function bootAttachableTrait()
    {
        self::deleted(function ($subject) {
            if ($subject->isForceDeleting()) {
                foreach ($subject->attachments()->get() as $attachment) {
                    $attachment->deleteFile();
                }
                $subject->attachments()->delete();
            }
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }
}
