<template>
    <div class="viewer">
        <div class="wrapper">
            <div class="inner-menu">
                <dl>
                    <dd>
                        <router-link to="/timeline"><i class="fa fa-recycle"></i>商圈列表</router-link>
                    </dd>
                </dl>
            </div>
            <router-view></router-view>
        </div>
    </div>
</template>