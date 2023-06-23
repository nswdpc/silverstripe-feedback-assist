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
            'URLSegement' => 'test-page'
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

    public function testSiteConfigHash() {

        $siteConfig = SiteConfig::current_site_config();
        $siteConfig->EnableFeedbackAssist = 1;
        $siteConfig->FeedbackAssistHash = 'test-hash-test';
        $siteConfig->write();

        $page = \Page::create([
            'Title' => 'Test Page',
            'URLSegement' => 'test-page'
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
            'URLSegement' => 'test-page',
            'DisableFeedbackAssist' => 1
        ]);
        $page->publishSingle();

        $this->assertEquals(1, $siteConfig->EnableFeedbackAssist);

        $response = $this->get( $page->Link() );

        $this->assertEquals(200, $response->getStatusCode() );

        $body = $response->getBody();

        $this->assertFalse(
            strpos($body, FAContentControllerExtension::FA_INIT_URL)
        );

        $this->assertFalse(
            strpos($body, FAContentControllerExtension::FA_URL)
        );

    }


}