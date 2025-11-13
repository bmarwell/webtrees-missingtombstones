<?php

declare(strict_types=1);

namespace bmhm\WebtreesModules\MissingTombstones;

use Fisharebest\Webtrees\Individual;
use Fisharebest\Webtrees\Media;
use Fisharebest\Webtrees\Tree;
use PHPUnit\Framework\TestCase;

/**
 * Test suite for TombstoneListService
 * 
 * Tests the core functionality of the tombstone detection service,
 * focusing on the public API without requiring complex database setup.
 */
class TombstoneListServiceTest extends TestCase
{
    /**
     * Test that personHasTombstone returns false when given null
     * 
     * This is an edge case that should be handled gracefully.
     * A null person cannot have a tombstone.
     */
    public function testPersonHasTombstoneReturnsFalseForNull(): void
    {
        $result = TombstoneListService::personHasTombstone(null);
        
        $this->assertFalse($result, 'A null person should not have a tombstone');
    }

    /**
     * Test that personHasTombstone returns false for an individual with no media
     * 
     * An individual without any media attachments cannot have a tombstone.
     */
    public function testPersonHasTombstoneReturnsFalseForIndividualWithNoMedia(): void
    {
        // Create a mock individual with no media (empty gedcom)
        $individual = $this->createMock(Individual::class);
        $individual->method('gedcom')->willReturn("0 @I1@ INDI\n1 NAME Test /Person/");
        
        $result = TombstoneListService::personHasTombstone($individual);
        
        $this->assertFalse($result, 'An individual with no media should not have a tombstone');
    }

    /**
     * Test that the personHasTombstone method signature is correct
     * 
     * This test validates the method exists and accepts the expected parameter type.
     * Testing actual behavior with media requires a full webtrees environment setup
     * which is beyond the scope of unit tests.
     */
    public function testPersonHasTombstoneMethodExists(): void
    {
        $this->assertTrue(
            method_exists(TombstoneListService::class, 'personHasTombstone'),
            'TombstoneListService should have personHasTombstone method'
        );
        
        $reflection = new \ReflectionMethod(TombstoneListService::class, 'personHasTombstone');
        $this->assertTrue(
            $reflection->isStatic(),
            'personHasTombstone should be a static method'
        );
        $this->assertTrue(
            $reflection->isPublic(),
            'personHasTombstone should be public'
        );
    }
}
