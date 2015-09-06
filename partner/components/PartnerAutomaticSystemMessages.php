<?php
namespace partner\components;
 
use \Yii;
use yii\base\Component;
use partner\models\PartnerUser;
 
class PartnerAutomaticSystemMessages extends \common\components\AutomaticSystemMessages {
 
 	/**
 	 * Работа с системными сообщениями
 	 */

 	// Объект партнера
 	private $partner;

 	// Массив, в который складываются Id отелей, по которым уже выведено сообщение из очереди (то есть следующие сообщения для этих отелей не выводятся)
 	private $stopHotels = [];
 	// Массив, в который складываются Id номеров, по которым уже выведено сообщение из очереди (то есть следующие сообщения для этих номеров не выводятся)
 	private $stopRooms = [];

 	const SESSION_SHOWN_MESSAGES = 'systemMessagesShownAlert';
 	const SESSION_ALERT_FLASH_PREFIX = 'systemMessages-';

 	public function __construct() {
 		parent::__construct();
 		$this->partner = PartnerUser::findOne(\Yii::$app->user->id);
 	}

     public function init() {
        parent::init();
    }

    public function resetMessages() {
    	// перезагружаем данные партнера, чтобы учесть измеения в нем
    	$this->partner = PartnerUser::findOne(\Yii::$app->user->id);
    	parent::resetMessages();
    	$this->partner->system_info = serialize([
            'messages' => $this->actualMessages,
            'messages_update_time' => time(), // Время апдейта сообщений
        ]);
    	$res = $this->partner->update(false, ['system_info']);
    }

    public function prepareMessages($event) {
        if (\Yii::$app->request->isAjax || \Yii::$app->request->isPut) { //TODO: нихрена это не работает (при любом аяксе это преодолевается)
            return;
        }
        if (\Yii::$app->user->isGuest) {
            return;
        }

    	if (!$this->partner->system_info) { // Если поле system_info пустое, запускаем цикл проверки
    		$this->resetMessages();
    	} else { // Если поле system_info есть, берем сообщения оттуда
    		$si = unserialize($this->partner->system_info);
    		$this->actualMessages = $si['messages'];
            if (isset($si['messages_update_time']) && $si['messages_update_time']) {
                if (time() - $si['messages_update_time'] > 86400 * 3) { // Если апдейт был больше 3-х дней назад - обновляем сообщения
                    $this->resetMessages();
                }
            } else {
                $this->resetMessages();
            }
    	}

    	// Сообщения для виджета Alert
    	// Получаем список уже показанных сообщений
    	$shownMessages = \Yii::$app->session->get(self::SESSION_SHOWN_MESSAGES, []);//$shownMessages = [];print_r($shownMessages);
    	$a = [];
        foreach ($this->actualMessages as $k => $message) {
        	if (!in_array($k, $shownMessages)) { // Если сообщение не было показано, добавляем его к показу и в список показанных
        		$a[$message['type']][] = "<b>{$message['title']}</b><br />{$message['text']}";
        		\Yii::$app->session->set(self::SESSION_SHOWN_MESSAGES, array_keys($this->actualMessages));
        	}
        }
        if ($a) {
        	foreach ($a as $k=>$v) {
            	\Yii::$app->session->setFlash($k, $v);
        	}
        }

    }

