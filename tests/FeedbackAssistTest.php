<?php

namespace NSWDPC\FeedbackAssist\Tests;

use NSWDPC\FeedbackAssist\Extensions\FASiteConfigExtension;
use NSWDPC\FeedbackAssist\Extensions\FAPageExtension;
use NSWDPC\FeedbackAssist\Extensions\FAContentControllerExtension;
use SilverStripe\CMS\Controllers\ContentController;
use SilverStripe\SiteConfig\SiteConfig;
use SilverStripe\View\Requirements;
use SilverStripe\Dev\FunctionalTest;

/**
 * Test to verify FA handling
 * @author James
 */
class FeedbackAssistTest extends FunctionalTest
{

    protected $usesDatabase = true;

    protected static $required_extensions = [
        \Page::class => [
            FAPageExtension::class,
        ],
        ContentController::class => [
            FAContentControllerExtension::class
        ],
        SiteConfig::class => [
            FASiteConfigExtension::class
        ]
    ];

    protected static $extra_dataobjects = [
        \Page::class
    ];

    public function testSiteConfig() {

        $siteConfig = SiteConfig::current_site_config();
        $siteConfig->EnableFeedbackAssist = 1;
        $siteConfig->FeedbackAssistHash = '';
        $siteConfig->write();

        $page = \Page::create([
            'Title' => 'Test Page',
            'URLSegment' => 'test-page'
        ]);
        $page->publishSingle();

        $this->assertEquals(1, $siteConfig->EnableFeedbackAssist);

        $response = $this->get( $page->Link() );

        $this->assertEquals(200, $response->getStatusCode() );

        $body = $response->getBody();

        $this->assertStringContainsString(
            FAContentControllerExtension::FA_INIT_URL,
            $body
        );

        $this->assertStringContainsString(
            FAContentControllerExtension::FA_URL,
            $body
        );

    }

    public function testSiteConfigNotEnabled() {

        $siteConfig = SiteConfig::current_site_config();
        $siteConfig->EnableFeedbackAssist = 0;
        $siteConfig->FeedbackAssistHash = '';
        $siteConfig->write();

        $page = \Page::create([
            'Title' => 'Test Page',
            'URLSegment' => 'test-page'
        ]);
        $page->publishSingle();

        $this->assertEquals(0, $siteConfig->EnableFeedbackAssist);

        $response = $this->get( $page->Link() );

        $this->assertEquals(200, $response->getStatusCode() );

        $body = $response->getBody();

        $this->assertStringNotContainsString(
            FAContentControllerExtension::FA_INIT_URL,
            $body
        );

        $this->assertStringNotContainsString(
            FAContentControllerExtension::FA_URL,
            $body
        );

    }

    public function testSiteConfigHash() {

        $siteConfig = SiteConfig::current_site_config();
        $siteConfig->EnableFeedbackAssist = 1;
        $siteConfig->FeedbackAssistHash = 'test-hash-test';
        $siteConfig->write();

        $page = \Page::create([
            'Title' => 'Test Page',
            'URLSegment' => 'test-page'
        ]);
        $page->publishSingle();

        $this->assertEquals(1, $siteConfig->EnableFeedbackAssist);

        $response = $this->get( $page->Link() );

        $this->assertEquals(200, $response->getStatusCode() );

        $body = $response->getBody();

        $this->assertStringContainsString(
            ' integrity="test-hash-test" ',
            $body
        );

        $this->assertStringContainsString(
            FAContentControllerExtension::FA_INIT_URL,
            $body
        );

        $this->assertStringContainsString(
            FAContentControllerExtension::FA_URL,
            $body
        );

    }

    public function testPageDisable() {

        $siteConfig = SiteConfig::current_site_config();
        $siteConfig->EnableFeedbackAssist = 1;
        $siteConfig->FeedbackAssistHash = 'test-fa-hash';
        $siteConfig->write();

        $page = \Page::create([
            'Title' => 'Test Page',
            'URLSegment' => 'test-page',
            'DisableFeedbackAssist' => 1
        ]);
        $page->publishSingle();

        $this->assertEquals(1, $siteConfig->EnableFeedbackAssist);

        $response = $this->get( $page->Link() );

        $this->assertEquals(200, $response->getStatusCode() );

        $body = $response->getBody();

        $this->assertStringNotContainsString(
            FAContentControllerExtension::FA_INIT_URL,
            $body
        );

        $this->assertStringNotContainsString(
            FAContentControllerExtension::FA_URL,
            $body
        );

    }


}
