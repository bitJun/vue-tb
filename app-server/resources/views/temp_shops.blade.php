<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>魔客</title>
    <script src="/js/jquery-3.2.1.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <link href="/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <style type="text/css">
        body,div,h1,h2,h3,h4,h5,h6,p,input{padding:0; margin:0;}
        h3{color: #8a6d3b}
        body{ background-color:#111;  max-width:900px; margin:0 auto}
        .regbox{border-bottom:1px solid #2e2e2e; border-top:1px solid #2e2e2e; padding:0 20px;}
        .content{width: 900px;border: 1px bisque dashed;}
        table{width: 100%;}
        table td{line-height: 20px; padding: 5px;}
        .head{text-align: center; font-size: 16px; color: white; border-bottom: solid 1px darkgray;}
        .tr{font-size: 14px; color: #000000; border-bottom: solid 1px darkgray;}
        .bgtr{ background-color: #CCCCCC;}
        .bgtr2{ background-color: #dff0d8;}
        .ctr{text-align: center;}
        .ctr img{width: 100px; height: 80px;}
    </style>
</head>
<body>
<div class="content">
    <h3>待审核魔客列表</h3>
    <div class="regbox">
        <table>
            <header>
                <td class="head">店铺名称</td>
                <td class="head">联系人</td>
                <td class="head">联系电话</td>
                <td class="head">营业执照</td>
                <td class="head">申请时间</td>
                <td class="head">操作</td>
            </header>
            @foreach($data as $key=>$item)
            <tr class="tr @if($key % 2 == 0) bgtr @else bgtr2 @endif">
                <td>{{$item['shop_name']}}</td>
                <td class="ctr">{{$item['contact']}}</td>
                <td class="ctr">{{$item['mobile']}}</td>
                <td class="ctr"><img src="{{$item['num_license']}}" alt=""></td>
                <td class="ctr">{{$item['created_at']}}</td>
                <td class="ctr"><a href="/shop/{{$item['id']}}">审核</a></td>
            </tr>
            @endforeach
        </table>
    </div>
</div>

</body>
</html>