 	public function messages() {
 		return [
 			// Серия действий необходмых для начала работы
 			'beginWork' => [
 				'noHotels' => [
 					'type' => self::TYPE_DANGER,
 					'condition' => 'condHotelNotCreated',
 					'title' => \Yii::t('automatic_system_messages', 'No registered hotels'),
 					'text' => \Yii::t('automatic_system_messages', 'Please, <a href="{link}">register a hotel</a> to continue.', ['link'=>\yii\helpers\Url::toRoute('/hotel/create')]),
 				],
 				'noRooms' => [
 					'type' => self::TYPE_DANGER,
 					'condition' => 'condRoomNotCreated',
 					'title' => \Yii::t('automatic_system_messages', 'Hotel registration is not complete'),
 					'text' => 'No rooms registered yet in the following hotels: {link}. Follow the links to add.',
 				],
 				'noHotelPhotos' => [
 					'type' => self::TYPE_DANGER,
 					'condition' => 'condNoHotelPhotos',
 					'title' => \Yii::t('automatic_system_messages', 'Hotel registration is not complete'),
 					'text' => 'No photos uploded for the following hotels: {link}. Follow the links to upload.',
 				],
 				'noRoomPhotos' => [
 					'type' => self::TYPE_DANGER,
 					'condition' => 'condNoRoomPhotos',
 					'title' => \Yii::t('automatic_system_messages', 'Room registration is not complete'),
 					'text' => 'No photos uploded for the following rooms: {link}. Follow the links to upload.',
 				],
 				'noPayMethods' => [
 					'type' => self::TYPE_WARNING,
 					'condition' => 'condNoPayMethods',
 					'title' => \Yii::t('automatic_system_messages', 'Booking is not available'),
 					'text' => \Yii::t('automatic_system_messages', '<a href="{link}">Select the avaliable payment options</a> to activate on-site booking.', ['link'=>\yii\helpers\Url::toRoute('/profile')]),
 				],
 				'noPrices' => [
 					'type' => self::TYPE_WARNING,
 					'condition' => 'condNoPrices',
 					'title' => \Yii::t('automatic_system_messages', 'Booking is limited'),
 					'text' => 'Not all prices are specified for the next 30 days for the following rooms: {link}. Follow the links to set prices.',
 				],
 				'noYandexKassa' => [
 					'type' => self::TYPE_WARNING,
 					'condition' => 'condNoYandexKassa',
 					'title' => \Yii::t('automatic_system_messages', 'Booking is limited'),
 					'text' => \Yii::t('automatic_system_messages', '<a href="{link}">Setup Yandex.Kassa</a> to activate online booking.', ['link'=>\yii\helpers\Url::toRoute('/profile')]),
 				],
 			],
 		];
 	}

 	/**
 	 * Нет отелей
 	 */
 	public function condHotelNotCreated($message) {
 		if ($this->partner->hotels) {
 			return false;
 		}
 		return $message;
 	}

 	/**
 	 * Нет номеров
 	 */
 	public function condRoomNotCreated($message) {
 		if (!isset($this->partner->hotels)) {
 			return false;
 		}
 		$title = 'title_' . \common\models\Lang::$current->url;
 		$links = array();
 		foreach ($this->partner->hotels as $hotel) {
 			if ($hotel->rooms) {
 				continue;
 			} else {
 				$links[] = '<a href="' . \yii\helpers\Url::toRoute(['/hotel/rooms', 'id' => $hotel->id, '#' => '/add']) . '">' . $hotel->$title . '</a>';
 				$this->stopHotels[] = $hotel->id;
 			}
 		}
 		if (!$links) {
 			return false;
 		}
 		$message['text'] = \Yii::t('automatic_system_messages', $message['text'], ['link' => implode(', ', $links)]);
 		return $message;
 	}

 	/**
 	 * Нет фотографий отеля
 	 */
 	public function condNoHotelPhotos($message) {
 		if (!isset($this->partner->hotels)) {
 			return false;
 		}
 		$title = 'title_' . \common\models\Lang::$current->url;
 		$links = array();
 		foreach ($this->partner->hotels as $hotel) {
 			if (in_array($hotel->id, $this->stopHotels)) {
 				continue;
 			}
 			if ($hotel->images) {
 				continue;
 			} else {
 				$links[] = '<a href="' . \yii\helpers\Url::toRoute(['/hotel/images', 'id' => $hotel->id, '#' => '/']) . '">' . $hotel->$title . '</a>';
 				$this->stopHotels[] = $hotel->id;
 			}
 		}
 		if (!$links) {
 			return false;
 		}
 		$message['text'] = \Yii::t('automatic_system_messages', $message['text'], ['link' => implode(', ', $links)]);
 		return $message;
 	}
 
