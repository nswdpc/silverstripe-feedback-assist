<?php

namespace NSWDPC\FeedbackAssist\Extensions;

use SilverStripe\ORM\DataExtension;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\CheckboxField;

class FAPageExtension extends DataExtension
{

    /**
     * @inheritdoc
     */
    private static $db = [
        'DisableFeedbackAssist' => 'Boolean'
    ];

    /**
     * @inheritdoc
     */
    public function updateSettingsFields(FieldList $fields)
    {
        $fields->addFieldToTab('Root.FeedbackAssist', CheckboxField::create('DisableFeedbackAssist', "Disable Feedback Assist on this page"));
    }
}
