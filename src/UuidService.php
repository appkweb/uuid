<?php

/*
 * This file is part of Uuid package of appkweb.
 *
 * (c) Valentin REGNIER <vregnier@appkweb.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Appkweb\Uuid;

use Exception;

class UuidService
{
    /**
     * @return string
     * @throws Exception
     */
    public function generate(): string
    {

        $randomBytes = random_bytes(16);

        $timestamp = round(microtime(true) * 1000);
        $timeHex = sprintf('%012x', $timestamp);

        $pid = getmypid();
        $pidHex = sprintf('%04x', $pid);

        $additionalRandom = mt_rand(0, 0xffff);
        $additionalHex = sprintf('%04x', $additionalRandom);

        $hashInput = $timestamp . $pid . $additionalRandom . uniqid('', true);
        $hash = substr(hash('sha256', $hashInput), 0, 12);

        return sprintf(
            '%08s-%04s-4%03x-%04x-%012s',
            substr($timeHex, 0, 8),
            substr($timeHex, 8, 4),
            hexdec(substr($hash, 0, 3)),
            (hexdec(substr($hash, 3, 2)) & 0x3fff) | 0x8000,
            substr($hash, 5, 12)
        );
    }
}
