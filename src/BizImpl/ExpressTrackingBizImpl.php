<?php

namespace Cn\Xu42\ExpressTracking\BizImpl;

use Cn\Xu42\ExpressTracking\Exception\ArgumentException;
use Cn\Xu42\ExpressTracking\Exception\SystemException;

class ExpressTrackingBizImpl
{
    const URL_WAP = 'http://m.kuaidi100.com/result.jsp?from=weixin&nu=';
    const URL_COM_CODE = 'http://m.kuaidi100.com/autonumber/auto?num=';
    const URL_QUERY = 'http://m.kuaidi100.com/query?';


    public function getComCodes($postId)
    {
        if (empty($postId)) throw new ArgumentException('运单号格式错误');

        $url = self::URL_COM_CODE . $postId;
        $curlResponse = $this->curlRequest($url);
        $comCodes = [];

        foreach (json_decode($curlResponse, true) as $item) {
            $comCodes[] = isset($item['comCode']) ? $item['comCode'] : null;
        }

        if (empty($comCodes)) throw new SystemException('comCode查询不到');

        return $comCodes;
    }


    public function query($postId, $comCodes)
    {
        if (empty($postId)) throw new ArgumentException('运单号格式错误');

        if (empty($comCodes) || !is_array($comCodes)) throw new ArgumentException('comCodes格式错误');

        $result = [];
        foreach ($comCodes as $comCode) {
            $result = $this->getQueryResult($comCode, $postId);
            if ($result['status'] == '200') break;
        }

        if (empty($result) || $result['message'] != 'ok') throw new SystemException('查询不到');

        $result['url'] = self::URL_WAP . $postId;

        return $result;
    }


    private function curlRequest($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_REFERER, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:24.0) Gecko/20100101 Firefox/24.0');
        $curlResponse = curl_exec($ch);
        curl_close($ch);
        return $curlResponse;
    }


    private function getQueryResult($comCode, $postId)
    {
        $url = self::URL_QUERY . 'type=' . $comCode . '&postid=' . $postId . '&id=1&valicode=&temp=' . time();
        $response = $this->curlRequest($url);
        return json_decode($response, true);
    }

}