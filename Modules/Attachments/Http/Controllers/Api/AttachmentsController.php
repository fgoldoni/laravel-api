<?php

namespace Modules\Attachments\Http\Controllers\Api;

use App\Flag;
use Illuminate\Routing\Controller;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Translation\Translator;
use Modules\Attachments\Http\Requests\StoreAttachmentRequest;
use Modules\Attachments\Repositories\Contracts\AttachmentsRepository;

class AttachmentsController extends Controller
{
    /**
     * @var \Illuminate\Routing\ResponseFactory
     */
    private $response;
    /**
     * @var \Modules\Attachments\Repositories\Contracts\AttachmentsRepository
     */
    private $attachments;
    /**
     * @var \Illuminate\Translation\Translator
     */
    private $lang;

    public function __construct(ResponseFactory $response, AttachmentsRepository $attachments, Translator $lang)
    {
        $this->response = $response;
        $this->attachments = $attachments;
        $this->lang = $lang;
    }

    public function store(StoreAttachmentRequest $request)
    {
        if (class_exists($request->get('attachable_type'))
            && method_exists($request->get('attachable_type'), 'attachments')) {
            $subject = \call_user_func(
                $request->get('attachable_type') . '::find',
                (int) ($request->get('attachable_id'))
            );

            if ($subject) {
                try {
                    $attachment = $this->attachments->store($request->all());

                    $result['attachment'] = $attachment;
                    $result['status'] = Flag::STATUS_CODE_SUCCESS;
                    $result['success'] = true;
                    $result['message'] = $this->lang->get('messages.created', ['attribute' => 'File']);
                } catch (Exception $e) {
                    $result['status'] = Flag::STATUS_CODE_ERROR;
                    $result['success'] = false;
                    $result['message'] = $e->getMessage();
                }
            } else {
                return $this->response->json(
                    ['attachable_id' => 'This content can not receive a file'],
                    Flag::STATUS_CODE_ERROR,
                    [],
                    JSON_NUMERIC_CHECK
                );
            }
        } else {
            return $this->response->json(
                ['error' => 'method attachments undefined'],
                Flag::STATUS_CODE_ERROR,
                [],
                JSON_NUMERIC_CHECK
            );
        }

        return $this->response->json($result, $result['status'], [], JSON_NUMERIC_CHECK);
    }
}
