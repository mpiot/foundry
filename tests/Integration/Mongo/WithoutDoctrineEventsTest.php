<?php

declare(strict_types=1);

/*
 * This file is part of the zenstruck/foundry package.
 *
 * (c) Kevin Bond <kevinbond@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Zenstruck\Foundry\Tests\Integration\Mongo;

use PHPUnit\Framework\Attributes\Test;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;
use Zenstruck\Foundry\Tests\Fixture\DoctrineEvents\DocumentForDoctrineEventsFactory;
use Zenstruck\Foundry\Tests\Fixture\DoctrineEvents\MongoDoctrineEventsListener;
use Zenstruck\Foundry\Tests\Integration\RequiresMongo;

use function Zenstruck\Foundry\Persistence\flush_after;

final class WithoutDoctrineEventsTest extends KernelTestCase
{
    use Factories, RequiresMongo, ResetDatabase;

    /**
     * @test
     */
    #[Test]
    public function mongo_events_are_called_by_default(): void
    {
        $document = DocumentForDoctrineEventsFactory::createOne(['name' => 'test']);

        self::assertSame('test (from Mongo event)', $document->name);
    }

    /**
     * @test
     */
    #[Test]
    public function it_can_disable_all_mongo_events(): void
    {
        $document = DocumentForDoctrineEventsFactory::new()
            ->withoutDoctrineEvents()
            ->create(['name' => 'test']);

        self::assertSame('test', $document->name);
    }

    /**
     * @test
     */
    #[Test]
    public function it_can_disable_specific_mongo_event_listener(): void
    {
        $document = DocumentForDoctrineEventsFactory::new()
            ->withoutDoctrineEvents(MongoDoctrineEventsListener::class)
            ->create(['name' => 'test']);

        self::assertSame('test', $document->name);
    }

    /**
     * @test
     */
    #[Test]
    public function mongo_events_are_restored_after_creation(): void
    {
        DocumentForDoctrineEventsFactory::new()
            ->withoutDoctrineEvents()
            ->create(['name' => 'first']);

        $document = DocumentForDoctrineEventsFactory::createOne(['name' => 'second']);

        self::assertSame('second (from Mongo event)', $document->name);
    }

    /**
     * @test
     */
    #[Test]
    public function it_throws_when_used_inside_flush_after(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('withoutDoctrineEvents() cannot be used inside flush_after().');

        flush_after(static function(): void {
            DocumentForDoctrineEventsFactory::new()
                ->withoutDoctrineEvents()
                ->create(['name' => 'test']);
        });
    }
}
