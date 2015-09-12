<?php

namespace frontend\controllers;

use common\components\BookingHelper;
use common\models\Hotel;
use common\models\Lang;
use common\models\Order;
use common\models\OrderItem;
use common\models\Room;
use DateTime;
use frontend\models\BookingParams;
use partner\models\PartnerUser;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\Response;
use \common\models\Currency;

class HotelController extends \yii\web\Controller
{
	public function behaviours()
	{
		return [
			'verb' => [
				'class'   => VerbFilter::className(),
				'actions' => [
					['booking', 'search', 'get-form', 'rooms'] => ['post'],
				]
			],
		];
	}

//	public function beforeAction($action) {
//		if ($action->id == 'booking') {
//			$this->enableCsrfValidation = false;
//		}
//		return parent::beforeAction($action);
//	}

    /**
     * При оформлении заказа и выборе полной оплаты на месте
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionBookingComplete($id) {
        $order = Order::findOne(['payment_url' => $id]);

        if ($order === null || $order->status !== Order::OS_CHECKIN_FULLPAY) {
            throw new NotFoundHttpException(\Yii::t('frontend', 'Page was not found!'));
        }

        return $this->render('booking-complete', [
            'order' => $order,
        ]);
    }

	/**
	 * Страница оформления заказа
	 * (переход со страницы поиска)
	 *
	 * @return string
	 * @throws BadRequestHttpException
	 * @throws \Exception
	 */
	public function actionBooking()
	{
        /** @var Order $orderForm */
        $orderForm = new Order();
		if ($orderForm->load(\Yii::$app->request->post())) {

			$orderItem = new OrderItem();
			$orderItem->load(\Yii::$app->request->post('items')[0]);

			/** @var Hotel $hotel */
			$hotel = Hotel::findOne($orderForm->hotel_id);

			if (!$hotel) {
				throw new BadRequestHttpException('Wrong hotel id');
			}
        	if (!$hotel->published()) {
        		throw new \yii\web\HttpException(404, 'The requested hotel is not published.');
        	}

			$orderForm->status = 1;
			$orderForm->sum = BookingHelper::calcRoomPrice([
				'adults'   => (int)$orderItem->adults,
				'children' => (int)$orderItem->children,
				'kids'     => (int)$orderItem->kids,
				'roomId'   => (int)$orderItem->room_id,
				'dateFrom' => $orderForm->dateFrom,
				'dateTo'   => $orderForm->dateTo,
                'code'     => $orderForm->code,
			]);

			$orderForm->number = Order::generateNumber($orderForm->contact_email);

			$orderForm->lang = Lang::$current->url;

			// Учитываем все деньги
			$orderForm->sum_currency_id = $hotel->currency_id;
			if ($orderForm->partial_pay && $hotel->allow_partial_pay) {
				$orderForm->partial_pay_percent = $hotel->partial_pay_percent;
				$orderForm->pay_sum = (float)(round($orderForm->sum * ($orderForm->partial_pay_percent / 100) * 100) / 100);
			} else {
				$orderForm->pay_sum = $orderForm->sum;
				$orderForm->partial_pay_percent = 100;
			}
			$orderForm->pay_sum_currency_id = $hotel->currency_id;
			// Если платеж через Яндекс.Кассу записываем сумму в рублях
			// TODO: Надо учитывать, чей отель и соответственно, в какой валюте оплата
			$orderForm->payment_system_sum = $orderForm->hotel->currency->convertTo($orderForm->pay_sum, 'RUB', $orderForm->hotel->partner->currency_exchange_percent);
			$orderForm->payment_system_sum_currency_id = Currency::findOne(['code' => 'RUB'])->id;

			if ($orderForm->save()) {
				$orderItem->order_id = $orderForm->id;
				//Устанавливаем все суммы на конкретный номер (сейчас как у заказа, потом ПЕРЕПИСАТЬ)
				$orderItem->sum = $orderForm->sum;
				$orderItem->sum_currency_id = $orderForm->sum_currency_id;
				$orderItem->pay_sum = $orderForm->pay_sum;
				$orderItem->pay_sum_currency_id = $orderForm->pay_sum_currency_id;
				$orderItem->payment_system_sum = $orderForm->payment_system_sum;
				$orderItem->payment_system_sum_currency_id = $orderForm->payment_system_sum_currency_id;
				// Сохраняем OrderItem
				if ($orderItem->save()) {
					// посылаем письма только после добавления номера, иначе его в письмах не будет
					$orderForm->sendEmailToClient();
					$orderForm->sendEmailToPartner();

                    if ($orderForm->checkin_fullpay == 1 && $orderForm->hotel->partner->allow_checkin_fullpay) {
                        $orderForm->status = Order::OS_CHECKIN_FULLPAY;
                        $orderForm->save(false);
                        return $this->redirect(['hotel/booking-complete', 'id' => $orderForm->payment_url]);
                    }
					return $this->redirect(['payment/show', 'id' => $orderForm->payment_url]);
				}
			}

			throw new \yii\web\BadRequestHttpException('Validation Error');
			/*return $this->render('booking_error', [
				'orderForm' => $orderForm,
				'orderItem' => $orderItem,
				'embedded'   => \Yii::$app->request->get('embedded', 0),
				'no_desc'   => \Yii::$app->request->get('no_desc', 0),
			]);*/
		} else {
			$bookingParams = new BookingParams();

			if (!$bookingParams->load(\Yii::$app->request->post()) || !$bookingParams->validate()) {
				throw new BadRequestHttpException('Wrong parameters passed');
			};

			$room = Room::findOne($bookingParams->roomId);
			/** @var Hotel $hotel */
			$hotel = Hotel::findOne($bookingParams->hotelId);

            if (!$this->checkBookingPossibility($hotel)) {
                throw new ForbiddenHttpException(\Yii::t('frontend', 'Booking is not availible!'));
            }

            $orderForm->dateFrom = $bookingParams->dateFrom;
			$orderForm->dateTo = $bookingParams->dateTo;
			$orderForm->sum = BookingHelper::calcRoomPrice($bookingParams->toArray());
			$orderForm->partial_pay_percent = $hotel->partial_pay_percent;
			$orderForm->partial_pay = false; // по умолчанию полная оплата
			$orderForm->hotel_id = $bookingParams->hotelId;
            $orderForm->code = '';

			$items = [];
			$items[] = new OrderItem([
//				'room' => $room,
				'room_id'       => $room->id,
				'sum'           => BookingHelper::calcRoomPrice($bookingParams),
				'adults'        => $bookingParams->adults,
				'children'      => $bookingParams->children,
				'kids'          => $bookingParams->kids,
				'guest_name'    => '',
				'guest_surname' => '',
				'guest_address' => '',
			]);

			return $this->render('booking', [
				'room'          => $room,
				'hotel'         => $hotel,
				'bookingParams' => $bookingParams,
				'orderForm'     => $orderForm,
				'items'         => $items,
				'price'         => BookingHelper::calcRoomPrice($bookingParams->toArray()),
				'embedded'   => \Yii::$app->request->get('embedded', 0),
				'no_desc'   => \Yii::$app->request->get('no_desc', 0),
			]);
		}
	}

