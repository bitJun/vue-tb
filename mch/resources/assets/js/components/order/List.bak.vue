<template>
    <div class="container">
        <div class="list-filter-form clearfix">
            <form role="form">
                <div class="form-group">
                    <label class="control-label">订单号：</label>
                    <div class="controls">
                        <input class="form-control input-sm" type="text" name="order_sn" placeholder="订单号">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">手机号码：</label>
                    <div class="controls">
                        <input class="form-control input-sm" type="text" name="mobile" placeholder="手机号码">
                    </div>
                </div>
                <div class="form-group">
                    <button type="button" class="btn btn-primary btn-sm"><i class="fa fa-search"></i> 筛选</button>
                </div>
            </form>
        </div>

        <table class="table table-hover table-middle">
            <thead>
            <tr>
                <th>手机号</th>
                <th>昵称</th>
                <th>订单号</th>
                <th>订单金额</th>
                <th>订单类型</th>
                <th>创建时间</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>13968154713</td>
                <td>道儿瑟老</td>
                <td>E2017080515019130547282891</td>
                <td>100</td>
                <td>消费</td>
                <td>2017-08-07 11:11:11</td>
                <td>
                    <a placement="left">操作</a>
                    <span class="delimiter">|</span>
                    <a placement="left">操作</a>
                </td>
            </tr>
            <tr>
                <td>13968154713</td>
                <td>道儿瑟老</td>
                <td>E2017080515019130547282891</td>
                <td>100</td>
                <td>充值</td>
                <td>2017-08-07 11:11:11</td>
                <td>
                    <a placement="left">操作</a>
                    <span class="delimiter">|</span>
                    <a placement="left">操作</a>
                </td>
            </tr>
            <tr>
                <td>13968154713</td>
                <td>道儿瑟老</td>
                <td>E2017080515019130547282891</td>
                <td>100</td>
                <td>消费</td>
                <td>2017-08-07 11:11:11</td>
                <td>
                    <a placement="left">操作</a>
                    <span class="delimiter">|</span>
                    <a placement="left">操作</a>
                </td>
            </tr>
            </tbody>
        </table>

        <div class="well well-lg text-center">
            <i class="fa fa-file-o"></i> 没有相关数据
        </div>

        <div class="list-footer clearfix">
            <pagination class="pull-right"
                        :page-count="20"
                        :page-range="10"
                        :container-class="'pagination'"
                        :click-handler="clickCallback"
            ></pagination>
        </div>
    </div>
</template>
<script>
    import Pagination from '../../components/common/Pagination.vue';

    export default {
        components: {
            Pagination
        },
        mounted () {

        },
        methods:{
            clickCallback: function(pageNum) {
                console.log(pageNum)
            }
        }
    }
</script>