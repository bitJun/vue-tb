<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>店铺审核</title>
    <script src="/js/jquery-3.2.1.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <link href="/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <style type="text/css">
        body,div,h1,h2,h3,h4,h5,h6,p,input{padding:0; margin:0;}
        body{ background-color:#111;  max-width:640px; margin:0 auto}
        .regbox{border-bottom:1px solid #2e2e2e; border-top:1px solid #2e2e2e; padding:0 20px;}
        .inputbox{line-height:44px; border-bottom:1px solid #2e2e2e;}
        .txt{font-size:14px; color:#d9af6d; width: 80px;display: inline-block;}
        .text{background:none; border:none; border: 1px solid grey; font-size:16px; color:#d9af6d; margin-top: 8px;display: inline-block; line-height:42px; height:42px; }
        .submit{margin:20px; width: 90%;
            border:0px; line-height:44px; height:44px; text-align:center; display:block; border-radius:3px; background-color:#d9af6d; font-size:18px; color:#000; text-decoration:none;}
        .span{color: white; padding-left: 10px; display: inline-block; }
    </style>
</head>
<body>
<div class="content">
    <h3>魔店审核</h3>
    <div class="regbox">
        <input name="shop_apply_id" type="hidden" value="{{$data['id']}}">
        <div class="inputbox">
            <span class="txt">店铺名称</span>
            <span class="span">{{$data['company']}}</span>
        </div>
        <div class="inputbox">
            <span class="txt">联系人</span>
            <span class="span">{{$data['contact']}}</span>
        </div>
        <div class="inputbox">
            <span class="txt">联系方式</span>
            <span class="span">{{$data['mobile']}}</span>
        </div>
        <div class="inputbox">
            <span class="txt">所属行业</span>
            <span class="span">{{$data['tag_str']}}</span>
        </div>
        <div class="inputbox">
            <span class="txt">省/市/区</span>
            <span class="span">{{$data['province_str']}}{{$data['city_str']}}{{$data['district_str']}}</span>
        </div>
        <div class="inputbox">
            <span class="txt">详细地址</span>
            <span class="span">{{$data['address']}}</span>
        </div>
        <div class="inputbox">
            <span class="txt">店铺LOGO</span>
            <span class="span"><img src="{{$data['logo_path']}}" height="100" width="200"></span>
        </div>
        <div class="inputbox">
            <span class="txt">营业执照</span>
            <span class="span"><img src="{{$data['num_license_path']}}" height="100" width="200"></span>
        </div>
        <div class="inputbox">
            <span class="txt">店铺图片</span>
            <span class="span">
            @if($data['imgs_path'])
                @foreach($data['imgs_path'] as $key=>$item)
                    <img src="{{$item}}" height="100" width="200">
                @endforeach
            @endif
            </span>
        </div>
        <div class="inputbox">
            <span class="txt">手机号</span>
            <input type="text" name="mobile" placeholder="请输入手机号，可作为登录账号" autocomplete="off"  style="width:400px; height:30px;color:#666;" class="text">
        </div>
        <div class="inputbox">
            <span class="txt"></span>
            <button class="submit">审核</button>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {

        //提交
        $('.submit').click(function () {
            var shop_apply_id = $("input[name='shop_apply_id']").val();
            var mobile = $("input[name='mobile']").val();

            if(mobile == "")
            {
                alert('请填写手机号，作为登录账号');
                return false;
            }

            var data = {
                mobile : mobile
            };
            $.ajax({
                type: "POST",
                url: "/api/audit_shop/"+shop_apply_id+".json",
                data: data,
                dataType: "json",
                success: function(result){
                    alert('添加成功');
                    window.location.href='/shops';
                },
                error: function (result) {
                    console.log(result);
                    alert(result.responseJSON.message);
                }
            });
        });
    });
</script>
</body>
</html>
