<?php

namespace frontend\controllers;

use common\components\BookingHelper;
use common\models\Hotel;
use common\models\Room;
use DateTime;
use frontend\models\BookingParams;
use frontend\models\OrderForm;
use frontend\models\OrderItemForm;
use yii\filters\PageCache;
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
					['booking'] => ['post']
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

	public function actionBooking()
	{
		$bookingParams = new BookingParams();
		if (!$bookingParams->load(\Yii::$app->request->post()) || !$bookingParams->validate()) {
			throw new BadRequestHttpException('Wrong parameters passed');
		};

		$room = Room::findOne($bookingParams->roomId);
		/** @var Hotel $hotel */
		$hotel = $room->hotel;

		$orderForm = new OrderForm();
		if ($orderForm->load(\Yii::$app->request->post()) && $orderForm->validate()) {
			// сохраняем заказ
			return $this->render('booking_success',[
				[
					'room'          => $room,
					'hotel'         => $hotel,
					'bookingParams' => $bookingParams,
					'orderForm'     => $orderForm,
					'price'         => BookingHelper::calcRoomPrice($bookingParams->toArray()),
				]
			]);
		} else {
			$orderForm->dateFrom = $bookingParams->dateFrom;
			$orderForm->dateTo = $bookingParams->dateTo;
			$orderForm->items[] = new OrderItemForm([
				'room' => $room,
				'roomId' => $room->id,
				'name' => '',
				'surname' => '',
			]);
			return $this->render('booking', [
				'room'          => $room,
				'hotel'         => $hotel,
				'bookingParams' => $bookingParams,
				'orderForm'     => $orderForm,
				'price'         => BookingHelper::calcRoomPrice($bookingParams->toArray()),
			]);
		}
	}

	public function actionIndex($name)
	{
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

		$adults = (int) $req->get('adults', 1);
		$children = (int) $req->get('children', 0);
		$kids = (int) $req->get('kids', 0);

		$bookParams = new BookingParams([
			'dateFrom' => $dateFrom->format('Y-m-d'),
			'dateTo' => $dateTo->format('Y-m-d'),
			'adults' => in_array($adults, range(1, 10)) ? $adults : 1,
			'children' => in_array($children, range(1, 5)) ? $children : 0,
			'kids' => in_array($kids, range(1, 5)) ? $kids : 0,
		]);

		return $this->render('index', [
			'model' => $model,
			'bookParams' => $bookParams,
		]);
	}

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
