<?php

namespace NSWDPC\FeedbackAssist\Extensions;

use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Core\Extension;
use SilverStripe\SiteConfig\SiteConfig;
use SilverStripe\View\Requirements;

/**
 * When allowed, adds frontend requirements to the current request
 */
class FAContentControllerExtension extends Extension
{

    /**
     * @var string
     */
    const FA_INIT_URL = "https://www.onegov.nsw.gov.au/CDN/feedbackassist/feedbackassist.v1.min.js";

    /**
     * @var string
     */
    const FA_URL = "https://feedbackassist.onegov.nsw.gov.au/feedbackassist";

    /**
     * Provide requirements after controller init
     */
    public function onAfterInit()
    {
        self::provideRequirements( $this->owner->data() );
    }

    /**
     * Based on current page, provide frontend requirements (or not)
     */
    public static function provideRequirements(SiteTree $page) : bool {
        if($page->DisableFeedbackAssist == 1) {
            return false;
        }
        $siteConfig = SiteConfig::current_site_config();
        $feedbackAssistInitURL = self::FA_INIT_URL;
        $feedbackAssistURL = self::FA_URL;
        $attributes = [];
        if($siteConfig->FeedbackAssistHash) {
            $attributes['integrity'] = $siteConfig->FeedbackAssistHash;
            $attributes['crossorigin'] = "anonymous";
            $attributes['referrerpolicy'] = "no-referrer";
        }
        Requirements::javascript(
            $feedbackAssistInitURL,
            $attributes
        );
        Requirements::customScript("caBoootstrap.init('{$feedbackAssistURL}');");
        return true;
    }
}
