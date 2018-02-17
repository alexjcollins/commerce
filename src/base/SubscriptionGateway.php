<?php

namespace craft\commerce\base;

use craft\commerce\elements\Subscription;
use craft\commerce\errors\NotImplementedException;
use craft\commerce\models\subscriptions\CancelSubscriptionForm;
use craft\commerce\models\subscriptions\SubscriptionForm;
use craft\commerce\models\subscriptions\SwitchPlansForm;

/**
 * Class Subscription Gateway
 *
 * @author Pixel & Tonic, Inc. <support@pixelandtonic.com>
 * @since 2.0
 */
abstract class SubscriptionGateway extends Gateway implements SubscriptionGatewayInterface
{
    /**
     * Get the cancel subscription form model
     *
     * @return CancelSubscriptionForm
     */
    abstract public function getCancelSubscriptionFormHtml(): string;

    /**
     * Get the cancel subscription form model
     *
     * @return CancelSubscriptionForm
     */
    abstract public function getCancelSubscriptionFormModel(): CancelSubscriptionForm;

    /**
     * Subscription plan settings HTML
     *
     * @param array $params
     * @return string|null
     */
    abstract public function getPlanSettingsHtml(array $params = []);

    /**
     * Get the subscription plan model.
     *
     * @return Plan
     */
    abstract public function getPlanModel(): Plan;

    /**
     * Get the subscription form html to use when subscribing to a plan.
     *
     * @return string
     */
    public function getSubscriptionFormHtml(): string
    {
        return '';
    }

    /**
     * Get the subscription form model
     *
     * @return SubscriptionForm
     */
    abstract public function getSubscriptionFormModel(): SubscriptionForm;

    /**
     * Return the html to use when switching between two plans
     *
     * @param PlanInterface $originalPlan
     * @param PlanInterface $targetPlan
     * @return string
     */
    public function getSwitchPlansFormHtml(PlanInterface $originalPlan, PlanInterface $targetPlan): string
    {
        return '';
    }

    /**
     * Get the form model used for switching plans.
     *
     * @return SwitchPlansForm
     */
    abstract public function getSwitchPlansFormModel(): SwitchPlansForm;

    /**
     * @inheritdoc
     */
    public function reactivateSubscription(Subscription $subscription): SubscriptionResponseInterface
    {
        throw new NotImplementedException('This gateway has not implemented subscription reactivation');
    }


}