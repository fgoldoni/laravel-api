<?php

namespace Modules\Attachments\Tests\Feature;

use App\Flag;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Modules\Attachments\Entities\Attachment;
use Modules\Attachments\Notifications\AttachmentCreated;
use Tests\TestCase;

class AttachmentsApiTest extends TestCase
{
    /**
     * @var array
     */
    private $headers;

    protected function setUp(): void
    {
        parent::setUp();
        $token = $this->admin->makeVisible('api_token')->api_token;
        $this->headers = ['Authorization' => "Bearer $token"];
    }

    /**
     * A basic unit test example.
     */
    public function testAttachmentsAreListedCorrectly(): void
    {
        factory(Attachment::class, 10)->create();

        $response = $this->json('GET', 'api/attachments', [], $this->headers);

        $this->responseBody = $response->getContent();

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'attachable_id',
                        'attachable_type',
                        'type',
                        'extension',
                        'mime_type',
                        'size',
                        'author',
                        'basename',
                        'url',
                        'updated_at',
                        'created_at'
                    ]
                ],
                'success',
                'status',
            ]);
    }

    /**
     * A basic unit test example.
     */
    public function testAttachmentsAreStoredCorrectly(): void
    {
        Notification::fake();
        Notification::assertNothingSent();
        Storage::fake(Flag::UPLOADS);

        $payload = [
            'attachment'                  => UploadedFile::fake()->image('avatar.jpg'),
            'attachable_id'               => $this->user->id,
            'attachable_type'             => \get_class($this->user),
            'folder'                      => 'users',
            'resize'                      => false,
        ];

        $response = $this->json('POST', 'api/attachments/store', $payload, []);

        $this->responseBody = $response->getContent();

        Storage::disk(Flag::UPLOADS)->assertExists($this->getBasename());

        $response->assertStatus(200)
            ->assertJsonStructure([
                'attachment' => [
                    'id',
                    'name',
                    'attachable_id',
                    'attachable_type',
                    'type',
                    'extension',
                    'mime_type',
                    'size',
                    'author',
                    'basename',
                    'url',
                    'updated_at',
                    'created_at'
                ],
                'success',
                'status',
            ]);

        $payload = $this->getResponseAsArray()['attachment'];

        $this->assertDatabaseHas('attachments', [
            'id'              => $payload['id'],
            'name'            => $payload['name'],
            'basename'        => $payload['basename'],
            'attachable_id'   => $payload['attachable_id'],
            'attachable_type' => $payload['attachable_type'],
            'created_at'      => $payload['created_at'],
        ]);

        Notification::assertSentTo(
            [$this->admin],
            AttachmentCreated::class
        );
    }

    /**
     * A basic unit test example.
     */
    public function testAttachmentsAreDeletedCorrectly(): void
    {
        Notification::fake();

        Notification::assertNothingSent();

        $attachment = factory(Attachment::class)->create();

        $this->json('DELETE', 'api/attachments/' . $attachment->id, [], [])
            ->assertStatus(200)
            ->assertJsonStructure([
                'attachment' => [
                    'id',
                    'name',
                    'attachable_id',
                    'attachable_type',
                    'type',
                    'extension',
                    'mime_type',
                    'size',
                    'author',
                    'basename',
                    'url',
                    'updated_at',
                    'created_at'
                ],
                'message',
                'success',
                'status',
            ]);

        $attachment = $attachment->fresh();

        $this->assertNull($attachment);
    }

    private function getBasename()
    {
        return  $this->getResponseAsArray()['attachment']['type'] . DS . $this->getResponseAsArray()['attachment']['basename'];
    }
}
