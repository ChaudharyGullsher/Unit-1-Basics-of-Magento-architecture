<?php

declare(strict_types=1);

namespace RLTSquare\CustomEmailLogger\Controller\Index;

use Magento\Framework\App\ActionInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

/**
 * Controller file
 */
class Index implements ActionInterface
{
    /**
     * @var PageFactory
     */
    protected PageFactory $pageFactory;

    /**
     * @param PageFactory $pageFactory
     */
    public function __construct(
        PageFactory           $pageFactory
    ) {
        $this->pageFactory = $pageFactory;
    }

    /**
     * @return ResultInterface|Page
     */
    public function execute()
    {
        return $this->pageFactory->create();
    }

}
