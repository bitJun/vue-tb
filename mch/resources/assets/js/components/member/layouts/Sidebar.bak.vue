<template>
    <div class="viewer">
        <div class="wrapper">
            <div class="inner-menu">
                <dl>
<!--                    <dt>
                        会员管理
                    </dt>
                    <dd>
                        <router-link to="/member/dashboard"><i class="fa fa-line-chart"></i>会员概况</router-link>
                    </dd>-->
                    <dd>
                        <router-link to="/member"><i class="fa fa-users"></i>会员列表</router-link>
                    </dd>
                </dl>
            </div>
            <router-view></router-view>
        </div>
    </div>
</template>