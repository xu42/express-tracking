<?php

namespace Cn\Xu42\ExpressTracking\Exception;

class ETSystemException extends BaseException
{
    public $message = '快递追踪查询系统异常';

    public $code = '42103000';
}
