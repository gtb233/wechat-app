<?php

namespace api\models;

use common\components\WechatCoreHelper;
use yii\base\Exception;

/**
 * 微信消息处理
 */
class MsgEventManage
{
    public $postObj; //接收到的微信请求数据

    /**
     * @return MsgEventManage
     */
    public static function factory($postObj)
    {
        $class = __CLASS__;
        return new $class($postObj);
    }

    public function __construct($postObj)
    {
        $this->postObj = $postObj;
    }

    /**
     * 消息处理
     * @throws Exception
     */
    public function MsgManage()
    {
        try {
            switch ($this->postObj->MsgType) {
                case 'text'://文本信息
                    $textMsg = new TextManage();
                    $textMsg->userContent = $this->postObj->Content;
                    $textMsg->result();
                    break;
                case 'image'://图片信息
                    break;
                case 'location': //地理信息
                    break;
                case 'event':
                    $this->EventManage();
                    break;
                default:
                    exit('');
                    break;
            }
        } catch (Exception $e) {
            WechatCoreHelper::wechatLogRecord($e->getMessage(), 'MsgManage');
            throw new Exception($e->getMessage());
        }
    }

    /**
     * 事件处理
     * @throws Exception
     */
    public function EventManage()
    {
        try {
            switch ($this->postObj->Event) {
                case 'subscribe'://用户关注
                    $subscribe = new SubscribeManage();
                    $subscribe->result();
                    break;
                case 'unsubscribe':  //取消关注
                    $unSubscribe = new SubscribeManage();
                    $unSubscribe->unsubscribe();
                    break;
                case 'CLICK'://自定义菜单推送
                    break;
                default:
                    exit('');
                    break;
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}