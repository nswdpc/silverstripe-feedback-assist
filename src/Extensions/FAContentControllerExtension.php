<?php

namespace NSWDPC\FeedbackAssist\Extensions;

use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Core\Extension;
use SilverStripe\SiteConfig\SiteConfig;
use SilverStripe\View\Requirements;

class FAContentControllerExtension extends Extension
{

    const FA_INIT_URL = "https://www.onegov.nsw.gov.au/CDN/feedbackassist/feedbackassist.v1.min.js";
    const FA_URL = "https://feedbackassist.onegov.nsw.gov.au/feedbackassist";

    public function onAfterInit()
    {
        self::provideRequirements( $this->owner->data() );
    }

    public static function provideRequirements(SiteTree $page) : bool {
        if($page->DisableFeedbackAssist == 1) {
            return false;
        }
        $siteConfig = SiteConfig::current_site_config();
        if($siteConfig->EnableFeedbackAssist == 0) {
            return false;
        }
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
