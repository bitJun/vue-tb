<template>
    <div class="wrapper">
        <div class="inner-menu">
            <dl>
                <dt>
                    订单管理
                </dt>
                <dd>
                    <router-link to="/shop/order">
                        <i class="fa fa-list"></i>订单列表
                    </router-link>
                </dd>
            </dl>
        </div>
        <div class="container">
            <router-view></router-view>
        </div>
    </div>
</template>