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
    private $comCode = '';
    private $urlWap = 'http://m.kuaidi100.com/result.jsp?from=weixin&nu=';
    private $urlComCode = 'http://m.kuaidi100.com/autonumber/auto?num=';
    private $urlQuery = 'http://m.kuaidi100.com/query?';

    /**
     * expressTracking constructor.
     * @param string $webUrl
     */
    public function __construct($num)
    {
        $this->num = $num;
        $this->urlWap = $this->urlWap . $num;
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
        if (is_null($result)) {
            return ['status' => '404', 'message' => '未查询到快递状态', 'data' => [], 'url' => $this->urlWap];
        }
        if ($result['message'] == 'ok') {
            return ['status' => $result['status'], 'state' => $result['state'], 'data' => $result['data'], 'url' => $this->urlWap];
        }
        return ['status' => $result['status'], 'message' => $result['message'], 'data' => [], 'url' => $this->urlWap];
    }

    /**
     * 简单封装的get网络请求助手
     * @param $url
     * @return mixed
     */
    private function myCurl($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_REFERER, $this->urlWap);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:24.0) Gecko/20100101 Firefox/24.0');
        $webPage = curl_exec($ch);
        curl_close($ch);
        return $webPage;
    }

    /**
     * 获取快递单号所属的快递公司代号
     * @return mixed
     */
    private function getComCode()
    {
        $response = $this->myCurl($this->urlComCode);
        $comCode = json_decode($response, true);
        if (isset($comCode[0])) {
            $this->comCode = $comCode[0]['comCode'];
            return $comCode[0]['comCode'];
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
        if (is_null($type)) {
            return null;
        }
        $this->urlQuery = $this->urlQuery . 'type=' . $type . '&postid=' . $this->num . '&id=1&valicode=&temp=' .time();
        $response = $this->myCurl($this->urlQuery);
        $result = json_decode($response, true);
        return $result;
    }
}
