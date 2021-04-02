<?php
namespace Kuechenpate\Brands\Setup;
use Magento\Eav\Setup\EavSetup;

/*
What's happening here is that we are extending from the
\Magento\Eav\Setup\EavSetup class, thus effectively telling Magento
we are about to create our own entity.
We do so by overriding getDefaultEntities.

The getDefaultEntities method returns an array of entities we want to register with Magento.
Within our $entities array, the key $employeeEntity becomes an entry in the eav_entity_type table.

Only a handful of metadata values are required to make our new entity type functional. The entity_model
value should point to our EAV model resourcee class, not the model class.
The table value should equal the name of our EAV entity table in the database.
the attributes array should list any attribute we want crated on this entity.
Attributes and their metadata get created in the eav_attribute table.

What creates attributes and a new entity type is the array we just defined under the getDefaultEntities method.
*/
class BrandSetup extends EavSetup {

    public function getDefaultEntities() {

        $brandEntity = \Kuechenpate\Brands\Model\Brand::ENTITY;

        $entities = [
            $brandEntity => [
                'entity_model' => 'Kuechenpate\Brands\Model\ResourceModel\Brand', //the full resource model class name
                'table' => $brandEntity . '_entity',
                'attributes' => [
                    'manufacturer_id' => [
                        'type' => 'static',
                    ],
                    'name' => [
                        'type' => 'static',
                    ],
                    'created_at' => [
                        'type' => 'static',
                    ],
                    'updated_at' => [
                        'type' => 'static',
                    ],
                ],
            ],
        ];

        return $entities;
    }
}
