<?php declare(strict_types=1);
namespace caticons\Ace360CategoryIcons\Subscriber;
use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Shopware\Core\Framework\Struct\ArrayEntity;
use Shopware\Core\Framework\Context;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Shopware\Storefront\Page\Navigation\NavigationPageLoadedEvent;
use Shopware\Storefront\Pagelet\Header\HeaderPageletLoadedEvent;
use Shopware\Storefront\Pagelet\Footer\FooterPageletLoadedEvent;

class CategoryIcons implements EventSubscriberInterface
{
    private $systemConfigService;
    public function __construct(

        SystemConfigService $systemConfigService
    )
    {

        $this->systemConfigService = $systemConfigService;
    }

   public static function getSubscribedEvents(): array
    {
        return [
             FooterPageletLoadedEvent::class => 'onNavigationPageLoaded'
        ];
    }

    public function onNavigationPageLoaded(FooterPageletLoadedEvent $event)
    {
    
        $systemConfig = $this->systemConfigService->getDomain('Ace360CategoryIcons');
		//var_dump($systemConfig);
        if ($systemConfig) {
            foreach ($systemConfig as $key => $values) {
                $shortKey = str_replace('Ace360CategoryIcons.config.', '', $key);

                if (strpos($shortKey, 'sidebar') !== false) {
                $iconConfigValues['sidebar'][$shortKey] = $values;
                }
                elseif (strpos($shortKey, 'mobile') !== false) {
                    $iconConfigValues['mobile'][$shortKey] = $values;
                }
				 elseif (strpos($shortKey, 'IconWidth') !== false) {
                    $iconConfigValues['IconWidth'][$shortKey] = $values;
                }
				 elseif (strpos($shortKey, 'Iconheight') !== false) {
                    $iconConfigValues['Iconheight'][$shortKey] = $values;
                }
				elseif (strpos($shortKey, 'pluginMedia') !== false) {
                    $iconConfigValues['pluginMedia'][$shortKey] = $values;
                }
				elseif (strpos($shortKey, 'homelink') !== false) {
                    $iconConfigValues['homelink'][$shortKey] = $values;
                }
            }
			
            $event->getContext()->addExtension("Ace360CategoryIcons", new ArrayEntity($iconConfigValues));
			//var_dump($iconConfigValues);
        }
    }



}
