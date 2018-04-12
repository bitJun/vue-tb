<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, user-scalable=no">
        <title>向魔店示范店付款</title>
        <link rel="stylesheet" href="css/app.css">
    </head>
    <body>
        <div class="h5-container">
            <div class="pay-form">
                <div class="store-info">
                    <div class="logo"><img src="rs/1.png"></div>
                    <span>魔店示范店</span>
                </div>
                <div class="pay-info">
                    <div class="pay-info-amount">
                        <span>付款金额</span>
                        <div class="amount-input">
                            <i class="currency">¥</i>
                            <span>520</span>
                            <i class="cursor"></i>
                        </div>
                    </div>
                </div>
                <div class="pay-remark">
                    <input type="text" placeholder="添加备注">
                </div>
                <div class="copyright">
                    <a class="if if-modian" href="javascript:;">魔店提供技术支持</a>
                </div>
            </div>
            <div class="keyboard">
                <ul class="keyboard-nums">
                    <li>7</li>
                    <li>8</li>
                    <li>9</li>
                    <li>4</li>
                    <li>5</li>
                    <li>6</li>
                    <li>1</li>
                    <li>2</li>
                    <li>3</li>
                    <li>00</li>
                    <li>0</li>
                    <li>.</li>
                </ul>
                <ul class="keyboard-btns">
                    <li class="if if-del"></li>
                    <li class="if if-plus"></li>
                    <li class="btn-pay">立即支付</li>
                </ul>
            </div>
        </div>
    </body>
</html>
