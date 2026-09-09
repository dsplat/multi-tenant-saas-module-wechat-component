<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * wechat_authorizations 唯一约束：一租户一授权 → 一租户每账号类型一条
     *
     * 放开 UNIQUE(tenant_id) 以支持公众号 + 小程序两类授权并存（H5 公众号
     * 登录与小程序登录/构建各取所需）；authorizer_appid 全局 UNIQUE 保留
     * （微信账号语义天然全局唯一）。存量数据每租户至多一条，放开不产生冲突。
     */
    public function up(): void
    {
        Schema::table('wechat_authorizations', function ($table) {
            $table->dropUnique('wechat_authorizations_tenant_unique');
            $table->unique(['tenant_id', 'authorizer_type'], 'wechat_authorizations_tenant_type_unique');
        });
    }

    public function down(): void
    {
        Schema::table('wechat_authorizations', function ($table) {
            $table->dropUnique('wechat_authorizations_tenant_type_unique');
            $table->unique('tenant_id', 'wechat_authorizations_tenant_unique');
        });
    }
};
