<template>
    <div class="viewer">
        <aside class="sidebar">
            <ul>
                <li>
                    <router-link to="/shop/dashboard">
                        <i class="fa fa-tachometer"></i><span>概况</span>
                    </router-link>
                </li>
                <li>
                    <router-link to="/shop/order">
                        <i class="fa fa-archive"></i><span>订单</span>
                    </router-link>
                </li>
                <li>
                    <router-link to="/shop/setting">
                        <i class="fa fa-cog"></i><span>设置</span>
                    </router-link>
                </li>
            </ul>
        </aside>
        <router-view></router-view>
    </div>
</template>


