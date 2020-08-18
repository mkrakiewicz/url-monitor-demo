<?php

namespace App\Services;

use App\Repositories\UrlRepository;
use App\User;
use Illuminate\Support\Str;

class BulkUrlPersistenceService
{
    /**
     * @var UrlRepository
     */
    private $urlRepository;

    /**
     * @param UrlRepository $urlRepository
     */
    public function __construct(
        UrlRepository $urlRepository
    ) {
        $this->urlRepository = $urlRepository;
    }

    /**
     * @param array $urlsToAdd
     * @param bool $cutTrailingSlash
     */
    public function saveMany(User $user,array $urlsToAdd, bool $cutTrailingSlash = true): void
    {
        foreach ($urlsToAdd as $url) {
            if ($cutTrailingSlash) {
                if (Str::endsWith($url, ['/'])) {
                    $url = Str::replaceLast('/', '', $url);
                }
            }
            $this->urlRepository->persistByUrl($user,$url);
        }
    }
}
