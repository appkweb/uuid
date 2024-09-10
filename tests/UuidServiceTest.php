<?php

/*
 * This file is part of Uuid package of appkweb.
 *
 * (c) Valentin REGNIER <vregnier@appkweb.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Appkweb\Uuid\Tests;

use Appkweb\Uuid\Exception\UuidException;
use Appkweb\Uuid\UuidService;
use Exception;
use PHPUnit\Framework\TestCase;

class UuidServiceTest extends TestCase
{
    private UuidService $uuidService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->uuidService = new UuidService();
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testGenerateReturnsUuidv4Format(): void
    {
        $uuid = $this->uuidService->generate();

        $this->assertMatchesRegularExpression(
            '/^[a-f0-9]{8}-[a-f0-9]{4}-4[a-f0-9]{3}-[89ab][a-f0-9]{3}-[a-f0-9]{12}$/',
            $uuid,
            'UUID generated does not match the format of a version 4 UUID.'
        );
    }

    /**
     *
     * @return void
     * @throws Exception
     */
    public function testGenerateCollisionShouldBeOk(): void
    {
        $uuids = [];
        for ($i = 0; $i < 10000; $i++) {
            $uuid = $this->uuidService->generate();
            $this->assertNotContains($uuid, $uuids, 'UUID collision detected.');
            $uuids[] = $uuid;
        }
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testGenerateShouldBeOk(): void
    {
        for ($i = 0; $i < 100; $i++) {
            $this->assertNotEmpty($this->uuidService->generate());
        }
    }
}
