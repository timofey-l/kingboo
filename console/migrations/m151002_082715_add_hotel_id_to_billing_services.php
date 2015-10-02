<?php

use common\models\BillingAccount;
use common\models\BillingAccountServices;
use common\models\BillingService;
use common\models\Hotel;
use partner\models\PartnerUser;
use yii\db\Schema;
use yii\db\Migration;

class m151002_082715_add_hotel_id_to_billing_services extends Migration
{
    public function up()
    {
        $this->addColumn(BillingAccountServices::tableName(), 'hotel_id', Schema::TYPE_INTEGER);

        // Проверка и обновление уже созданных услуг
        $defaultService = BillingService::findOne(['default' => true]);
        foreach (PartnerUser::find()->all() as $partner) {
            /** @var PartnerUser $partner */

            $services = $partner->billing->services;

            if (count($services) != 0) {
                $service = $partner->billing->services[0];

                // цикл по отелям
                foreach ($partner->hotels as $i => $hotel) {
                    /** @var Hotel $hotel */
                    if ($i == 0) {
                        $service->hotel_id = $hotel->id;
                        $service->save();
                    } else {
                        /** @var BillingAccountServices $newService */
                        $newService = new BillingAccountServices();
                        $newService->account_id = $partner->billing->id;
                        $newService->service_id = $defaultService->id;
                        $newService->hotel_id = $hotel->id;
                        $newService->add_date = date(\DateTime::ISO8601);
                        $newService->end_date = date(\DateTime::ISO8601);
                        $newService->active = true;
                        $newService->save();
                    }
                }
            }

        }

    }

    public function down()
    {
        $this->dropColumn(BillingAccountServices::tableName(), 'hotel_id');
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
