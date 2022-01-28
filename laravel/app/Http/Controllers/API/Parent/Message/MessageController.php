<?php


namespace App\Http\Controllers\API\Parent\Message;

use App\Http\Controllers\API\BaseController;
use App\Http\Requests\SendMessageRequest;
use App\Interfaces\IServices\IMessagingService;
use App\Models\Appointment;

class MessageController extends BaseController
{

    private IMessagingService $messageService;

    public function __construct(IMessagingService $messageService)
    {
        $this->middleware(['auth:parent']);
        $this->messageService = $messageService;
    }

    public function sendMessage(Appointment $appointment, SendMessageRequest $request): \Illuminate\Http\Response
    {
        try {
            $sent = $this->messageService->sendMessage(\auth()->user(), $appointment, $request->text);
            return $this->sendResponse($sent, 'Message sent');
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), null, $exception->getCode());
        }
    }

    public function getMessages(Appointment $appointment)
    {
        try {
            return $this->sendResponse($this->messageService->getMessages($appointment), 'Messages received');
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), null, $exception->getCode());
        }
    }
}
