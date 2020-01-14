<?php

namespace Modules\Attachments\Http\Controllers\Api;

use App\Exceptions\AttachmentException;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Translation\Translator;
use Modules\Attachments\Http\Requests\SaveAttachmentRequest;
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

    public function getAttachments()
    {
        try {
            return $this->responseJson(['data' => $this->attachments->all()]);
        } catch (Exception $e) {
            return $this->responseJsonError($e);
        }
    }

    public function save(SaveAttachmentRequest $request)
    {
        try {
            if (class_exists($request->get('attachable_type'))
                && method_exists($request->get('attachable_type'), 'attachments')) {
                $subject = \call_user_func(
                    $request->get('attachable_type') . '::find',
                    (int) ($request->get('attachable_id'))
                );

                if ($subject) {
                    $attachment = $this->attachments->save($request->all());
                    $result['attachment'] = $attachment;
                    $result['message'] = $this->lang->get('messages.created', ['attribute' => 'File']);

                    return $this->responseJson($result);
                }
                throw AttachmentException::notFileReceiveException();
            }
            throw AttachmentException::undefinedMethodException($request->get('attachable_type'));
        } catch (AttachmentException $e) {
            return $this->responseJsonError($e);
        } catch (Exception $e) {
            return $this->responseJsonError($e);
        }
    }

    public function store(StoreAttachmentRequest $request)
    {
        try {
            if (class_exists($request->get('attachable_type'))
                && method_exists($request->get('attachable_type'), 'attachments')) {
                $subject = \call_user_func(
                    $request->get('attachable_type') . '::find',
                    (int) ($request->get('attachable_id'))
                );

                if ($subject) {
                    $attachment = $this->attachments->store($request->all());

                    $result['attachment'] = $attachment;
                    $result['message'] = $this->lang->get('messages.created', ['attribute' => 'File']);

                    return $this->responseJson($result);
                }
                throw AttachmentException::notFileReceiveException();
            }
            throw AttachmentException::undefinedMethodException($request->get('attachable_type'));
        } catch (AttachmentException $e) {
            return $this->responseJsonError($e);
        } catch (Exception $e) {
            return $this->responseJsonError($e);
        }
    }

    public function destroy(int $id)
    {
        try {
            $result['attachment'] = $this->attachments->delete($id);
            $result['message'] = $this->lang->get('messages.deleted', ['attribute' => $result['attachment']->name]);

            return $this->responseJson($result);
        } catch (Exception $e) {
            return $this->responseJsonError($e);
        }
    }
}
