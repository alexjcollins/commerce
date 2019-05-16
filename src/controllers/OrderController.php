<?php
/**
 * @link https://craftcms.com/
 * @copyright Copyright (c) Pixel & Tonic, Inc.
 * @license https://craftcms.github.io/license/
 */

namespace craft\commerce\controllers;

use Craft;
use craft\base\Field;
use craft\commerce\elements\Order;
use craft\commerce\Plugin;
use craft\errors\ElementNotFoundException;
use craft\helpers\ArrayHelper;
use craft\web\Controller;
use Throwable;
use yii\base\Exception;
use yii\web\Response;

/**
 * Class Order Editor Controller
 *
 * @author Pixel & Tonic, Inc. <support@pixelandtonic.com>
 * @since 2.0
 */
class OrderController extends Controller
{
    public $enableCsrfValidation = false;
    public $allowAnonymous = true;

    // Public Methods
    // =========================================================================

    public function actionGet($orderId = null)
    {

        $order = null;

        if ($orderId) {
            $order = Order::find()->id($orderId)->one();
        }

        if (!$order) {
            $order = new Order([
                'number' => Plugin::getInstance()->getCarts()->generateCartNumber()
            ]);

            Craft::$app->getElements()->saveElement($order);
        }


        $data = [];

        // Add meta data
        $data['meta'] = [];
        $data['meta']['edition'] = Plugin::getInstance()->is(Plugin::EDITION_LITE) ? Plugin::EDITION_LITE : Plugin::EDITION_PRO;

        // Remove custom fields
        $orderAttributes = $order->attributes();
        if ($order::hasContent() && ($fieldLayout = $order->getFieldLayout()) !== null) {
            foreach ($fieldLayout->getFields() as $field) {
                /** @var Field $field */
                ArrayHelper::removeValue($orderAttributes, $field->handle);
            }
        }

        // Remove unneeded fields
        ArrayHelper::removeValue($orderAttributes, 'hasDescendants');

        $extraFields = [
            'billingAddress', 'shippingAddress'
        ];
        $data['order'] = $order->toArray($orderAttributes, $extraFields);

        $orderStatuses = Plugin::getInstance()->getOrderStatuses()->getAllOrderStatuses();
        $data['orderStatuses'] = ArrayHelper::toArray($orderStatuses);

        return $this->asJson($data);
    }

    /**
     * @return Response
     * @throws Throwable
     * @throws ElementNotFoundException
     * @throws Exception
     */
    public function actionSave()
    {

    }
}
