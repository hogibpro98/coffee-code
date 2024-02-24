<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
</head>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100&family=Sawarabi+Mincho&display=swap');
    body {
        font-family: 'Sawarabi Mincho';
        margin: 3px;
        padding: 20px;
        background-color: #ffffff;
        width: auto;
        height: auto;
        border: 2px solid #646363;
        color: #727171;
        line-break:strict;
    }
    #half-circle {
        width: 180px;
        height: 360px;
        border-top-left-radius: 250px;
        border-bottom-left-radius: 275px;
        border: 75px solid #dddddd;
        border-right: 0;
        position: absolute;
        top: 40px;
        right: 0px;
        z-index: -1000;
    }
    .top {
        width: 100%;
        display: flex;
        border-bottom: 2px solid #646363;
    }
    .content {
        padding: 12px 3px 12px 20px;
    }
    .content-left {
        min-height: 500px;
        padding: 5px;
        width: 30%;
        background-color: #fff2cc;
        float: left;
    }
    .content-right {
        max-width: 64%;
        align-items: flex-start;
        padding: 3px 3px 3px 50px;
        float: right;
    }
    .content-right .item-advance ul {
        list-style-type: disc;
    }
    .content-right .item-advance span {
        color: #f8b500;
        font-size: 16px;
    }
    .item {
        margin-bottom: 5px;
    }
    .item ul li{
        list-style-type: none;
        margin-bottom: 5px;
    }
    .item-advance ul {
        padding-left: 16px;
        padding-bottom: 10px;
    }
    .item-advance ul li:nth-child(1) {
        color: #727171;
        font-weight: bold;
        font-size: 16px;
    }
    .item-advance p::before {
        content: "\f0c8";
        font-family: FontAwesome;
        margin-left: -1.3em;
        margin-right: 0.5em;
        width: 1.3em;
    }
    .item-advance p {
        padding-left: 20px;
        font-size: 13px;
        margin: 0;
    }
    .top-left {
        font-family: 'Noto Sans JP', sans-serif;
        font-size: 16px;
        font-weight: 500;
    }
    .top-left span {
        font-size: 28px;
        color: #f8b500;
        font-weight: 500;
    }
    .top-right {
        position: absolute;
        font-family: 'Noto Sans JP', sans-serif;
        font-size: 20px;
        top: 34px;
        right: 26px;
        font-weight: 500;
    }
    .top-right span{
        color: #f8b500;
        font-size: 16px;
        font-weight: 500;
    }
    img {
        width: 20%;
    }
    ul {
        margin: 0;
        padding-left: 0;
        line-height: 10px;
    }
    ul li {
        font-size: 13px;
    }
    ul li:nth-child(1){
        color: #f8b500;
        font-size: 16px !important;
    }
</style>
<body>
<div class="top">
    <div class="top-left">
        <span style="font-weight: 500">P</span>rofessional<span>M</span>ember <span>R</span>esume
    </div>
    <div class="top-right">
        PR<span style="display: inline-block; margin-bottom: -8px;">O</span>ATTEND
        <div id="half-circle"></div>
    </div>
</div>
<div class="content">
    <div class="content-left">
        <div class="item">
            <ul>
                <li>氏名</li>
                <li>{{ $name_kanji }}</li>
            </ul>
        </div>
        <div class="item">
            <ul>
                <li>保有資格</li>
                @foreach ($owned_qualifications as $index => $owned_qualification)
                    @if($index != 0 && $index%2 == 0) <br/>@endif
                    <span style="display:inline-block;margin-right: 20px; font-size: 13px; padding-bottom: 5px">{{ $owned_qualification['qualifications'] }}</span>
                @endforeach
            </ul>
        </div>
        <div class="item">
            <ul>
                <li>年齢</li>
                <li>{{ $birthdate }}</li>
            </ul>
        </div>
        <div class="item">
            <ul>
                <li>アドバイザリー経験年数</li>
                <li>{{ $advisory_experience_years }}</li>
            </ul>
        </div>
        <div class="item">
            <ul>
                <li>専門性</li>
                @foreach ($field_type_names as $name)
                    <li>{{ $name }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    <div class="content-right">
        <div class="item">
            <ul>
                <li>要約</li>
                <li style="word-break:break-all;word-wrap:break-word;">{{ $experience }}</li>
            </ul>
        </div>
        <div class="item-advance">
            <span>主な経験-会社名</span>
            @foreach ($career_histories as $history)
                <p>{{ $history->office_name }} ({{ $history->find_work }}　{{ $history->retirement }})</p>
                <ul style="word-break:break-all;word-wrap:break-word;font-size: 13px;line-height: 13px">
                    @if($history->free_entry)
{{--                        <li>--}}
                            {{$history->free_entry}}
{{--                        </li>--}}
                    @endif
                </ul>
            @endforeach
        </div>
    </div>

</div>

</body>
</html>
