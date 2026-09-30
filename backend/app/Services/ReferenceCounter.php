<?php

namespace App\Services;

use App\Enums\ReferenceType;
use App\Models\ReferenceCounter as ReferenceCounterModel;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class ReferenceCounter
{
    /**
     * Lowest year that can be rendered as exactly four digits.
     */
    private const MIN_YEAR = 1000;

    /**
     * Highest year that can be rendered as exactly four digits.
     */
    private const MAX_YEAR = 9999;

    /**
     * Generate the next atomic reference for the given type and year.
     *
     * Format: PREFIX-YYYY-NNNNNN (6-digit sequential number)
     *
     * @throws InvalidArgumentException When the type is unknown or the year cannot be rendered as four digits.
     */
    public static function next(string|ReferenceType $type, ?int $year = null): string
    {
        $prefix = self::resolvePrefix($type);
        $year = self::resolveYear($year);

        // The unique index arbitrates concurrent first inserts. The loser observes
        // the existing row and the transaction below serializes all increments.
        $timestamp = now();

        ReferenceCounterModel::query()->insertOrIgnore([
            'prefix' => $prefix,
            'year' => $year,
            'current_value' => 0,
            'created_at' => $timestamp,
            'updated_at' => $timestamp,
        ]);

        return DB::transaction(function () use ($prefix, $year) {

            /** @var ReferenceCounterModel $counter */
            $counter = ReferenceCounterModel::query()
                ->where('prefix', $prefix)
                ->where('year', $year)
                ->lockForUpdate()
                ->firstOrFail();

            $counter->current_value++;
            $counter->save();

            return sprintf('%s-%04d-%06d', $prefix, $year, $counter->current_value);
        }, 5);
    }

    /**
     * Resolve and validate the year used to build the reference.
     *
     * The reference format requires exactly four digits, so any year outside
     * 1000..9999 must be rejected before the counter row is touched.
     *
     * @throws InvalidArgumentException
     */
    private static function resolveYear(?int $year): int
    {
        $year ??= (int) now()->format('Y');

        if ($year < self::MIN_YEAR || $year > self::MAX_YEAR) {
            throw new InvalidArgumentException(sprintf(
                'Invalid reference year [%d]. The year must be between %d and %d so that references match PREFIX-YYYY-NNNNNN.',
                $year,
                self::MIN_YEAR,
                self::MAX_YEAR
            ));
        }

        return $year;
    }

    /**
     * Validate and resolve the prefix for the given reference type.
     *
     * @throws InvalidArgumentException
     */
    public static function resolvePrefix(string|ReferenceType $type): string
    {
        if ($type instanceof ReferenceType) {
            return $type->value;
        }

        $enumType = ReferenceType::tryFrom($type);
        if ($enumType === null) {
            throw new InvalidArgumentException(sprintf(
                'Invalid reference type [%s]. Allowed types are: %s.',
                $type,
                implode(', ', ReferenceType::values())
            ));
        }

        return $enumType->value;
    }
}
