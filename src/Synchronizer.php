<?php

declare(strict_types=1);

namespace BOA\Previews;

use BVP\Scraper\Scraper;
use Carbon\CarbonImmutable as Carbon;
use DateTimeInterface;

/**
 * @author shimomo
 */
final class Synchronizer
{
    /**
     * @param \DateTimeInterface|string $date
     * @return array<array-key, mixed>
     */
    public static function sync(DateTimeInterface|string $date = 'today'): array
    {
        $date = Carbon::parse($date, 'Asia/Tokyo');

        /** @var array<array-key, array<array-key, array<array-key, array{boats: array<mixed>}>>> $previews */
        $previews = Scraper::scrapePreviews($date);

        return self::normalize($previews);
    }

    /**
     * @param array<array-key, array<array-key, array<array-key, array{boats: array<mixed>}>>> $previews
     * @return array<array-key, mixed>
     */
    private static function normalize(array $previews): array
    {
        $newPreviews = [];

        foreach (array_values($previews) as $data) {
            foreach (array_values($data) as $preview) {
                $preview['boats'] = isset($preview['boats'])
                    ? array_values($preview['boats'])
                    : [];

                $newPreviews[] = $preview;
            }
        }

        return $newPreviews;
    }
}
