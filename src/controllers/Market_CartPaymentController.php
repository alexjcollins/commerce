<?php
namespace Craft;

/**
 * Cart. Step "Payment".
 *
 * Class Market_CartPaymentController
 *
 * @package Craft
 */
class Market_CartPaymentController extends Market_BaseController
{
	protected $allowAnonymous = true;

	/**
	 * @throws HttpException
	 */
	public function actionPay()
	{
		$this->requirePostRequest();

		$paymentForm             = new Market_PaymentFormModel;
		$paymentForm->attributes = $_POST;

		//in case of success "pay" redirects us somewhere
		if (!craft()->market_payment->processPayment($paymentForm, $customError)) {
			craft()->urlManager->setRouteVariables(compact('paymentForm', 'customError'));
		}
	}

//	public function actionCancel()
//	{
//		$this->actionGoToComplete();
//		$this->redirect('market/cart');
//	}

	/**
	 * @throws Exception
	 */
	public function actionGoToComplete()
	{
		$order = craft()->market_cart->getCart();

		if ($order->canTransit(Market_OrderRecord::STATE_COMPLETE)) {
			$order->completedAt = DateTimeHelper::currentTimeForDb();
			craft()->market_order->save($order);
			$order->transition(Market_OrderRecord::STATE_COMPLETE);
		} else {
			throw new Exception('unable to go to payment state from the state: ' . $order->state);
		}
	}

//	public function actionSuccess()
//	{
//		$this->actionGoToComplete();
//		$this->redirect('market/cart');
//	}
}