 	/**
 	 * Нет фотографий номеров
 	 */
 	public function condNoRoomPhotos($message) {
 		if (!isset($this->partner->hotels)) {
 			return false;
 		}
 		$title = 'title_' . \common\models\Lang::$current->url;
 		$links = array();
 		foreach ($this->partner->hotels as $hotel) {
 			if (in_array($hotel->id, $this->stopHotels)) {
 				continue;
 			}
 			if (!$hotel->rooms) {
 				continue;
 			} else {
 				foreach ($hotel->rooms as $room) {
 					if (in_array($room->id, $this->stopRooms)) {
 						continue;
 					}
 					if ($room->images) {
 						continue;
 					} else {
 						$links[] = '<a href="' . \yii\helpers\Url::toRoute(['/hotel/rooms', 'id' => $hotel->id, '#' => "/images/{$room->id}"]) . '">' . $room->$title . '</a>';
 						$this->stopRooms[] = $room->id;
 					}
 				}
 			}
 		}
 		if (!$links) {
 			return false;
 		}
 		$message['text'] = \Yii::t('automatic_system_messages', $message['text'], ['link' => implode(', ', $links)]);
 		return $message;
 	}

 	public function condNoPayMethods($message) {
 		$ready = false;
 		foreach ($this->partner->hotels as $hotel) {
 			if (!in_array($hotel->id, $this->stopHotels)) {
 				$ready = true;
 				break;
 			}
 		}
 		if (!$ready) {
 			return false;
 		}
 		if ($this->partner->allow_checkin_fullpay 
 			|| $this->partner->allow_payment_via_bank_transfer 
 			|| $this->partner->shopId) {
 			return false;
 		} else {
 			// Если нет способов оплаты, все отлели в стоп-лист
 			foreach ($this->partner->hotels as $hotel) {
 				$this->stopHotels[] = $hotel->id;
 			}
 			return $message;
 		}
 	}

 	public function condNoPrices($message) {
 		if (!isset($this->partner->hotels)) {
 			return false;
 		}
 		$title = 'title_' . \common\models\Lang::$current->url;
 		$links = [];
 		foreach ($this->partner->hotels as $hotel) {
 			if (in_array($hotel->id, $this->stopHotels)) {
 				continue;
 			}
 			if (!$hotel->rooms) {
 				continue;
 			} else {
 				foreach ($hotel->rooms as $room) {
 					if (in_array($room->id, $this->stopRooms)) {
 						continue;
 					}
 					$today = new \DateTime();
 					$month = clone $today;
 					$month->add(date_interval_create_from_date_string('30 days'));
 					$rate = \common\components\BookingHelper::priceSetStatistic([
 						'roomId' => $room->id, 
 						'beginDate' => $today->format('Y-m-d'), 
 						'endDate' => $month->format('Y-m-d'),
 					]);
 					if ($rate < 1) {
 						$links[] = '<a href="' . \yii\helpers\Url::toRoute(['/hotel/rooms', 'id' => $hotel->id, '#' => "/availability/{$room->id}"]) . '">' . $room->$title . '</a>';
 						$this->stopRooms[] = $room->id;
 					} else {
 						continue;
 					}
 				}
 			}
 		}
 		if (!$links) {
 			return false;
 		}
 		$message['text'] = \Yii::t('automatic_system_messages', $message['text'], ['link' => implode(', ', $links)]);
 		return $message;
 	}

 	public function condNoYandexKassa($message) {
 		$ready = false;
 		foreach ($this->partner->hotels as $hotel) {
 			if (!in_array($hotel->id, $this->stopHotels)) {
 				$ready = true;
 				break;
 			}
 		}
 		if (!$ready) {
 			return false;
 		}
 		if ($this->partner->shopId 
 			&& $this->partner->scid 
 			&& $this->partner->shopPassword) {
 			return false;
 		} else {
 			return $message;
 		}
 	}

}