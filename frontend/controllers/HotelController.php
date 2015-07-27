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
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\Response;


class HotelController extends \yii\web\Controller
{
	public function behaviours()
	{
		return [
			'verb' => [
				'class'   => VerbFilter::className(),
				'actions' => [
					['booking', 'search', 'get-form'] => ['post'],
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
	 * Страница оформления заказа
	 * (переход со страницы поиска)
	 *
	 * @return string
	 * @throws BadRequestHttpException
	 * @throws \Exception
	 */
	public function actionBooking()
	{
		$orderForm = new Order();
		if ($orderForm->load(\Yii::$app->request->post())) {

			$orderItem = new OrderItem();
			$orderItem->load(\Yii::$app->request->post('items')[0]);

			/** @var Hotel $hotel */
			$hotel = Hotel::findOne($orderForm->hotel_id);

			if (!$hotel) {
				throw new BadRequestHttpException('Wrong hotel id');
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

			$orderForm->number = md5(\Yii::$app->getSecurity()->generateRandomString(15) . $orderForm->contact_email);

			$orderForm->lang = Lang::$current->url;

			if ($orderForm->partial_pay && $hotel->allow_partial_pay) {
				$orderForm->partial_pay_percent = $hotel->partial_pay_percent;
				$orderForm->pay_sum = (float)(round($orderForm->sum * (1 - $orderForm->partial_pay_percent / 100) * 100) / 100);
			} else {
				$orderForm->pay_sum = $orderForm->sum;
				$orderForm->partial_pay_percent = 100;
			}

			if ($orderForm->save()) {
				$orderItem->order_id = $orderForm->id;
				$orderItem->sum = $orderForm->sum;
				if ($orderItem->save()) {
					return $this->redirect(['payment/show', 'id' => $orderForm->payment_url]);
				}
			}

			return $this->render('booking_error', [
				'orderForm' => $orderForm,
				'orderItem' => $orderItem,
				'embedded'   => \Yii::$app->request->get('embedded', 0),
				'no_desc'   => \Yii::$app->request->get('no_desc', 0),
			]);
		} else {
			$bookingParams = new BookingParams();

			if (!$bookingParams->load(\Yii::$app->request->post()) || !$bookingParams->validate()) {
				throw new BadRequestHttpException('Wrong parameters passed');
			};

			$room = Room::findOne($bookingParams->roomId);
			/** @var Hotel $hotel */
			$hotel = Hotel::findOne($bookingParams->hotelId);


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

	/**
	 * Страница поиска, куда клиенты приходят с виджета
	 *
	 * @param $name
	 * @return string
	 * @throws \yii\web\HttpException
	 */
	public function actionIndex($name = false)
	{
        if ($name === false && \Yii::$app->request->hostInfo == 'http://abc.itdesign.ru/') {
            $name = 'loceanica';
        }
        
        if ($name === false && \Yii::$app->request->hostInfo == 'http://abc2.itdesign.ru/') {
            $name = 'loceanica';
        }
	
		$model = Hotel::findOne(['name' => $name]);

		if (is_null($model)) {
			throw new \yii\web\HttpException(404, 'The requested hotel does not exist.');
		}
		$req = \Yii::$app->request;
		$dateFrom = new DateTime($req->get('dateFrom', date('Y-m-d')));
		$dateTo = new DateTime($req->get('dateTo', date('Y-m-d')));

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
}
