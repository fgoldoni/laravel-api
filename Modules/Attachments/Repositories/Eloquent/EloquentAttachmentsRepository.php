<?php

/**
 * Created by PhpStorm.
 * User: goldoni
 * Date: 24.09.18
 * Time: 21:18.
 */

namespace Modules\Attachments\Repositories\Eloquent;

use App\Flag;
use App\Repositories\Criteria\Where;
use App\Repositories\RepositoryAbstract;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Modules\Attachments\Entities\Attachment;
use Modules\Attachments\Repositories\Contracts\AttachmentsRepository;
use Modules\Events\Entities\Event;

/**
 * Class EloquentPostsRepository.
 */
class EloquentAttachmentsRepository extends RepositoryAbstract implements AttachmentsRepository
{
    public function model()
    {
        return Attachment::class;
    }

    public function store(array $attributes = []): Attachment
    {
        $attachment = new Attachment([
            'attachable_type' => $attributes['attachable_type'],
            'attachable_id'   => $attributes['attachable_id'],
            'position'        => $attributes['position']
        ]);

        $attachment->loadParameters($attributes['attachment'], $attributes['folder']);

        $attachment->basename = $this->makeImage($attributes);

        if ('cover' === $attachment->type) {
            $attachments = $this->withCriteria([
                new Where('type', 'cover'),
                new Where('attachable_id', $attributes['attachable_id']),
                new Where('attachable_type', $attributes['attachable_type'])
            ])->get();

            foreach ($attachments as $file) {
                $file->delete();
            }
        }

        if (Event::class === $attributes['attachable_type']) {
            $attachments = $this->withCriteria([
                new Where('position', (int) $attributes['position']),
                new Where('attachable_id', $attributes['attachable_id']),
                new Where('attachable_type', $attributes['attachable_type'])
            ])->get();

            foreach ($attachments as $file) {
                $file->delete();
            }
        }

        $attachment->save();

        return $attachment;
    }

    private function makeImage(array $attributes = [])
    {
        if ('testing' === app()->environment()) {
            $driver = 'gd';
        } else {
            $driver = env('IMAGE_DRIVER', 'gd');
        }

        $manager = new ImageManager(['driver' => $driver]);

        $basename = $this->getBasename($attributes['attachment']->getClientOriginalExtension());

        $path = $this->getDirectory($attributes['folder']) . DS . $basename;

        if (isset($attributes['resize']) && (bool) $attributes['resize']) {
            $manager->make($attributes['attachment']->getRealPath())->orientate()->fit($attributes['width'], $attributes['height'])->save($path);
        } elseif (isset($attributes['position']) && (0 === (int) $attributes['position'])) {
            $manager->make($attributes['attachment']->getRealPath())->orientate()->fit(166, 39)->save($path);
        } else {
            $manager->make($attributes['attachment']->getRealPath())->orientate()->save($path);
        }

        return $basename;
    }

    private function getBasename(string $extension)
    {
        return  time() . '_' . mb_substr(str_replace('/', '', bcrypt(str_random(40))), -4) . '.' . $extension;
    }

    private function getDirectory(string $folder)
    {
        if (!\in_array($folder, Storage::disk(Flag::UPLOADS)->directories(), true)) {
            Storage::disk(Flag::UPLOADS)->makeDirectory($folder);
        }

        return Storage::disk(Flag::UPLOADS)->getDriver()->getAdapter()->getPathPrefix() . $folder;
    }

    public function save(array $attributes = []): Attachment
    {
        return $this->resolveModel()->create(
            $attributes
        );
    }
}
