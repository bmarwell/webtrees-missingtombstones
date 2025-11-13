<?php

declare(strict_types=1);

namespace bmhm\WebtreesModules\MissingTombstones;

use PHPUnit\Framework\TestCase;

/**
 * Test suite for MissingTombstonesModule
 * 
 * Tests the basic module functionality including metadata and instantiation.
 * These tests validate the module's basic properties without requiring
 * a full webtrees environment.
 */
class MissingTombstonesModuleTest extends TestCase
{
    /**
     * Test that the module can be instantiated
     * 
     * This is a basic smoke test to ensure the module class is valid.
     */
    public function testModuleCanBeInstantiated(): void
    {
        $module = new MissingTombstonesModule();
        
        $this->assertInstanceOf(
            MissingTombstonesModule::class,
            $module,
            'Module should be instantiable'
        );
    }

    /**
     * Test that the module's title method exists and is callable
     * 
     * The title is used in the webtrees UI to identify the module.
     * Note: We don't call the method directly as it requires I18N initialization.
     */
    public function testModuleHasTitleMethod(): void
    {
        $module = new MissingTombstonesModule();
        
        $this->assertTrue(
            method_exists($module, 'title'),
            'Module should have a title() method'
        );
    }

    /**
     * Test that the module's description method exists and is callable
     * 
     * The description explains what the module does to users.
     * Note: We don't call the method directly as it requires I18N initialization.
     */
    public function testModuleHasDescriptionMethod(): void
    {
        $module = new MissingTombstonesModule();
        
        $this->assertTrue(
            method_exists($module, 'description'),
            'Module should have a description() method'
        );
    }

    /**
     * Test that the module has an author name
     * 
     * The author name identifies who created/maintains the module.
     */
    public function testModuleHasAuthorName(): void
    {
        $module = new MissingTombstonesModule();
        $author = $module->customModuleAuthorName();
        
        $this->assertNotEmpty($author, 'Module should have an author name');
        $this->assertIsString($author, 'Module author name should be a string');
        $this->assertStringContainsString('Benjamin', $author, 'Author name should contain "Benjamin"');
    }

    /**
     * Test that the module has a version string
     * 
     * The version helps track module compatibility and updates.
     */
    public function testModuleHasVersion(): void
    {
        $module = new MissingTombstonesModule();
        $version = $module->customModuleVersion();
        
        $this->assertNotEmpty($version, 'Module should have a version');
        $this->assertIsString($version, 'Module version should be a string');
    }

    /**
     * Test that the module has a support URL
     * 
     * The support URL helps users find help and report issues.
     */
    public function testModuleHasSupportUrl(): void
    {
        $module = new MissingTombstonesModule();
        $supportUrl = $module->customModuleSupportUrl();
        
        $this->assertNotEmpty($supportUrl, 'Module should have a support URL');
        $this->assertIsString($supportUrl, 'Module support URL should be a string');
        $this->assertStringContainsString('github.com', $supportUrl, 'Support URL should point to GitHub');
    }

    /**
     * Test that the module has a latest version URL
     * 
     * This URL is used to check for module updates.
     */
    public function testModuleHasLatestVersionUrl(): void
    {
        $module = new MissingTombstonesModule();
        $latestVersionUrl = $module->customModuleLatestVersionUrl();
        
        $this->assertNotEmpty($latestVersionUrl, 'Module should have a latest version URL');
        $this->assertIsString($latestVersionUrl, 'Module latest version URL should be a string');
        $this->assertStringContainsString('github.com', $latestVersionUrl, 'Latest version URL should point to GitHub');
    }

    /**
     * Test that the module has a resources folder path
     * 
     * The resources folder contains views and other assets.
     */
    public function testModuleHasResourcesFolder(): void
    {
        $module = new MissingTombstonesModule();
        $resourcesFolder = $module->resourcesFolder();
        
        $this->assertNotEmpty($resourcesFolder, 'Module should have a resources folder path');
        $this->assertIsString($resourcesFolder, 'Resources folder path should be a string');
        $this->assertStringEndsWith('/', $resourcesFolder, 'Resources folder path should end with a slash');
    }
}