    public function checkBookingPossibility($hotel)
    {
        /** @var PartnerUser $partner */
        $partner = $hotel->partner;
        if ($partner)
            return !((trim($partner->shopPassword) == ''
                || trim($partner->shopId) == ''
                || trim($partner->scid) == '') && (!$partner->allow_checkin_fullpay && !$partner->allow_payment_via_bank_transfer));
    }

	/**
	 * Страница поиска, куда клиенты приходят с виджета
	 *
	 * @param $name
	 * @return string
	 * @throws \yii\web\HttpException
	 */
	public function actionIndex($name = false)
	{
        $model = Hotel::findOne(['name' => $name]);

        if (!$this->checkBookingPossibility($model)) {
            //\Yii::$app->session->setFlash('danger', \Yii::t('frontend', 'Booking is not availible!'));
        }

		if (is_null($model)) {
			throw new \yii\web\HttpException(404, 'The requested hotel does not exist.');
		}
        if (!$model->published()) {
        	throw new \yii\web\HttpException(404, 'The requested hotel is not published.');
        }

		$req = \Yii::$app->request;
		$dateFrom = $req->get('dateFrom', false);
		$dateTo = $req->get('dateTo', false);

		if ($dateFrom && $dateTo) {
			$dateFrom = new DateTime($dateFrom);
			$dateTo = new DateTime($dateTo);
			$search = true;
		} else {
			$dateFrom = new DateTime();
			$dateTo = clone $dateFrom;
			$dateTo->add(new \DateInterval('P7D'));
			$search = false;
		}

		$now = new DateTime();

		// проверяем, если переданная дата_с меньше сегодняшней, то устанавливаем на сегодня
		if ($now->diff($dateFrom)->invert) {
			$dateFrom = $now;
		}

		// проверяем дата_по
		if ($dateFrom->diff($dateTo)->invert) {
			$dateTo = clone $dateFrom;
			$dateTo->add(new \DateInterval('P7D'));
		}

		$adults = (int)$req->get('adults', 1);
		$children = (int)$req->get('children', 0);
		$kids = (int)$req->get('kids', 0);

		$bookParams = new BookingParams([
			'dateFrom' => $dateFrom->format('Y-m-d'),
			'dateTo'   => $dateTo->format('Y-m-d'),
			'adults'   => in_array($adults, range(1, 10)) ? $adults : 1,
			'children' => in_array($children, range(1, 5)) ? $children : 0,
			'kids'     => in_array($kids, range(1, 5)) ? $kids : 0,
		]);

		return $this->render('index', [
			'model'      => $model,
			'bookParams' => $bookParams,
			'embedded'   => \Yii::$app->request->get('embedded', 0),
			'no_desc'   => \Yii::$app->request->get('no_desc', 0),
			'search' => $search ? 'true' : 'false',
		]);
	}

