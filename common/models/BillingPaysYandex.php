<?php

namespace common\models;

use Yii;
use common\models\Currency;

/**
 * This is the model class for table "{{%billing_pays_yandex}}".
 *
 * @property integer $id
 * @property integer $invoiceId
 * @property boolean $payed
 * @property boolean $checked
 * @property integer $billing_invoice_id
 * @property string $check_post_dump
 * @property string $avisio_post_dump
 */
class BillingPaysYandex extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%billing_pays_yandex}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['invoiceId', 'billing_invoice_id'], 'required'],
            [['invoiceId', 'billing_invoice_id'], 'integer'],
            [['payed', 'checked'], 'boolean'],
            [['check_post_dump', 'avisio_post_dump'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('billing_pays_yandex', 'ID'),
            'invoiceId' => Yii::t('billing_pays_yandex', 'Invoice ID'),
            'payed' => Yii::t('billing_pays_yandex', 'Payed'),
            'checked' => Yii::t('billing_pays_yandex', 'Checked'),
            'billing_invoice_id' => Yii::t('billing_pays_yandex', 'Billing Invoice ID'),
            'check_post_dump' => Yii::t('billing_pays_yandex', 'Check Post Dump'),
            'avisio_post_dump' => Yii::t('billing_pays_yandex', 'Avisio Post Dump'),
        ];
    }

    public function getInvoice()
    {
        return $this->hasOne(BillingInvoice::className(), ['id' => 'billing_invoice_id']);
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        // если payed стало true - добавляем запись в billing_income
        if ($this->payed == true) {
            /** @var BillingInvoice $invoice */
            $invoice = $this->invoice;
            $invoice->payed = true;
            if (!$invoice->save(false, ['payed'])) {
                MailerHelper::adminEmail(
                    'Billing aviso save error',
                    "Invoice errors\n" . print_r($invoice->errors, true)
                );
                $this->payed = false;
                $this->save(false, ['payed']);
                return;
            }

            $income = new BillingIncome();
            $income->sum = Currency::numberFormat($invoice->sum);
            $income->date = date('Y-m-d H:i:s');
            $income->currency_id = $invoice->currency_id;
            $income->account_id = $invoice->account_id;
            $income->invoice_id = $invoice->id;
            $income->pay_id = $this->id;
            if (!$income->save()) {
                MailerHelper::adminEmail(
                    'Billing aviso save error',
                    "Income errors\n" . print_r($income->errors, true)
                );
                $this->payed = false;
                $this->save(false, ['payed']);
            }

            // Сигнал для системы сообщений
            $automaticSystemMessages = new \partner\components\PartnerAutomaticSystemMessages();
            $automaticSystemMessages->resetMessages($income->account->partner);

        }
    }
}
