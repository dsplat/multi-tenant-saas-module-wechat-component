<?php

namespace MultiTenantSaas\Modules\WechatComponent;

use MultiTenantSaas\Modules\Contracts\ModuleServiceProvider;

/**
 * 微信第三方平台服务商（S 端）模块
 *
 * 职责边界（C/S 拆分）：本模块是「运营微信第三方平台」的 S 端——组件凭证管理、
 * component_verify_ticket 接收、component_access_token 缓存、授权发起/回跳/入库、
 * authorizer_refresh_token 管理。C 端（消费 component/authorizer token 做网页授权
 * 登录、jscode2session、代调用/代开发）留在 Wechat 模块，对本模块为可选探测依赖
 * （class_exists + Schema::hasTable），未安装本模块时 C 端代模式不可用、self 接入
 * 方式不受影响（参照 WechatWork suite 与 Wechat OAuth 的关系）。
 */
class WechatComponentServiceProvider extends ModuleServiceProvider
{
    protected string $moduleName = 'wechatcomponent';

    protected function registerModuleBindings(): void
    {
        //
    }

    protected function bootModule(): void
    {
        $this->registerComponentCallbackRoutes();
    }

    /**
     * 微信第三方平台组件回调路由（裸路由，无中间件链）
     *
     * 微信第三方平台回调 URL 必须公网可访问，且回调请求不携带租户上下文
     * （Host 为平台统一回调域 auth.neihang.com）：
     * - GET：URL 有效性验证（echostr 验签解密）
     * - POST：事件推送（component_verify_ticket 每 10 分钟 / authorized /
     *   updateauthorized / unauthorized）
     * - authorize/launch：授权发起统一入口（平台域 302 到微信授权页）
     * - authorize/callback：授权页完成后的平台域回跳（auth_code 换授权入库）
     * 控制器内按 component_appid 手动解析组件凭证，参照 WechatWork 模块
     * suite.php 的裸路由注册先例。
     */
    protected function registerComponentCallbackRoutes(): void
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        $moduleDir = dirname((new \ReflectionClass($this))->getFileName());
        $path = $moduleDir . '/Routes/callback.php';

        if (file_exists($path)) {
            $this->loadRoutesFrom($path);
        }
    }
}
