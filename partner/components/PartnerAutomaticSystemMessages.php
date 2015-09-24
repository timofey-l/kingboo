<?php
namespace partner\components;
 
use \Yii;
use yii\base\Component;
use partner\models\PartnerUser;
 
class PartnerAutomaticSystemMessages {
 
 	/**
 	 * Работа с системными сообщениями
 	 */

    const TYPE_INFO = 'info';
    const TYPE_WARNING = 'warning';
    const TYPE_DANGER = 'danger';
    const TYPE_ERROR = 'error';

    protected $systemInfo = [];
    // Обработанные сообщения, подлежащие выдаче в настоящее время
    protected $actualMessages = [];
    // Закрытые сообщения
    protected $closedMessages = [];
    // Признак того, что произошли события, влияющие на сообщения
    protected $dataUpdated = false;
    // Признак, что сообщения уже проверены, устанавливается в true в ResetMessages()
    protected $reset = false;

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

    /**
     * Привязывает события EVENT_BEFORE_REQUEST и EVENT_AFTER_REQUEST
     */
    public function init() {
        Yii::$app->on(yii\base\Application::EVENT_BEFORE_REQUEST, [$this, 'prepareMessages']);
        Yii::$app->on(yii\base\Application::EVENT_AFTER_REQUEST, [$this, 'checkUpdates']);
    }

    /**
     * Сообщения, которые надо выводить сейчас
     */
    public function actualMessages() {
        return $this->actualMessages;
    }

    public function setDataUpdated() {
        $this->dataUpdated = true;
    }

    /**
     * Запускает пересчет сообщений, если были изменения
     */
    public function checkUpdates() {
        if ($this->dataUpdated) {
            $this->resetMessages();
        }
    }

    public function resetMessages($si=false) {
        if ($this->reset) {
            return;
        }
        if (!$this->systemInfo) {
            $this->readSystemInfo();
        }


        $this->actualMessages = [];
        // Цикл по последовательностям
        foreach ($this->messages() as $key0 => $query) {
            //Цикл по сообщениям
            foreach ($query as $key => $message) {
                $f = $message['condition'];
                // Проверяем условие и меняем сообщение, если это предусматривает функция
                if (!method_exists($this, $f)) {
                    throw new \Exception("Method '$f' is not defined in class " . get_class($this) );
                }
                $msg = $this->$f($message);
                if ($msg) {
                    $key = "$key0-$key";
                    if ($msg['close']) {
                        $msg['text'] .= '<p class="pull-right" data-sysmsg-id="' . $key . '"><a href="javascript:closeSystemMessage(\'' . $key . '\')">' 
                            . \Yii::t('automatic_system_messages', 'Don&acute;t show it again.') . '</a></p>';
                    }
                    $this->actualMessages[$key] = $msg;
                }
            }
        }
        \Yii::trace('Reset messages', 'debug');
        $this->reset = true;


    	$this->systemInfo['messages'] = $this->actualMessages;
        $this->systemInfo['messages_update_time'] = time(), // Время апдейта сообщений
        $this->partner->system_info = serialize($this->systemInfo);
    	$this->partner->update(false, ['system_info']);
    }

    public function readSystemInfo() {\Yii::trace('read system info', 'debug');
        if ($this->partner->system_info) {
            $this->systemInfo = unserialize($this->partner->system_info);
            $this->actualMessages = $this->systemInfo['messages'];
        }
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
            $this->readSystemInfo();
    		/*$si = unserialize($this->partner->system_info);
    		$this->actualMessages = $si['messages'];
            $this->closedMessages = isset($si['closed']) ? $si['closed'] : [];*/
            if (isset($this->systemInfo['messages_update_time']) && $this->systemInfo['messages_update_time']) {
                if (time() - $this->systemInfo['messages_update_time'] > 86400 * 1) { // Если апдейт был больше 1-го дня назад - обновляем сообщения
                    $this->resetMessages($this->systemInfo);
                }
            } else {
                $this->resetMessages($this->systemInfo);
            }
    	}

