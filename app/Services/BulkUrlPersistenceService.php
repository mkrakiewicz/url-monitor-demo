<?php

namespace App\Services;

use App\Repositories\UrlRepository;
use App\Url;
use App\User;
use Illuminate\Support\Collection;
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
     * @return Collection|Url[]
     */
    public function saveMany(User $user, array $urlsToAdd, bool $cutTrailingSlash = true): Collection
    {
        $urlsCreated = collect();
        foreach ($urlsToAdd as $url) {
            if ($cutTrailingSlash) {
                if (Str::endsWith($url, ['/'])) {
                    $url = Str::replaceLast('/', '', $url);
                }
            }
            $urlsCreated->push($this->urlRepository->persistByUrl($user, $url));
        }
        return $urlsCreated;
    }
}
