<?php

namespace app\commands;


use Yii;
use yii\console\Controller;
use yii\console\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\Console;
use yii\httpclient\Client;
use app\models\Order;

/**
 * Заказы
 */
class OrderController extends Controller
{
    /**
     * Обнволение заказов
     * @param $url
     * @return void
     * @throws Exception
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\httpclient\Exception
     * @throws \Exception
     */
    public function actionUpdateNet($url = null)
    {
        if ($url === null) {
            $url = Yii::$app->params['order_json_url'];
        }

        $client = new Client();

        $response = $client
            ->createRequest()
            ->setMethod('GET')
            ->setUrl($url)
            ->send();

        if ($response->isOk) {
            $total = 0;
            $success = 0;
            $withErrors = 0;

            $items = ArrayHelper::getValue($response->data, 'orders');

            foreach ($items as $item) {
                $total += 1;

                $real_id = ArrayHelper::getValue($item, 'real_id');
                $user_name = ArrayHelper::getValue($item, 'user_name');
                $user_phone = ArrayHelper::getValue($item, 'user_phone');
                $warehouse_id = ArrayHelper::getValue($item, 'warehouse_id');
                $created_at = ArrayHelper::getValue($item, 'created_at');
                $status = ArrayHelper::getValue($item, 'status');
                $orderItems = ArrayHelper::getValue($item, 'items');

                $items_count = is_array($orderItems) ? count($orderItems) : 0;

                if ($real_id !== null) {
                    $order = Order::find()->realId($real_id)->one();

                    if ($order === null) {
                        $order = new Order();
                    }

                    $order->real_id = $real_id;
                    $order->user_name = $user_name;
                    $order->user_phone = $user_phone;
                    $order->warehouse_id = $warehouse_id;
                    $order->created_at = $created_at;
                    $order->status = $status;
                    $order->items_count = $items_count;

                    if ($order->save()) {
                        $success += 1;
                    } else {
                        $withErrors += 1;
                        $this->stdout($real_id.': '.Console::errorSummary($order).PHP_EOL, Console::FG_RED);
                    }
                }
            }

            $this->stdout('Обработано заказов: '.$total.PHP_EOL);
            $this->stdout('Успешно: '.$success.PHP_EOL, Console::FG_GREEN);
            $this->stdout('С ошибками: '.$withErrors.PHP_EOL, Console::FG_RED);
        } else {
            throw new Exception($response->getStatusCode());
        }
    }

    /**
     * Информация о заказе
     * @param $order_id
     * @return void
     */
    public function actionInfo($order_id)
    {
        $order = $this->findModel($order_id);

        $jsonAttributes = Json::encode($order->attributes);
        $output = str_replace(',', ','.PHP_EOL.'    ', $jsonAttributes);
        $output = str_replace('{', '{'.PHP_EOL.'    ', $output);
        $output = str_replace('}', PHP_EOL.'}'.PHP_EOL, $output);

        $this->stdout($output, Console::FG_GREEN);
    }

    /**
     * Finds the Order model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $real_id
     * @return Order the loaded model
     * @throws \Exception if the model cannot be found
     */
    protected function findModel($real_id)
    {
        if (($model = Order::find()->realId($real_id)->one()) !== null) {
            return $model;
        }

        throw new Exception('Заказ не найден.');
    }
}