    	// Сообщения для виджета Alert
    	// Получаем список уже показанных сообщений
    	$shownMessages = \Yii::$app->session->get(self::SESSION_SHOWN_MESSAGES, []);//$shownMessages = [];print_r($shownMessages);
    	$a = [];
        foreach ($this->actualMessages as $k => $message) {
            // Если сообщение не было показано, или типа Error, добавляем его к показу и в список показанных
        	if ($message['type'] == self::TYPE_ERROR || !in_array($k, $shownMessages)) { 
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

    public function closeMessage($key) {
        $this->readSystemInfo();
        if (!$this->systemInfo || !array_key_exists($key, $this->systemInfo['messages'])) {
            return;
        }
        $this->systemInfo['closed'][$key] = time();
        
        $this->partner->system_info = serialize($this->systemInfo);echo $this->partner->system_info;
        if ($this->partner->update()) {
            echo ' ok';
        } else {
            echo ' failed';
        }
    }

    /**
     * Возвращает массив сообщений, переопределяется в потомках
     * Массив имет вид ['query-key' => ['item-key' => ['type' => $v, 'condition' => $v, 'title' => $v, 'text' => $v]]] 
     * type - тип сообщения (TYPE_INFO, TYPE_WARNING, TYPE_DANGER)
     * condition - функция, которая обрабатывает сообщения (она же меняет сообщение и возвращает его измененным, например вставляет в текст ссылки)
     */
 	public function messages() {
 		return [
            // Окончание демо периода
            'demoPeriod' => [
                'demoShort' => [
                    'type' => self::TYPE_WARNING,
                    'condition' => 'condDemoShort',
                    'title' => \Yii::t('automatic_system_messages', 'Expiration of demo period'),
                    'text' => 'Expiration of demo period in {n, plural, =0{# days} one{# day} few{# days} many{# days} other{# days}}. <a href="{link}">Put money on your account</a>.',
                    'close' => 0,
                ],
            ],
            'balance' => [
                'critical' => [
                    'type' => self::TYPE_ERROR,
                    'condition' => 'condBalanceCritical',
                    'title' => \Yii::t('automatic_system_messages', 'The account is blocked'),
                    'text' => 'Your balance is {sum}. <a href="{link}">Put money on your account</a>.',
                    'close' => 0,
                ],
                'negative' => [
                    'type' => self::TYPE_DANGER,
                    'condition' => 'condBalanceNegative',
                    'title' => \Yii::t('automatic_system_messages', 'Your balance is negative'),
                    'text' => \Yii::t('automatic_system_messages', '<a href="{link}">Put money on your account</a>.', ['link' => \yii\helpers\Url::toRoute('/billing/pay')]),
                    'close' => 0,
                ],
                'short' => [
                    'type' => self::TYPE_WARNING,
                    'condition' => 'condBalanceShort',
                    'title' => \Yii::t('automatic_system_messages', 'Low balance'),
                    'text' => 'Your balance is {sum}. <a href="{link}">Put money on your account</a>.',
                    'close' => 0,
                ],
            ],
 			// Серия действий необходмых для начала работы
 			'beginWork' => [
 				'noHotels' => [
 					'type' => self::TYPE_DANGER,
 					'condition' => 'condHotelNotCreated',
 					'title' => \Yii::t('automatic_system_messages', 'No registered hotels'),
 					'text' => \Yii::t('automatic_system_messages', 'Please, <a href="{link}">register a hotel</a> to continue.', ['link'=>\yii\helpers\Url::toRoute('/hotel/create')]),
                    'close' => 0,
 				],
 				'noRooms' => [
 					'type' => self::TYPE_DANGER,
 					'condition' => 'condRoomNotCreated',
 					'title' => \Yii::t('automatic_system_messages', 'Hotel registration is not complete'),
 					'text' => 'No rooms registered yet in the following hotels: {link}. Follow the links to add.',
                    'close' => 0,
 				],
 				'noHotelPhotos' => [
 					'type' => self::TYPE_DANGER,
 					'condition' => 'condNoHotelPhotos',
 					'title' => \Yii::t('automatic_system_messages', 'Hotel registration is not complete'),
 					'text' => 'No photos uploded for the following hotels: {link}. Follow the links to upload.',
                    'close' => 0,
 				],
 				'noRoomPhotos' => [
 					'type' => self::TYPE_DANGER,
 					'condition' => 'condNoRoomPhotos',
 					'title' => \Yii::t('automatic_system_messages', 'Room registration is not complete'),
 					'text' => 'No photos uploded for the following rooms: {link}. Follow the links to upload.',
                    'close' => 0,
 				],
 				'noPayMethods' => [
 					'type' => self::TYPE_WARNING,
 					'condition' => 'condNoPayMethods',
 					'title' => \Yii::t('automatic_system_messages', 'Booking is not available'),
 					'text' => \Yii::t('automatic_system_messages', '<a href="{link}">Select the avaliable payment options</a> to activate on-site booking.', ['link'=>\yii\helpers\Url::toRoute('/profile')]),
                    'close' => 1,
 				],
 				'noPrices' => [
 					'type' => self::TYPE_WARNING,
 					'condition' => 'condNoPrices',
 					'title' => \Yii::t('automatic_system_messages', 'Booking is limited'),
 					'text' => 'Not all prices are specified for the next 30 days for the following rooms: {link}. Follow the links to set prices.',
                    'close' => 1,
 				],
 				'noYandexKassa' => [
 					'type' => self::TYPE_WARNING,
 					'condition' => 'condNoYandexKassa',
 					'title' => \Yii::t('automatic_system_messages', 'Booking is limited'),
 					'text' => \Yii::t('automatic_system_messages', '<a href="{link}">Setup Yandex.Kassa</a> to activate online booking.', ['link'=>\yii\helpers\Url::toRoute('/profile')]),
                    'close' => 1,
 				],
 			],
 		];
 	}

    /*****************************************************************************************/
    /* Begin work
    /*****************************************************************************************/

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


    /*****************************************************************************************/
    /* Demo period
    /*****************************************************************************************/
    public function condDemoShort($message) {
        $n = $this->partner->getDemoLeft();
        if ($n !== false && $n > 0 && $n < 10 && $this->partner->billing->balance <= 0) {
            $message['text'] = \Yii::t('automatic_system_messages', $message['text'], ['n' => $n, 'link' => \yii\helpers\Url::toRoute('/billing/pay')]);
            return $message;
        } else {
            return false;
        }
    }

    /*****************************************************************************************/
    /* Balance
    /*****************************************************************************************/
    public function condBalanceCritical($message) {
        if (!$this->partner->getDemoExpired()) {
            return false;
        }
        if ($this->partner->isBlocked()) {
            $message['text'] = \Yii::t('automatic_system_messages', $message['text'], ['sum' => $this->partner->billing->getBalanceString(), 'link' => \yii\helpers\Url::toRoute('/billing/pay')]);
            return $message;
        } else {
            return false;
        }
    }

    public function condBalanceNegative($message) {
        if (!$this->partner->getDemoExpired()) {
            return false;
        }
        if (isset($this->actualMessages['balance-critical'])) {
            return false;
        }
        if ($this->partner->billing->balance <= 0) {
            return $message;
        } else {
            return false;
        }
    }

    public function condBalanceShort($message) {
        if (!$this->partner->getDemoExpired()) {
            return false;
        }
        if (isset($this->actualMessages['balance-critical']) || isset($this->actualMessages['balance-negative'])) {
            return false;
        }
        // TODO: сделать вывод сообщений в зависимости от дневных затрат
        if ($this->partner->billing->balance < 300) {
            $message['text'] = \Yii::t('automatic_system_messages', $message['text'], ['sum' => $this->partner->billing->getBalanceString(), 'link' => \yii\helpers\Url::toRoute('/billing/pay')]);
            return $message;
        } else {
            return false;
        }
    }

}