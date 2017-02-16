<?php

namespace Swoole\Packet;

use Swoole\Protocols\Json;
use Swoole\Protocols\Serialize;

class Format {

    /**
     * 协议包头长度
     */
    const HEADER_SIZE = 12;

    /**
     * 拼装返回数据
     * @param string $data
     * @param string $message
     * @param int $code
     * @return array
     */
    public static function packFormat($data = '', $message = 'OK', $code = 0)
    {
        $pack = [
            'code'      => $code,
            'message'   => $message,
            'data'      => $data
        ];

        return $pack;
    }

    /**
     * 解包包头
     * @param $pack
     * @return array|bool
     */
    public static function packDecodeHeader($pack)
    {
        $header = unpack('Nlength/Ntype/Nguid', substr($pack, 0, self::HEADER_SIZE));

        if ($header === false) {
            return false;
        }
        
        return $header;
    }

    /**
     * 检测包头长度
     * @param $length
     * @param $pack
     * @return bool
     */
    public static function checkHeaderLength($length, $pack)
    {
        $data = substr($pack, self::HEADER_SIZE);
        if ($length != strlen($data)) {
            return false;
        }
        
        return true;
    }

    /**
     * 解包
     * @param $pack
     * @param $protocol_mode
     * @return array|bool
     */
    public static function packDecode($pack, $protocol_mode)
    {
        switch ($protocol_mode) {
            case Json::PROTOCOLS_MODE :
                $pack = Json::decode($pack);
                break;
            case Serialize::PROTOCOLS_MODE :
                $pack = Serialize::decode($pack);
                break;
            default:
                $pack = false;
                break;
        }
        
        return $pack;
    }

    /**
     * 打包
     * @param $data
     * @param int $protocol_mode
     * @param int $guid
     * @return string
     */
    public static function packEncode($data, $protocol_mode = Json::PROTOCOLS_MODE, $guid = 0)
    {
        switch ($protocol_mode) {
            case Json::PROTOCOLS_MODE :
                $data = Json::encode($data, $guid);
                break;
            case Serialize::PROTOCOLS_MODE :
                $data = Serialize::encode($data, $guid);
                break;
            default:
                $data = false;
                break;
        }

        return $data;
    }
}