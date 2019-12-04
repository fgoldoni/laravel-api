<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\ResponseFactory;

class NotificationsController extends Controller
{
    /**
     * @var \Illuminate\Routing\ResponseFactory
     */
    private $response;

    public function __construct(ResponseFactory $response)
    {
        $this->response = $response;
    }

    public function notifications(Request $request)
    {
        $result = [];
        $notifications = $request->user()->unreadNotifications()->latest()->get();

        foreach ($notifications as $key => $item) {
            $result['notifications'][] = [
                'index'         => $key,
                'title'         => $item->data['title'],
                'msg'           => $item->data['msg'],
                'icon'          => $item->data['icon'],
                'time'          => $item->data['time'],
                'category'      => $item->data['category']
            ];
        }

        $result['user_id'] = $request->user()->id;
        $result['notificationsCount'] = $notifications->count();

        return $this->responseJson($result);
    }
}
