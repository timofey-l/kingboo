<?php
namespace common\components;

use DOMDocument;
use DOMElement;
use yii\base\Component;
use yii\helpers\ArrayHelper;
use yii\web\Response;
use yii\web\ResponseFormatterInterface;

class YandexResponseFormatter extends Component implements ResponseFormatterInterface
{
    /**
     * @var string the Content-Type header for the response
     */
    public $contentType = 'application/xml';
    /**
     * @var string the XML version
     */
    public $version = '1.0';
    /**
     * @var string the XML encoding. If not set, it will use the value of [[Response::charset]].
     */
    public $encoding;

    /**
     * Formats the specified response.
     * @param Response $response the response to be formatted.
     */
    public function format($response)
    {
        if (!is_array($response->data)) {
            return;
        }
        // type 'check' or 'avisio'
        $type = ArrayHelper::getValue($response->data, 'type', '');
        $charset = $this->encoding === null ? $response->charset : $this->encoding;
        if (stripos($this->contentType, 'charset') === false) {
            $this->contentType .= '; charset=' . $charset;
        }
        $response->getHeaders()->set('Content-Type', $this->contentType);
        $dom = new DOMDocument($this->version, $charset);
        if ($type == 'check') {
            $root = new DOMElement('checkOrderResponse');
        } elseif ($type == 'avisio') {
            $root = new DOMElement('paymentAvisoResponse');
        }
        $dom->appendChild($root);

        foreach ($response->data as $name => $value) {
            if ($name !== 'type') {
                $root->setAttribute($name, $value);
            }
        }

        $response->content = $dom->saveXML();
    }
}