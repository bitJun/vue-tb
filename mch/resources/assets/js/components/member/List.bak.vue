<template>
    <div class="container">
        <div class="list-filter-form clearfix">
            <form role="form">
                <div class="form-group">
                    <label class="control-label">手机号码：</label>
                    <div class="controls">
                        <input class="form-control input-sm" type="text" name="mobile" placeholder="手机号码">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">等级：</label>
                    <div class="controls">
                        <select class="form-control input-sm">
                            <option value="">请选择</option>
                            <option value="0">高级会员</option>
                            <option value="1">股东合伙人</option>
                        </select>
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
                <th style="width: 30px;"></th>
                <th>头像</th>
                <th>昵称</th>
                <th>手机号</th>
                <th>等级</th>
                <th>魔豆</th>
                <th>余额</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>
                    <div class="checkbox">
                        <input type="checkbox" id="checkbox8"/>
                    </div>
                </td>
                <td><img src="http://wx.qlogo.cn/mmopen/cT1uJvb9VBfuiaTs2QHshicMia9IP1D866DsAjoYKFTuibTGwWC05Ygtziad23lqeu6zgrXFK6gwVnVibiaxKImpcLQuW59F3LFlHaL/0" class="size34"  style="border-radius: 2px;"></td>
                <td>道儿瑟老</td>
                <td>13968154713</td>
                <td>股东合伙人</td>
                <td><a href="" >100</a></td>
                <td><a href="" >1000</a></td>
                <td>
                    <a placement="left">操作</a>
                    <span class="delimiter">|</span>
                    <a placement="left">操作</a>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="checkbox">
                        <input type="checkbox" id="checkbox8"/>
                    </div>
                </td>
                <td><img src="http://wx.qlogo.cn/mmopen/cT1uJvb9VBfuiaTs2QHshicMia9IP1D866DsAjoYKFTuibTGwWC05Ygtziad23lqeu6zgrXFK6gwVnVibiaxKImpcLQuW59F3LFlHaL/0" class="size34"  style="border-radius: 2px;"></td>
                <td>道儿瑟老</td>
                <td>13968154713</td>
                <td>股东合伙人</td>
                <td><a href="" >100</a></td>
                <td><a href="" >1000</a></td>
                <td>
                    <a placement="left">操作</a>
                    <span class="delimiter">|</span>
                    <a placement="left">操作</a>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="checkbox">
                        <input type="checkbox" id="checkbox8"/>
                    </div>
                </td>
                <td><img src="http://wx.qlogo.cn/mmopen/cT1uJvb9VBfuiaTs2QHshicMia9IP1D866DsAjoYKFTuibTGwWC05Ygtziad23lqeu6zgrXFK6gwVnVibiaxKImpcLQuW59F3LFlHaL/0" class="size34"  style="border-radius: 2px;"></td>
                <td>道儿瑟老</td>
                <td>13968154713</td>
                <td>股东合伙人</td>
                <td><a href="" >100</a></td>
                <td><a href="" >1000</a></td>
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
            <div class="pull-left">
                <div class="checkbox checkbox-all">
                    <label for="checkboxall"><input type="checkbox" id="checkboxall"/>全选</label>
                </div>
            </div>
            <div class="pull-left">
                <button type="button" class="btn btn-default btn-sm" placement="top">操作</button>
                <button type="button" class="btn btn-default btn-sm" placement="top">操作</button>
            </div>
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