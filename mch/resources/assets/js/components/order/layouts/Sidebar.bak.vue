<template>
    <div class="viewer">
        <div class="wrapper">
            <div class="inner-menu">
                <dl>
                    <dd>
                        <router-link to="/order"><i class="fa fa-list"></i>订单列表</router-link>
                    </dd>
                </dl>
            </div>
            <router-view></router-view>
        </div>
    </div>
</template>