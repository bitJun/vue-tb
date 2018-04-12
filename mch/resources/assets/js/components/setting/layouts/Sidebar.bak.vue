<template>
    <div class="viewer">
        <div class="wrapper">
            <div class="inner-menu">
                <dl>
                    <dd>
                        <router-link to="/setting/shop"><i class="fa fa-cog"></i>商户设置</router-link>
                    </dd>
                    <dd>
                        <router-link to="/setting/credit"><i class="fa fa-cog"></i>魔豆设置</router-link>
                    </dd>
                </dl>
            </div>
            <router-view></router-view>
        </div>
    </div>
</template>