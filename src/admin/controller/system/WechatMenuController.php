<?php

namespace warm\admin\controller\system;

use support\Request;
use support\Response;
use warm\admin\controller\AdminController;
use warm\admin\renderer\Form;
use warm\admin\renderer\Page;
use warm\admin\service\system\WechatMenuService;

/**
 * 微信菜单控制器
 * @extends AdminController<WechatMenuService>
 */
class WechatMenuController extends AdminController
{
    /**
     * @var string 服务类名
     */
    protected string $serviceName = WechatMenuService::class;

    /**
     * 微信菜单管理页面
     *
     * @return Page
     */
    public function index(): Response
    {
        // 如果是获取数据的操作，返回列表数据
        if ($this->actionOfGetData()) {
            return $this->response()->success($this->service->list());
        }

        // 返回页面
        return $this->response()->success($this->menuPage());
    }

    /**
     * 菜单管理页面
     *
     * @return Page
     */
    public function menuPage(): Page
    {
        $page = $this->basePage()
            ->title('微信菜单设置')
            ->body([
                amis()->Flex()
                    ->className('h-full')
                    ->items([
                        // 左侧：手机预览
                        // amis()->Card()
                        //     ->className('flex-1')
                        //     ->header(['title' => '手机预览'])
                        //     ->body($this->phonePreview()),
                        // 右侧：菜单列表和表单
                        amis()->Card()
                            ->className('flex-1 ml-4')
                            ->header(['title' => '菜单信息'])
                            ->body($this->menuForm())
                    ])
            ]);

        return $page;
    }

