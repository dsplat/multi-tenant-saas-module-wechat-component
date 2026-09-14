// 微信第三方平台服务商（S 端）admin 配置页：组件凭证 CRUD / 连接测试 / 已授权租户列表。
// 有自定义 routes.ts 的模块不再走视图自动发现，需在此显式声明全部页面；
// meta.menu 声明侧边菜单（AdminLayout 动态聚合，无需改布局硬编码）
const MENU_SECTION = '平台配置'

const routes = [
  {
    path: 'component-provider-settings',
    name: 'wechat-admin-component-provider-settings',
    component: () => import('./ui/element-plus/views/ComponentProviderSettings.vue'),
    meta: {
      title: '微信服务商', requiresAuth: true, module: 'wechat-component',
      menu: { section: MENU_SECTION, label: '微信服务商', icon: 'ChatDotRound', perm: 'platform.setting.view' },
    },
  },
]

export default routes
