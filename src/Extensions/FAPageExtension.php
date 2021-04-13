<?php

namespace NSWDPC\Waratah\Extensions;

use SilverStripe\ORM\DataExtension;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\CheckboxField;

class FAPageExtension extends DataExtension
{

    private static $db = [
        'DisableFeedbackAssist' => 'Boolean'
    ];

    public function updateSettingsFields(FieldList $fields)
    {
        $fields->addFieldToTab('Root.FeedbackAssist', CheckboxField::create('DisableFeedbackAssist', "Disable Feedback Assist on this page"));

    }

}
