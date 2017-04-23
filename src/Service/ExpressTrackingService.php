<?php

namespace Cn\Xu42\ExpressTracking\Service;

use Cn\Xu42\ExpressTracking\Exception\SystemException;
use Cn\Xu42\ExpressTracking\BizImpl\ExpressTrackingBizImpl;

class ExpressTrackingService
{
    private $bizImpl = null;

    public function __construct()
    {
        $this->bizImpl = new ExpressTrackingBizImpl();
    }

    /**
     * 获取快递运单号可能所属的公司
     *
     * @param $postId
     * @return array
     * @throws SystemException
     */
    public function getComCodes($postId)
    {
        try {
            return $this->bizImpl->getComCodes($postId);
        } catch (\Throwable $throwable) {
            throw new SystemException($throwable->getMessage());
        }
    }

    /**
     * 获取快递运单号最新动态
     *
     * @param $postId
     * @param $comCodes
     * @return array|mixed
     * @throws SystemException
     */
    public function query($postId, $comCodes)
    {
        try {
            return $this->bizImpl->query($postId, $comCodes);
        } catch (\Throwable $throwable) {
            throw new SystemException($throwable->getMessage());
        }
    }

}
