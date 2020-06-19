<?php declare(strict_types=1);

namespace caticons\Ace360CategoryIcons;
use Shopware\Core\Framework\Plugin;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\Plugin\Context\InstallContext;
use Shopware\Core\Framework\Plugin\Context\UninstallContext;
use Shopware\Core\Framework\Uuid\Uuid;
use Doctrine\DBAL\Connection;
use Shopware\Core\Defaults;

class Ace360CategoryIcons extends Plugin
{
   public function Install(InstallContext $context): void
    {
        parent::Install($context);
        $connection = $this->container->get(Connection::class);

        $customFieldSetId = Uuid::randomBytes();
        $connection->insert('custom_field_set', [
            'id' => $customFieldSetId,
            'name' => 'ace360_category',
            'config' => '{"label": {"de-DE": "Kategorie symbole (50x50)px", "en-GB": "Category Icons (50x50)px"}, "translated": true}',
            'active' => 1,
            'created_at' => (new \DateTimeImmutable())->format(Defaults::STORAGE_DATE_TIME_FORMAT),
        ]);

        $customFieldRelationId = Uuid::randomBytes();
        $connection->insert('custom_field_set_relation', [
            'id' => $customFieldRelationId,
            'set_id' => $customFieldSetId,
            'entity_name' => 'category',
            'created_at' => (new \DateTimeImmutable())->format(Defaults::STORAGE_DATE_TIME_FORMAT),
        ]);
        //New Custom Field for Icon
        $customFieldId = Uuid::randomBytes();
        $connection->insert('custom_field', [
            'id' => $customFieldId,
            'name' => 'sports',
            'type' => 'media',
           /* 'config' => '{"label": {"de-DE": "Laden Sie das Symbolbild der kategorie hoch", "en-GB": "Upload Category icon (50x50)px"}, "customFieldPosition": 75}',*/
		   'config' => '{"label": {"de-DE": "Hochladen kategorie Bild(50x50)px", "en-GB": "Upload icon image(50x50)px"}, "helpText": {"en-GB": "Upload icon image(50x50)px"}, "validation": "required", "placeholder": {"de-DE": "Hochladen kategorie Bild(50x50)px", "en-GB": "Upload icon image(50x50)px"}, "componentName": "sw-media-field", "customFieldType": "media", "customFieldPosition": 85}',
            'active' => 1,
			
		    'set_id' => $customFieldSetId,
            'created_at' => (new \DateTimeImmutable())->format(Defaults::STORAGE_DATE_TIME_FORMAT),
        ]);

    }


    public function uninstall(UninstallContext $context): void
    {
        parent::uninstall($context);

        $connection = $this->container->get(Connection::class);
        $connection->executeQuery("UPDATE category_translation SET custom_fields = NULL WHERE custom_fields like '%ace360_category%'");
$connection->executeQuery("DELETE FROM system_config WHERE configuration_key like '%Ace360CategoryIcons%'");
        $connection->executeQuery("DELETE FROM custom_field_set WHERE name = 'ace360_category'");
		$connection->executeQuery("DELETE FROM custom_field WHERE name = 'sports'");

    }
	
}