<?php

namespace NSWDPC\FeedbackAssist\Extensions;

use Silverstripe\ORM\DataExtension;
use Silverstripe\Forms\FieldList;
use Silverstripe\Forms\CheckboxField;
use Silverstripe\Forms\TextField;

class FASiteConfigExtension extends DataExtension
{
    /**
     * @inheritdoc
     */
    private static $db = [
        'EnableFeedbackAssist' => 'Boolean',
        'FeedbackAssistHash'   => 'Varchar(255)'
    ];

    /**
     * @inheritdoc
     */
    public function updateCMSFields(FieldList $fields)
    {
        $fields->addFieldToTab('Root.FeedbackAssist', CheckboxField::create('EnableFeedbackAssist', "Enable Feedback Assist on this site"));
        $fields->addFieldToTab('Root.FeedbackAssist', $faHashField = TextField::create('FeedbackAssistHash', 'Feedback Assist SRI Hash'));
        $faHashField->setDescription(
            'More information on <a target="_blank" href="https://scotthelme.co.uk/protect-site-from-cryptojacking-csp-sri/">CSP & SRI protection</a>.<br><a target="_blank" href="https://report-uri.com/home/sri_hash">Generate a new hash</a> for the Feedback Assist javascript (use the sha384 hash).'
        );
    }
}
