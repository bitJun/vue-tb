<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, user-scalable=no">
        <title>付款成功</title>
        <link rel="stylesheet" href="css/app.css">
    </head>
    <body>
        <div class="h5-container page-paid">
            <div class="paid-info">
                <i class="if if-success"></i>
                <span>付款成功</span>
                <div class="paid-amount">¥520.00</div>
            </div>
            <ul class="trade-detail">
                <li>
                    <label>交易时间</label>
                    <div>2017-09-21 18:05</div>
                </li>
                <li>
                    <label>交易时间</label>
                    <div>2017-09-21 18:05</div>
                </li>
                <li>
                    <label>交易时间</label>
                    <div>2017-09-21 18:05</div>
                </li>
            </ul>
            <div class="send-credit">
                <span>恭喜您获得<i>100</i>魔豆，下次消费可直接抵用。</span>
                <div class="receive-from">
                    <input type="text" placeholder="输入手机号码">
                    <button class="btn">领取</button>
                </div>
            </div>
            <div class="send-credit">
                <span>恭喜您获得<i>100</i>魔豆，下次消费可直接抵用。已经放入魔店账户(15257113820)<a href="#">去看看>></a></span>
            </div>
        </div>


        <div class="popup verify-member" style="bottom: 200px;">
            <div class="close-popup if if-close" data-popup=".popup-sku"></div>
            <div class="popup-title">验证手机</div>
            <form class="form-horizontal" role="form" onsubmit="return false;">
                <div class="form-group captcha">
                    <label>图片验证码</label>
                    <input type="text" name="captcha" class="form-item consignee-name" placeholder="请填写右侧验证码">
                    <img src="http://store.md.com/sms/captcha">
                </div>
            </form>
            <div class="popup-options">
                <button type="button" class="btn btn-block btn-primary">发送验证码</button>
            </div>
        </div>

        <div class="popup verify-member">
            <div class="close-popup if if-close" data-popup=".popup-sku"></div>
            <div class="popup-title">验证手机</div>
            <form class="form-horizontal" role="form" onsubmit="return false;">
                <div class="form-group verify-code">
                    <label>短信验证码</label>
                    <input type="text" name="verify-code" class="form-item consignee-name" placeholder="请填写短信验证码">
                    <button type="button" class="btn-sm btn-warning-o">重新发送</button>
                </div>
            </form>
            <div class="popup-options">
                <button type="button" class="btn btn-block btn-primary">确认</button>
            </div>
        </div>

    </body>
</html>
