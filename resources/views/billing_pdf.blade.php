<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    
    <title>Document</title>
    <style>
        @font-face{
            font-family: ipag;
            font-style: normal;
            font-weight: normal;
            src:url('{{ storage_path('fonts/ipag.ttf')}}');
        }
        .title{
            font-size: 36px;
            margin-bottom: 30px;
        }
        body{
            font-family: ipag;
            text-align: center;
            width: 800px;
            margin: auto;
        }

        table{
            width: 100%;
            table-layout: fixed;
        }
        table td{
            vertical-align: top;
        }
        .under{
            text-align: left;
            font-size: 120%;
            margin-right: 100px;
            border-bottom: solid 1px;
            padding-left: 10px;
        }
        .total{
            font-size: 36px;
            width: 500px;
            background-color: #DCDCDC;
            text-align: center;
            margin: auto;
        }
        .text{
            width: 500px;
            margin: auto;
            margin-top: 20px;
        }
        .text div{
            text-align: left;
        }
        .address{
            text-align: left;
            /* width: 200px; */
            float: right;
            /* margin-right: 40px; */
        }
        .amount-table{
            width: 200px;

        }
        .amount{
            text-align: right;
        }
        .is-small{
            font-size: 80%;
        }
        .is-right{
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="title">領収書</div>
    <table>
        <tr>
            <td><div class="under">{{ @$data['member']['name_kanji'] }}<span style="float: right;">様</span></div></td>
            <td>
                <div class="is-right">
                    <span>No.</span>{{ @$data['billing_number']}}
                </div>
                <div class="is-right">
                    <span>発行日：</span>{{ \Carbon\Carbon::now()->format('Y/m/d') }}
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div class="total" style="margin-top: 50px;">
                    <span>￥</span>
                    <span>{{number_format(@$data['total'])}}</span>
                    <span>（税込）</span>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div class="text" style="margin-bottom: 50px;">
                    <div class="is-small">但　<span>{{ @$data['billingDetails'][0]['name'] }}他</span></div>
                    <div class="is-small">上記正に領収いたしました。</div>
                </div>
            </td>
        </tr>
        <tr>
            <td class="text">
                <div>
                    <table class="amount-table">
                        <tr>
                            <td>税抜金額：</td>
                            <td class="amount">￥{{number_format(@$data['subtotal'])}}</td>
                        </tr>
                        <tr>
                            <td>消費税額：</td>
                            <td class="amount">￥{{number_format(@$data['tax'])}}</td>
                        </tr>
                    </table>
                </div>
            </td>
            <td>
                <div class="address">
                    <div>株式会社audience（ProAttend運営会社）</div>
                    <div>〒100-0004</div>
                    <div>東京都千代田区大手町1-6-1大手町ビル8階</div>
                    <div>ProAttend Club　運営担当者</div>
                    <div>お問い合わせ先：info@proattend.jp</div>
                </div>
            </td>
        </tr>
    </table>
</body>
</html>
