<?php

declare(strict_types=1);

namespace RLTSquare\CustomEmailLogger\Block;

use Magento\Framework\Phrase;
use Magento\Framework\View\Element\Template;

/**
 * Block file for render data or functions in phtml file
 */
class TestMessage extends Template
{
    /**
     * @return Phrase
     */
    public function welcomeNote(): Phrase
    {
        return __('Test');
    }
}
