<?php

declare(strict_types=1);

namespace RLTSquare\CustomEmailLogger\Controller;

use Exception;
use Magento\Framework\App\Action\Forward;
use Magento\Framework\App\ActionFactory;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\Area;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\RouterInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Framework\View\Result\PageFactory;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use RLTSquare\CustomEmailLogger\Logger\Logger;

/**
 * Class Router
 */
class Router implements RouterInterface
{
    /**
     * @var PageFactory
     */
    protected PageFactory $pageFactory;
    /**
     * @var Logger
     */
    protected Logger $logger;
    /**
     * @var TransportBuilder
     */
    protected TransportBuilder $transportBuilder;
    /**
     * @var StoreManagerInterface
     */
    protected StoreManagerInterface $storeManager;
    /**
     * @var StateInterface
     */
    protected StateInterface $inlineTranslation;
    /**
     * @var ActionFactory
     */
    private ActionFactory $actionFactory;

    /**
     * Router constructor.
     *
     * @param ActionFactory $actionFactory
     * @param PageFactory $pageFactory
     * @param Logger $logger
     * @param TransportBuilder $transportBuilder
     * @param StoreManagerInterface $storeManager
     * @param StateInterface $state
     */
    public function __construct(
        ActionFactory         $actionFactory,
        PageFactory           $pageFactory,
        Logger                $logger,
        TransportBuilder      $transportBuilder,
        StoreManagerInterface $storeManager,
        StateInterface        $state
    ) {
        $this->actionFactory = $actionFactory;
        $this->pageFactory = $pageFactory;
        $this->logger = $logger;
        $this->transportBuilder = $transportBuilder;
        $this->storeManager = $storeManager;
        $this->inlineTranslation = $state;
    }

    /**
     * @param RequestInterface $request
     * @return ActionInterface|null
     */
    public function match(RequestInterface $request): ?ActionInterface
    {
        $identifier = trim($request->getPathInfo(), '/');
        if (strpos($identifier, 'rltsquare') !== false) {
            $request->setModuleName('customemaillogger');
            $request->setControllerName('index');
            $request->setActionName('index');
            $this->logger->info("page visited");
            $this->sendEmail();
            return $this->actionFactory->create(Forward::class, ['request' => $request]);
        }
        return null;
    }

    /**
     * @return void
     */
    public function sendEmail()
    {
        $templateId = 'email_template';
        $fromEmail = 'gullsher@chaudhary.com';
        $fromName = 'Gullsher';
        $toEmail = 'customer@email.com';

        try {
            $templateVars = [
                'name' => 'Ali',
                'message' => 'Hi, How are you?'
            ];

            $storeId = $this->storeManager->getStore()->getId();
            $from = ['email' => $fromEmail, 'name' => $fromName];
            $this->inlineTranslation->suspend();
            $storeScope = ScopeInterface::SCOPE_STORE;
            $templateOptions = [
                'area' => Area::AREA_FRONTEND,
                'store' => $storeId
            ];
            $transport = $this->transportBuilder->setTemplateIdentifier($templateId, $storeScope)
                ->setTemplateOptions($templateOptions)
                ->setTemplateVars($templateVars)
                ->setFromByScope($from)
                ->addTo($toEmail)
                ->getTransport();
            $transport->sendMessage();
            $this->inlineTranslation->resume();
        } catch (Exception $e) {
            $this->logger->info($e->getMessage());
        }
    }
}
