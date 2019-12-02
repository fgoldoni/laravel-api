<?php

namespace Modules\Attachments\Repositories\Contracts;

use Modules\Attachments\Entities\Attachment;

interface AttachmentsRepository
{
    public function store(array $attributes = []): Attachment;
}
