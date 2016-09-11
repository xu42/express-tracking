<?php

namespace Xu42\ExpressTracking;

/**
 * 快递单号状态追踪
 * Class ExpressTracking
 * @package Xu42\ExpressTracking
 */
class ExpressTracking
{
    private $num = '';
//    private $comCode    = '';
    private $urlWap     = 'http://m.kuaidi100.com/result.jsp?from=weixin&nu=';
    private $urlComCode = 'http://m.kuaidi100.com/autonumber/auto?num=';
    private $urlQuery   = 'http://m.kuaidi100.com/query?';

    /**
     * expressTracking constructor.
     * @param string $webUrl
     */
    public function __construct( $num )
    {
        $this->num        = $num;
        $this->urlWap     = $this->urlWap . $num;
        $this->urlComCode = $this->urlComCode . $num;
    }

    /**
     * 获取一快递单号的最新动态
     * status 200表示查询正常, 其它为非异常结果
     * @return mixed
     */
    public function latestStatus()
    {
        $result = $this->query();
        if ( is_null( $result ) ) {
            return ['status' => '404', 'message' => '未查询到快递状态', 'data' => [], 'url' => $this->urlWap];
        }
        if ( $result['message'] == 'ok' ) {
            $result['url'] = $this->urlWap;
            return $result;
        }
        return ['status' => $result['status'], 'message' => $result['message'], 'data' => [], 'url' => $this->urlWap];
    }

    /**
     * 简单封装的get网络请求助手
     * @param $url
     * @return mixed
     */
    private function myCurl( $url )
    {
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, $url );
        curl_setopt( $ch, CURLOPT_REFERER, $this->urlWap );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 5 );
        curl_setopt( $ch, CURLOPT_USERAGENT,
            'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:24.0) Gecko/20100101 Firefox/24.0' );
        $webPage = curl_exec( $ch );
        curl_close( $ch );
        return $webPage;
    }

    /**
     * 获取快递单号所属的快递公司代号
     * @return mixed
     */
    private function getComCode()
    {
        $response = $this->myCurl( $this->urlComCode );
        $comCode  = json_decode( $response, true );
        $com      = [];
        if ( isset($comCode[0]) ) {
//            $this->comCode = $comCode[0]['comCode'];
//            return $comCode[0]['comCode'];
            $com[] = $comCode[0]['comCode'];
            if ( isset($comCode[1]) ) {
                $com[] = $comCode[1]['comCode'];
            }
            return $com;
        }
        return null;
    }

    /**
     * 查询快递状态
     * @return mixed
     */
    private function query()
    {

        $type = $this->getComCode();

        if ( is_null( $type ) ) {
            return null;
        }
        $result = $this->getQueryResult( $type[0], $this->num );
        if ($result['status'] == '200'){
            return $result;
        }

        if ( $result['status'] == '201' && count( $type ) >= 2 ) {
            $result1 = $this->getQueryResult( $type[1], $this->num );
            if ($result1['status'] == '200') {
                return $result1;
            }
        }
        return $result;
    }

    /**
     * 获取查询结果
     * @param $type
     * @param $postId
     * @return mixed
     */
    private function getQueryResult( $type, $postId )
    {
        $url      = $this->urlQuery . 'type=' . $type . '&postid=' . $postId . '&id=1&valicode=&temp=' . time();
        $response = $this->myCurl( $url );
        $result   = json_decode( $response, true );
        return $result;
    }
}
