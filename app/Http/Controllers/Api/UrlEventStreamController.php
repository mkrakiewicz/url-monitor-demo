<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\UrlRepository;
use App\Repositories\UrlRequestRepository;
use App\UrlRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\StreamedResponse;

class UrlEventStreamController extends Controller
{
    /**
     * @var UrlRequestRepository
     */
    private $urlRequestRepository;

    public function __construct(UrlRequestRepository $urlRequestRepository)
    {
        $this->middleware('guest');
        $this->urlRequestRepository = $urlRequestRepository;
    }

    /**
     * @param UrlRepository $urlRepository
     * @param Request $request
     * @return StreamedResponse
     */
    public function streamLastRequest(User $user, Request $request)
    {
        $lastRequestId = null;
        $response = new StreamedResponse(function () use ($lastRequestId, $request) {
            while (true) {
                $currentLastRequestId = $this->urlRequestRepository->getLastRequest($request->user)->id;
                if ($lastRequestId !== $currentLastRequestId) {
                    $lastRequestId = $currentLastRequestId;
                    $response = json_encode(['lastRequestId' => $lastRequestId]);
                    echo "data: $response \n\n";
                    ob_flush();
                    flush();
                }
                sleep(2);
            }
        });
        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('X-Accel-Buffering', 'no');
        $response->headers->set('Cach-Control', 'no-cache');
        return $response;
    }
}