	/**
	 * Поиск и выдача подходящих номеров
	 *
	 * @return array
	 * @throws BadRequestHttpException
	 */
	public function actionSearch()
	{
		// вывод в формате JSON
		\Yii::$app->response->format = Response::FORMAT_JSON;
		$req = \Yii::$app->request;

		// разбираем входные данные
		$dateFrom = $req->post('dateFrom', false);
		$dateTo = $req->post('dateTo', false);
		$adults = (int)$req->post('adults', false);
		$children = (int)$req->post('children', false);
		$kids = (int)$req->post('kids', false);
		$hotelId = (int)$req->post('hotelId', false);

		if (!$dateFrom || !$dateTo || $adults === false || $children === false || $kids === false) {
			throw new BadRequestHttpException();
		}

		$hotel = Hotel::findOne($hotelId);

		if ($hotel === null) {
			throw new BadRequestHttpException();
		}

		$rooms = Room::findAll(['hotel_id' => $hotelId]);
		$result = [];
		foreach ($rooms as $room) {
			if (!$room->published()) {
				continue;
			}
			$price = null;
			try {
				$price = BookingHelper::calcRoomPrice([
					'dateFrom' => $dateFrom,
					'roomId'   => $room->id,
					'dateTo'   => $dateTo,
					'adults'   => $adults,
					'children' => $children,
					'kids'     => $kids,
					'hotelId'  => $hotelId,
				]);
			} catch (\Exception $e) {
				continue;
			}
			if ($price === null) continue;

			$item = $room->toArray([], ['facilities']);
			$item['images'] = [];
			foreach ($room->images as $image) {
				$im = [];
				$im['image'] = $image->getUploadUrl('image');
				$im['thumb'] = $image->getThumbUploadUrl('image', 'thumb');
				$im['preview'] = $image->getThumbUploadUrl('image', 'preview');
				$item['images'][] = $im;
			}
			$item['price'] = $price;
			$item['sum_currency'] = $hotel->currency;

			$result[] = $item;
		}


		return $result;

	}

	public function actionRooms()
	{
		// вывод в формате JSON
		\Yii::$app->response->format = Response::FORMAT_JSON;
		$req = \Yii::$app->request;

		// разбираем входные данные
		$hotelId = (int)$req->post('hotelId', false);

		$hotel = Hotel::findOne($hotelId);

		if ($hotel === null) {
			throw new BadRequestHttpException();
		}

		$rooms = Room::findAll(['hotel_id' => $hotelId]);
		$result = [];
		foreach ($rooms as $room) {
			if (!$room->published()) {
				continue;
			}
			$item = $room->toArray([], ['facilities']);
			$item['images'] = [];
			foreach ($room->images as $image) {
				$im = [];
				$im['image'] = $image->getUploadUrl('image');
				$im['thumb'] = $image->getThumbUploadUrl('image', 'thumb');
				$im['preview'] = $image->getThumbUploadUrl('image', 'preview');
				$item['images'][] = $im;
			}
			$item['price'] = '';
			$item['sum_currency'] = '';

			$result[] = $item;
		}


		return $result;

	}

}