    /**
     * 手机预览组件
     *
     * @return array
     */
    private function phonePreview(): array
    {
        return [
            amis()->Card()
                ->className('phone-preview-card')
                ->body([
                    amis()->Tpl()
                        ->className('phone-mockup-wrapper')
                        ->tpl('<div class="phone-mockup">
                            <div class="phone-header">
                                <div class="phone-status-bar">
                                    <span>..... WeChat</span>
                                    <span>1:21 AM</span>
                                    <span>100%</span>
                                </div>
                                <div class="phone-nav">
                                    <span class="back-icon">←</span>
                                    <span>返回</span>
                                    <span class="user-icon">👤</span>
                                </div>
                            </div>
                            <div class="phone-content">
                                <div class="menu-content-area" id="menu-content-area">
                                    <div style="padding: 20px; text-align: center; color: #999; font-size: 14px;">
                                        点击底部菜单进行编辑
                                    </div>
                                </div>
                            </div>
                            <div class="phone-footer-wrapper">
                                <div class="submenu-area" id="submenu-area">
                                    <!-- 子菜单区域，显示在一级菜单上方 -->
                                </div>
                                <div class="phone-footer" id="phone-footer">
                                    <!-- 三栏底部菜单将通过JS动态渲染 -->
                                </div>
                            </div>
                        </div>')
                        ->style([
                            'padding' => '20px',
                            'background' => '#f5f5f5',
                            'min-height' => '600px'
                        ]),
                    amis()->Service()
                        ->id('menu-preview-service')
                        ->api($this->getListGetDataPath())
                        ->onEvent([
                            'inited' => [
                                'actions' => [
                                    [
                                        'actionType' => 'custom',
                                        'script' => 'console.log("Service inited:", event.data); setTimeout(function() { if (window.initMenuPreview) window.initMenuPreview(event.data); }, 200);'
                                    ]
                                ]
                            ],
                            'finished' => [
                                'actions' => [
                                    [
                                        'actionType' => 'custom',
                                        'script' => 'console.log("Service finished:", event.data); setTimeout(function() { if (window.initMenuPreview) window.initMenuPreview(event.data); }, 200);'
                                    ]
                                ]
                            ]
                        ])
                        ->body([
                            amis()->Tpl()
                                ->tpl('<script>
                                    (function() {
                                        let menuData = [];
                                        let selectedMenuId = null;
                                        
                                        // 初始化菜单数据
                                        function initMenuData(data) {
                                            console.log("初始化菜单数据:", data);
                                            
                                            // 处理amis返回的数据格式
                                            let items = [];
                                            let treeData = [];
                                            if (data) {
                                                if (data.data) {
                                                    // 优先使用tree数据（树形结构）
                                                    if (data.data.tree && Array.isArray(data.data.tree)) {
                                                        treeData = data.data.tree;
                                                    }
                                                    // 如果没有tree，使用items
                                                    if (data.data.items && Array.isArray(data.data.items)) {
                                                        items = data.data.items;
                                                    }
                                                } else if (data.tree && Array.isArray(data.tree)) {
                                                    treeData = data.tree;
                                                } else if (data.items && Array.isArray(data.items)) {
                                                    items = data.items;
                                                } else if (Array.isArray(data)) {
                                                    items = data;
                                                }
                                            }
                                            
                                            // 扁平化菜单数据（包含子菜单）
                                            menuData = [];
                                            
                                            // 如果有tree数据，使用tree数据（更准确）
                                            if (treeData.length > 0) {
                                                treeData.forEach(menu => {
                                                    menuData.push({
                                                        id: menu.id,
                                                        name: menu.name,
                                                        type: menu.type,
                                                        key: menu.key,
                                                        url: menu.url,
                                                        appid: menu.appid,
                                                        pagepath: menu.pagepath,
                                                        miniprogram_url: menu.miniprogram_url,
                                                        parent_id: menu.parent_id || 0,
                                                        sort: menu.sort || 0
                                                    });
                                                    
                                                    // 添加子菜单
                                                    if (menu.sub_menus && Array.isArray(menu.sub_menus)) {
                                                        menu.sub_menus.forEach(subMenu => {
                                                            menuData.push({
                                                                id: subMenu.id,
                                                                name: subMenu.name,
                                                                type: subMenu.type,
                                                                key: subMenu.key,
                                                                url: subMenu.url,
                                                                appid: subMenu.appid,
                                                                pagepath: subMenu.pagepath,
                                                                miniprogram_url: subMenu.miniprogram_url,
                                                                parent_id: subMenu.parent_id || menu.id,
                                                                sort: subMenu.sort || 0
                                                            });
                                                        });
                                                    }
                                                });
                                            } else {
                                                // 使用扁平化的items数据
                                                items.forEach(menu => {
                                                    menuData.push({
                                                        id: menu.id,
                                                        name: menu.name,
                                                        type: menu.type,
                                                        key: menu.key,
                                                        url: menu.url,
                                                        appid: menu.appid,
                                                        pagepath: menu.pagepath,
                                                        miniprogram_url: menu.miniprogram_url,
                                                        parent_id: menu.parent_id || 0,
                                                        sort: menu.sort || 0
                                                    });
                                                });
                                            }
                                            
                                            console.log("处理后的菜单数据:", menuData);
                                            renderFooter();
                                        }
                                        
                                        // 渲染底部三栏菜单和子菜单
                                        function renderFooter() {
                                            const footer = document.getElementById("phone-footer");
                                            const submenuArea = document.getElementById("submenu-area");
                                            
                                            if (!footer) {
                                                console.error("找不到phone-footer元素");
                                                return;
                                            }
                                            
                                            if (!submenuArea) {
                                                console.error("找不到submenu-area元素");
                                                return;
                                            }
                                            
                                            console.log("开始渲染菜单，数据量:", menuData.length);
                                            
                                            let html = "";
                                            const maxMenus = 3;
                                            const currentMenus = menuData.filter(m => m.parent_id == 0)
                                                .sort((a, b) => (a.sort || 0) - (b.sort || 0))
                                                .slice(0, maxMenus);
                                            
                                            console.log("一级菜单数量:", currentMenus.length);
                                            
                                            // 渲染已有的一级菜单
                                            currentMenus.forEach((menu, index) => {
                                                const isActive = selectedMenuId == menu.id;
                                                html += `<div class="footer-item ${isActive ? \'active\' : \'\'}" 
                                                    data-menu-id="${menu.id}" 
                                                    data-index="${index}"
                                                    onclick="window.selectMenu(${menu.id}, ${index})">
                                                    ${menu.name || "菜单" + (index + 1)}
                                                </div>`;
                                            });
                                            
                                            // 如果还有空位，显示+号
                                            if (currentMenus.length < maxMenus) {
                                                const addIndex = currentMenus.length;
                                                html += `<div class="footer-item footer-add" onclick="window.addMenu(${addIndex})">
                                                    <span class="add-icon">+</span>
                                                </div>`;
                                            }
                                            
                                            // 如果只有一栏，默认显示+号
                                            if (currentMenus.length === 0) {
                                                html = `<div class="footer-item footer-add" onclick="window.addMenu(0)">
                                                    <span class="add-icon">+</span>
                                                </div>
                                                <div class="footer-item"></div>
                                                <div class="footer-item"></div>`;
                                            }
                                            
                                            footer.innerHTML = html;
                                            
                                            // 确保至少显示一个+号（如果没有菜单）
                                            if (currentMenus.length === 0 && !html.includes("footer-add")) {
                                                footer.innerHTML = `<div class="footer-item footer-add" onclick="window.addMenu(0)">
                                                    <span class="add-icon">+</span>
                                                </div>
                                                <div class="footer-item"></div>
                                                <div class="footer-item"></div>`;
                                            }
                                            
                                            // 渲染子菜单区域
                                            renderSubmenuArea();
                                        }
                                        
                                        // 渲染子菜单区域（显示在一级菜单上方）
                                        function renderSubmenuArea() {
                                            const submenuArea = document.getElementById("submenu-area");
                                            if (!submenuArea) return;
                                            
                                            if (!selectedMenuId) {
                                                submenuArea.innerHTML = "";
                                                return;
                                            }
                                            
                                            const menu = menuData.find(m => m.id == selectedMenuId);
                                            if (!menu || menu.parent_id != 0) {
                                                submenuArea.innerHTML = "";
                                                return;
                                            }
                                            
                                            // 获取该一级菜单的所有二级菜单
                                            const subMenus = menuData.filter(m => m.parent_id == selectedMenuId)
                                                .sort((a, b) => (a.sort || 0) - (b.sort || 0));
                                            
                                            // 找到该一级菜单在底部的位置索引
                                            const firstLevelMenus = menuData.filter(m => m.parent_id == 0)
                                                .sort((a, b) => (a.sort || 0) - (b.sort || 0));
                                            const menuIndex = firstLevelMenus.findIndex(m => m.id == selectedMenuId);
                                            
                                            if (menuIndex === -1) {
                                                submenuArea.innerHTML = "";
                                                return;
                                            }
                                            
                                            // 计算子菜单区域的宽度和位置（对应底部菜单的位置）
                                            const itemWidth = 100 / 3; // 每栏宽度33.33%
                                            const leftPosition = menuIndex * itemWidth;
                                            
                                            let submenuHtml = `<div class="submenu-container" style="left: ${leftPosition}%; width: ${itemWidth}%;">
                                                <div class="submenu-list">`;
                                            
                                            // 渲染子菜单项
                                            subMenus.forEach((subMenu, index) => {
                                                submenuHtml += `<div class="submenu-item" 
                                                    onclick="window.selectSubMenu(${subMenu.id})"
                                                    data-submenu-id="${subMenu.id}">
                                                    ${subMenu.name || "子菜单" + (index + 1)}
                                                </div>`;
                                            });
                                            
                                            // 添加子菜单按钮（最多5个）
                                            if (subMenus.length < 5) {
                                                submenuHtml += `<div class="submenu-item submenu-add" onclick="window.addSubMenu()">
                                                    <span class="add-icon">+</span>
                                                </div>`;
                                            }
                                            
                                            submenuHtml += `</div></div>`;
                                            
                                            submenuArea.innerHTML = submenuHtml;
                                        }
                                        
                                        // 更新表单数据
                                        window.updateFormData = function(menu) {
                                            // 通过amis的store更新表单数据
                                            const formStore = window.amisStore && window.amisStore.getStoreById("menu-form");
                                            if (formStore) {
                                                formStore.setData({
                                                    id: menu.id,
                                                    name: menu.name || "",
                                                    type: menu.type || "click",
                                                    key: menu.key || "",
                                                    url: menu.url || "",
                                                    appid: menu.appid || "",
                                                    pagepath: menu.pagepath || "",
                                                    miniprogram_url: menu.miniprogram_url || "",
                                                    parent_id: menu.parent_id || 0,
                                                    sort: menu.sort || 0
                                                });
                                            } else {
                                                // 备用方案：直接操作DOM
                                                setTimeout(() => {
                                                    const form = document.querySelector("#menu-form");
                                                    if (form) {
                                                        const inputs = form.querySelectorAll("input, select");
                                                        inputs.forEach(input => {
                                                            const name = input.name || input.getAttribute("name");
                                                            if (name && menu[name] !== undefined) {
                                                                if (input.type === "checkbox" || input.type === "radio") {
                                                                    input.checked = input.value == menu[name];
                                                                } else {
                                                                    input.value = menu[name] || "";
                                                                }
                                                            }
                                                        });
                                                    }
                                                }, 100);
                                            }
                                        }
                                        
                                        // 重置表单
                                        window.resetForm = function(data) {
                                            // 通过amis的store重置表单数据
                                            const formStore = window.amisStore && window.amisStore.getStoreById("menu-form");
                                            if (formStore) {
                                                formStore.setData({
                                                    id: undefined,
                                                    name: "",
                                                    type: "click",
                                                    key: "",
                                                    url: "",
                                                    appid: "",
                                                    pagepath: "",
                                                    miniprogram_url: "",
                                                    parent_id: data ? data.parent_id : 0,
                                                    sort: 0
                                                });
                                            } else {
                                                // 备用方案：直接操作DOM
                                                setTimeout(() => {
                                                    const form = document.querySelector("#menu-form");
                                                    if (form) {
                                                        form.reset();
                                                        const parentIdInput = form.querySelector("[name=\'parent_id\']");
                                                        if (parentIdInput && data) {
                                                            parentIdInput.value = data.parent_id || 0;
                                                        }
                                                    }
                                                }, 100);
                                            }
                                        }
                                        
                                        // 选择菜单
                                        window.selectMenu = function(menuId, index) {
                                            selectedMenuId = menuId;
                                            const menu = menuData.find(m => m.id == menuId);
                                            if (!menu) return;
                                            
                                            // 更新表单数据
                                            updateFormData(menu);
                                            
                                            // 更新底部菜单选中状态
                                            document.querySelectorAll(".footer-item").forEach(item => {
                                                item.classList.remove("active");
                                                if (item.getAttribute("data-menu-id") == menuId) {
                                                    item.classList.add("active");
                                                }
                                            });
                                            
                                            // 重新渲染子菜单区域
                                            renderSubmenuArea();
                                        }
                                        
                                        // 添加一级菜单
                                        window.addMenu = function(index) {
                                            selectedMenuId = null;
                                            
                                            // 检查一级菜单数量限制
                                            const firstLevelMenus = menuData.filter(m => m.parent_id == 0);
                                            if (firstLevelMenus.length >= 3) {
                                                alert("一级菜单最多只能添加3个");
                                                return;
                                            }
                                            
                                            // 重置表单
                                            resetForm({parent_id: 0});
                                            
                                            // 清空子菜单区域
                                            const submenuArea = document.getElementById("submenu-area");
                                            if (submenuArea) {
                                                submenuArea.innerHTML = "";
                                            }
                                            
                                            // 更新底部菜单，+号移到下一栏
                                            setTimeout(() => {
                                                renderFooter();
                                            }, 100);
                                        }
                                        
                                        // 添加二级菜单
                                        window.addSubMenu = function() {
                                            if (!selectedMenuId) return;
                                            
                                            const menu = menuData.find(m => m.id == selectedMenuId);
                                            if (!menu) return;
                                            
                                            // 检查二级菜单数量限制
                                            const subMenus = menuData.filter(m => m.parent_id == selectedMenuId);
                                            if (subMenus.length >= 5) {
                                                alert("每个一级菜单最多只能添加5个二级菜单");
                                                return;
                                            }
                                            
                                            // 重置表单，设置为二级菜单
                                            resetForm({parent_id: selectedMenuId});
                                        }
                                        
                                        
                                        // 选择二级菜单
                                        window.selectSubMenu = function(menuId) {
                                            const menu = menuData.find(m => m.id == menuId);
                                            if (!menu) return;
                                            
                                            updateFormData(menu);
                                        }
                                        
                                        // 保存菜单后刷新
                                        window.refreshMenuPreview = function() {
                                            console.log("开始刷新菜单预览");
                                            // 延迟刷新，确保数据已保存
                                            setTimeout(() => {
                                                // 方式1: 通过amis API重新获取数据
                                                if (window.amis && window.amis.require) {
                                                    try {
                                                        const service = window.amis.require("service");
                                                        if (service) {
                                                            const serviceInstance = service.getComponentById("menu-preview-service");
                                                            if (serviceInstance) {
                                                                if (serviceInstance.reload) {
                                                                    console.log("通过amis reload刷新");
                                                                    serviceInstance.reload();
                                                                    return;
                                                                } else if (serviceInstance.props && serviceInstance.props.store) {
                                                                    const store = serviceInstance.props.store;
                                                                    if (store.fetchData) {
                                                                        console.log("通过store.fetchData刷新");
                                                                        store.fetchData();
                                                                        return;
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    } catch(e) {
                                                        console.log("amis刷新失败:", e);
                                                    }
                                                }
                                                
                                                // 方式2: 直接调用API获取数据并更新
                                                const serviceEl = document.querySelector("#menu-preview-service");
                                                if (serviceEl) {
                                                    const apiUrl = serviceEl.getAttribute("data-api") || serviceEl.getAttribute("data-url");
                                                    if (apiUrl) {
                                                        console.log("通过fetch API刷新:", apiUrl);
                                                        fetch(apiUrl)
                                                            .then(response => response.json())
                                                            .then(data => {
                                                                console.log("获取到新数据:", data);
                                                                if (window.initMenuPreview) {
                                                                    window.initMenuPreview(data);
                                                                }
                                                            })
                                                            .catch(error => {
                                                                console.error("获取数据失败:", error);
                                                            });
                                                    } else {
                                                        // 方式3: 触发页面重新加载
                                                        console.log("触发页面重新加载");
                                                        location.reload();
                                                    }
                                                }
                                            }, 300);
                                        }
                                        
                                        // 导出函数供amis使用
                                        window.initMenuPreview = initMenuData;
                                        
                                        // 监听Service组件数据加载
                                        function waitForServiceData() {
                                            // 尝试多种方式获取Service组件的数据
                                            const serviceEl = document.querySelector("#menu-preview-service");
                                            if (serviceEl) {
                                                // 方式1: 通过amis store
                                                if (window.amis && window.amis.require) {
                                                    try {
                                                        const service = window.amis.require("service");
                                                        if (service) {
                                                            const serviceInstance = service.getComponentById("menu-preview-service");
                                                            if (serviceInstance && serviceInstance.props && serviceInstance.props.store) {
                                                                const store = serviceInstance.props.store;
                                                                if (store.data) {
                                                                    console.log("从amis store获取数据:", store.data);
                                                                    initMenuData(store.data);
                                                                    return;
                                                                }
                                                            }
                                                        }
                                                    } catch(e) {
                                                        console.log("amis store方式失败:", e);
                                                    }
                                                }
                                                
                                                // 方式2: 通过DOM属性
                                                if (serviceEl.__store__ && serviceEl.__store__.data) {
                                                    console.log("从DOM __store__获取数据:", serviceEl.__store__.data);
                                                    initMenuData(serviceEl.__store__.data);
                                                    return;
                                                }
                                                
                                                // 方式3: 通过data属性
                                                const dataAttr = serviceEl.getAttribute("data");
                                                if (dataAttr) {
                                                    try {
                                                        const data = JSON.parse(dataAttr);
                                                        console.log("从data属性获取数据:", data);
                                                        initMenuData(data);
                                                        return;
                                                    } catch(e) {
                                                        console.log("解析data属性失败:", e);
                                                    }
                                                }
                                            }
                                            
                                            // 如果还没获取到数据，延迟重试
                                            setTimeout(waitForServiceData, 200);
                                        }
                                        
                                        // 页面加载完成后开始监听
                                        if (document.readyState === "loading") {
                                            document.addEventListener("DOMContentLoaded", function() {
                                                setTimeout(waitForServiceData, 300);
                                            });
                                        } else {
                                            setTimeout(waitForServiceData, 300);
                                        }
                                        
                                        // 也监听Service组件的自定义事件
                                        document.addEventListener("service:data:loaded", function(e) {
                                            if (e.detail && e.detail.data) {
                                                console.log("从自定义事件获取数据:", e.detail.data);
                                                initMenuData(e.detail.data);
                                            }
                                        });
                                    })();
                                </script>')
                        ])
                ]),
            amis()->Tpl()
                ->tpl('<style>
                    .phone-mockup {
                        max-width: 375px;
                        margin: 0 auto;
                        background: #fff;
                        border-radius: 20px;
                        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
                        overflow: hidden;
                    }
                    .phone-header {
                        background: #2c2c2c;
                        color: #fff;
                    }
                    .phone-status-bar {
                        display: flex;
                        justify-content: space-between;
                        padding: 5px 15px;
                        font-size: 12px;
                    }
                    .phone-nav {
                        display: flex;
                        justify-content: space-between;
                        align-items: center;
                        padding: 10px 15px;
                        border-bottom: 1px solid #333;
                    }
                    .phone-content {
                        min-height: 400px;
                        padding: 15px;
                        position: relative;
                    }
                    .menu-list {
                        list-style: none;
                        padding: 0;
                        margin: 0;
                    }
                    .submenu-item {
                        padding: 12px;
                        border-bottom: 1px solid #eee;
                        color: #007aff;
                        cursor: pointer;
                    }
                    .submenu-item:hover {
                        background: #f5f5f5;
                    }
                    .empty-submenu {
                        padding: 20px;
                        text-align: center;
                        color: #999;
                        font-size: 14px;
                    }
                    .add-submenu-btn {
                        position: absolute;
                        top: 15px;
                        right: 15px;
                    }
                    .btn-add-submenu {
                        padding: 8px 16px;
                        background: #007aff;
                        color: #fff;
                        border: none;
                        border-radius: 4px;
                        cursor: pointer;
                        font-size: 14px;
                    }
                    .btn-add-submenu:hover {
                        background: #0056b3;
                    }
                    .phone-footer-wrapper {
                        position: relative;
                        border-top: 1px solid #eee;
                        background: #fafafa;
                    }
                    .submenu-area {
                        position: absolute;
                        bottom: 100%;
                        left: 0;
                        right: 0;
                        min-height: 0;
                    }
                    .submenu-container {
                        position: absolute;
                        bottom: 0;
                        display: flex;
                        flex-direction: column;
                        align-items: center;
                    }
                    .submenu-list {
                        display: flex;
                        flex-direction: column;
                        width: 100%;
                        background: #fff;
                        border: 1px solid #ddd;
                        border-radius: 4px 4px 0 0;
                        box-shadow: 0 -2px 8px rgba(0,0,0,0.1);
                    }
                    .submenu-item {
                        padding: 10px 15px;
                        text-align: center;
                        color: #333;
                        font-size: 14px;
                        cursor: pointer;
                        border-bottom: 1px solid #eee;
                        background: #fff;
                    }
                    .submenu-item:last-child {
                        border-bottom: none;
                    }
                    .submenu-item:hover {
                        background: #f5f5f5;
                    }
                    .submenu-item.submenu-add {
                        color: #07c160;
                        font-size: 20px;
                        font-weight: bold;
                    }
                    .phone-footer {
                        display: flex;
                        justify-content: space-around;
                        align-items: center;
                        padding: 10px 0;
                        background: #fafafa;
                        min-height: 50px;
                        position: relative;
                    }
                    .footer-item {
                        flex: 1;
                        text-align: center;
                        padding: 8px 5px;
                        color: #333;
                        font-size: 14px;
                        cursor: pointer;
                        border-right: 1px solid #eee;
                        position: relative;
                    }
                    .footer-item:last-child {
                        border-right: none;
                    }
                    .footer-item.active {
                        color: #07c160;
                        font-weight: bold;
                        border-top: 2px solid #07c160;
                    }
                    .footer-item.footer-add {
                        color: #07c160;
                        font-size: 24px;
                        line-height: 1;
                    }
                    .footer-item.footer-add .add-icon {
                        display: inline-block;
                    }
                </style>')
        ];
    }

    /**
     * 菜单表单
     *
     * @return array
     */
    private function menuForm(): array
    {
        return [
            amis()->CRUD()
                ->id('menu-crud')
                ->api($this->getListGetDataPath())
                ->syncLocation(false)
                ->childrenColumnName('children')
                ->expandable([
                    'expandableOn' => 'this.children && this.children.length > 0'
                ])
                ->headerToolbar([
                    amis()->Button()
                        ->label('添加菜单')
                        ->level('primary')
                        ->actionType('dialog')
                        ->dialog([
                            'title' => '添加菜单',
                            'body' => [
                                'type' => 'form',
                                'api' => $this->getStorePath(),
                                'onEvent' => [
                                    'submitSucc' => [
                                        'actions' => [
                                            [
                                                'actionType' => 'reload',
                                                'target' => 'menu-crud'
                                            ],
                                            [
                                                'actionType' => 'reload',
                                                'target' => 'menu-preview-service'
                                            ],
                                            [
                                                'actionType' => 'custom',
                                                'script' => 'setTimeout(() => { if (window.refreshMenuPreview) { window.refreshMenuPreview(); } }, 500);'
                                            ]
                                        ]
                                    ]
                                ],
                                'body' => [
                                    amis()->InputText('name', '菜单名称')
                                        ->required(true)
                                        ->placeholder('请输入菜单名称'),
                                    amis()->Select('parent_id', '父菜单')
                                        ->options([
                                            ['label' => '一级菜单', 'value' => 0]
                                        ])
                                        ->source('system/wechat_menu/parent_options')
                                        ->value(0)
                                        ->description('选择父菜单，0表示一级菜单；选择一级菜单可添加二级菜单'),
                                    amis()->Select('type', '规则状态')
                                        ->options([
                                            ['label' => '关键字', 'value' => 'click'],
                                            ['label' => '跳转链接', 'value' => 'view'],
                                            ['label' => '小程序', 'value' => 'miniprogram']
                                        ])
                                        ->value('click')
                                        ->required(true),
                                    amis()->InputText('key', '关键字')
                                        ->visibleOn('this.type == "click"')
                                        ->required(true)
                                        ->placeholder('请输入关键字'),
                                    amis()->InputText('url', '链接地址')
                                        ->visibleOn('this.type == "view"')
                                        ->required(true)
                                        ->placeholder('请输入链接地址'),
                                    amis()->InputText('appid', '小程序AppID')
                                        ->visibleOn('this.type == "miniprogram"')
                                        ->required(true)
                                        ->placeholder('请输入小程序AppID'),
                                    amis()->InputText('pagepath', '小程序路径')
                                        ->visibleOn('this.type == "miniprogram"')
                                        ->required(true)
                                        ->placeholder('请输入小程序路径，如：pages/index/index'),
                                    amis()->InputText('miniprogram_url', '备用网址')
                                        ->visibleOn('this.type == "miniprogram"')
                                        ->required(true)
                                        ->placeholder('请输入小程序备用网址'),
                                    amis()->InputNumber('sort', '排序')
                                        ->value(0)
                                        ->min(0)
                                        ->description('数字越小越靠前'),
                                ]
                            ]
                        ])
                ])
                ->columns([
                    amis()->TableColumn('name', '菜单名称')
                        ->width(150),
                    amis()->TableColumn('type', '类型')
                        ->type('mapping')
                        ->map([
                            'click' => '关键字',
                            'view' => '跳转链接',
                            'miniprogram' => '小程序'
                        ])
                        ->width(100),
                    amis()->TableColumn('key', '关键字')
                        ->tpl('${key || "-"}'),
                    amis()->TableColumn('url', '链接')
                        ->tpl('${url || "-"}')
                        ->breakpoint('*')
                        ->popOver([
                            'trigger' => 'hover',
                            'body' => '${url}'
                        ]),
                    amis()->TableColumn('appid', '小程序AppID')
                        ->tpl('${appid || "-"}')
                        ->breakpoint('*'),
                    amis()->TableColumn('pagepath', '小程序路径')
                        ->tpl('${pagepath || "-"}')
                        ->breakpoint('*'),
                    amis()->TableColumn('sort', '排序')
                        ->width(80),
                    amis()->TableColumn('id', '操作')
                        ->type('operation')
                        ->buttons([
                            amis()->Button()
                                ->label('编辑')
                                ->level('link')
                                ->actionType('dialog')
                                ->dialog([
                                    'title' => '编辑菜单',
                                    'body' => [
                                        'type' => 'form',
                                        'api' => $this->getUpdatePath(),
                                        'initApi' => $this->getEditGetDataPath(),
                                        'onEvent' => [
                                            'submitSucc' => [
                                                'actions' => [
                                                    [
                                                        'actionType' => 'reload',
                                                        'target' => 'menu-crud'
                                                    ],
                                                    [
                                                        'actionType' => 'reload',
                                                        'target' => 'menu-preview-service'
                                                    ],
                                                    [
                                                        'actionType' => 'custom',
                                                        'script' => 'setTimeout(() => { if (window.refreshMenuPreview) { window.refreshMenuPreview(); } }, 500);'
                                                    ]
                                                ]
                                            ]
                                        ],
                                        'body' => [
                                            amis()->InputText('name', '菜单名称')->required(true),
                                            amis()->Select('parent_id', '父菜单')
                                                ->options([
                                                    ['label' => '一级菜单', 'value' => 0]
                                                ])
                                                ->source('system/wechat_menu/parent_options')
                                                ->description('选择父菜单，0表示一级菜单'),
                                            amis()->Select('type', '规则状态')
                                                ->options([
                                                    ['label' => '关键字', 'value' => 'click'],
                                                    ['label' => '跳转链接', 'value' => 'view'],
                                                    ['label' => '小程序', 'value' => 'miniprogram']
                                                ])
                                                ->value('click')
                                                ->required(true),
                                            amis()->InputText('key', '关键字')
                                                ->visibleOn('this.type == "click"')
                                                ->required(true),
                                            amis()->InputText('url', '链接地址')
                                                ->visibleOn('this.type == "view"')
                                                ->required(true),
                                            amis()->InputText('appid', '小程序AppID')
                                                ->visibleOn('this.type == "miniprogram"')
                                                ->required(true)
                                                ->placeholder('请输入小程序AppID'),
                                            amis()->InputText('pagepath', '小程序路径')
                                                ->visibleOn('this.type == "miniprogram"')
                                                ->required(true)
                                                ->placeholder('请输入小程序路径'),
                                            amis()->InputText('miniprogram_url', '备用网址')
                                                ->visibleOn('this.type == "miniprogram"')
                                                ->required(true)
                                                ->placeholder('请输入小程序备用网址'),
                                            amis()->InputNumber('sort', '排序')
                                                ->value(0)
                                                ->min(0)
                                                ->description('数字越小越靠前'),
                                        ]
                                    ]
                                ]),
                            amis()->Button()
                                ->label('删除')
                                ->level('link')
                                ->actionType('ajax')
                                ->api($this->getDeletePath())
                                ->confirmText('确定要删除吗？')
                        ])
                ])
        ];
    }

    /**
     * 获取父菜单选项
     *
     * @return Response
     */
    public function parentOptions(): Response
    {
        $menus = $this->service->getModel()
            ->where('parent_id', 0)
            ->orderBy('sort', 'asc')
            ->get();

        $options = [['label' => '一级菜单', 'value' => 0]];
        foreach ($menus as $menu) {
            $options[] = [
                'label' => $menu->name,
                'value' => $menu->id
            ];
        }

        return $this->response()->success($options);
    }

    /**
     * 发布菜单到微信
     *
     * @return Response
     */
    public function publish(): Response
    {
        $result = $this->service->publish();
        return $this->autoResponse($result, '发布');
    }
}

