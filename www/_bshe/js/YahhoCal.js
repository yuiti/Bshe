/**
 *  Yahho Calendar - Japanized Popup Calendar
 *  @see       http://0-oo.net/sbox/javascript/yahho-calendar
 *  @version   0.4.1 beta 1
 *  @copyright 2008-2009 dgbadmin@gmail.com
 *  @license   http://0-oo.net/pryn/MIT_license.txt (The MIT license)
 *
 *  See also
 *  @see http://developer.yahoo.com/yui/calendar/
 *  @see http://developer.yahoo.com/yui/docs/YAHOO.widget.Calendar.html
 */

var YahhoCal = {
    /**
     *  loadYUI()で読み込むYUIのURL
     *  @see http://developer.yahoo.com/yui/articles/hosting/
     *  @see http://code.google.com/intl/ja/apis/ajaxlibs/documentation/#yui
     */
    YUI_URL: {
        SERVER: location.protocol + "//ajax.googleapis.com/ajax/libs/yui/",
        VERSION: "2.7.0",
        DIR: "/build/"
    },
    
    /** カレンダーの見た目の設定 */
    CAL_STYLE: {
        //幅（IE6で縮まるのを防ぐ）
        "": "width: 13em",
        //日曜日
        "td.wd0 a.selector": "background-color: #fcf",
        //土曜日
        "td.wd6 a.selector": "background-color: #cff",
        //祝日（要 GCalHolidays）
        "td.holiday a.selector": "background-color: #f9f",
        //今日
        "td.today a.selector": "",
        //選択された日
        "td.selected a.selector": "background-color: #0f0",
        //選択可能な日付の範囲外の日（今日が黒くなるのを防ぐ）
        "td.previous": "background-color: #fff"
    },
    
    /** 地域（YUI_CAL_CONFIGのどれを使うかの指定） */
    locale: "ja",

    /** YUIカレンダー設定 */
    YUI_CAL_CONFIG: {
        //英語
        en: {},
        //日本語
        ja: {
            my_label_year_position: 1,
            my_label_year_suffix: "年 ",
            my_label_month_suffix: "月",
            months_long: ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12"],
            weekdays_short: ["日", "月", "火", "水", "木", "金", "土"]
        },
        //韓国語
        ko: {
            my_label_year_position: 1,
            my_label_year_suffix: "&#xb144; ",
            my_label_month_suffix: "&#xc6d4;",
            months_long: ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12"],
            weekdays_short: [
                "&#xc77c;", "&#xc6d4;", "&#xd654;", "&#xc218;", "&#xbaa9;",
                "&#xae08;", "&#xd1a0;"
            ]
        },
        //中国語
        zh: {
            my_label_year_position: 1,
            my_label_year_suffix: "年 ",
            my_label_month_suffix: "月",
            months_long: ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12"],
            weekdays_short: ["日", "一", "二", "三", "四", "五", "六"]
        },
        //スペイン語
        es: {
            months_long: [
                "enero", "febrero", "marzo", "abril", "mayo", "junio",
                "julio", "agosto", "septiembre", "octubre", "noviembre", "diciembre"
            ],
            weekdays_short: ["do", "lu", "ma", "mi", "ju", "vi", "sa"]
        },
        //ポルトガル語
        pt: {
            months_long: [
                "Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho",
                "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"
            ],
            weekdays_short: ["Do", "Se", "Te", "Qu", "Qu", "Se", "Sa"]
        }
    },
    
    //テキストボックスでの日付フォーマット
    format: {
        delimiter: '/', //区切り文字
        zeroPad: false  //ゼロ埋めするかどうか
    },
    
    //入力要素をラップするアダプタ
    adapters: {}
};
/**
 *  カレンダーを表示する
 *  @param  String  inputId 入力要素のid or 年の入力要素のid
 *  @param  String  monthId (optional) 月の入力要素のid
 *  @param  String  dateId  (optional) 日の入力要素のid
 *  @return Boolean カレンダーの表示ができたかどうか
 */
YahhoCal.render = function(inputId, monthId, dateId) {
    if (!window.YAHOO || !YAHOO.widget.Calendar) {  //YUIを読み込んでいない場合
        return false;
    }
    
    var currentId = (this.currentId = dateId || inputId);   //入力要素を特定するID

    //アダプタを取得
    if (!this.adapters[currentId]) {
        this.adapters[currentId] = this.createAdapter(inputId, monthId, dateId);
    }
    var adapter = this.adapters[currentId];

    var cal = this.cal;
    if (cal) {  //再表示の場合
        cal.hide();
        YAHOO.util.Dom.insertAfter(this.place, currentId);
        cal.show();
    } else {    //初めて表示する場合
        this.setStyle();
        cal = (this.cal = this.createCalendar(currentId));
    }

    //入力済みの日付を取得
    var val = adapter.getDate();
    var y = val[0], m = val[1], d = val[2];
    var shown = new Date(y, m - 1, d);

    //表示設定
    var pagedate = "", selected = "";
    if ((shown.getFullYear() == y && shown.getMonth() + 1 == m && shown.getDate() == d)) {
        //日付として正しい場合
        pagedate = m + "/" + y;
        selected = m + "/" + d + "/" + y;
    } else {
        shown = new Date();
    }
    cal.cfg.setProperty("pagedate", pagedate);  //表示する年月
    cal.cfg.setProperty("selected", selected);  //選択状態の日付

    cal.render();

    this.showHolidays(shown);
    
    //カレンダーの表示が終わってからクリックイベントの捕捉を始める
    setTimeout(function() {
        YAHOO.util.Event.addListener(document, "click", YahhoCal.clickListener);
    }, 1);
    
    return true;
};
/**
 *  入力要素とカレンダーとのポリモフィズムなアダプタを生成する
 */
YahhoCal.createAdapter = function(inputId, monthId, dateId) {
    var adapter = {};

    if (!monthId) {     //テキストボックス1つの場合（YYYY/M/D）
        var ymd = document.getElementById(inputId);
        adapter.getDate = function() { return ymd.value.split("/"); };
        adapter.setDate = function(y, m, d) {
            var deli = YahhoCal.format.delimiter;
            if (YahhoCal.format.padZero) {
                m = ("0" + m).slice(-2);
                d = ("0" + d).slice(-2);
            }
            ymd.value = y + deli + m + deli + d;
        };
        return adapter;
    }
    
    //年・月・日が分かれている場合
    var ey = document.getElementById(inputId);
    var em = document.getElementById(monthId);
    var ed = document.getElementById(dateId);

    if (ey.tagName == "INPUT") {    //テキストボックスの場合
        adapter.getDate = function() { return [ey.value, em.value, ed.value]; };
        adapter.setDate = function(y, m, d) { ey.value = y; em.value = m; ed.value = d; };
        return adapter;
    }
    
    //選択リストの場合
    var getNumber = function(opt) { return (opt.value || opt.text).replace(/^0+/, ""); };
    var get = function(sel) { return getNumber(sel.options[sel.selectedIndex]); };
    var set = function(sel, value) {
        for (var i = 0, len = sel.length; i < len; i++) {
            if (getNumber(sel.options[i]) == value) {
                sel.options[i].selected = true;
                return;
            }
        }
    };
    adapter.getDate = function() { return [get(ey), get(em), get(ed)]; };
    adapter.setDate = function(y, m, d) { set(ey, y); set(em, m); set(ed, d); };
    return adapter;
};
/**
 *  styleを設定する
 */
YahhoCal.setStyle = function() {
    var css = "";
    for (var target in this.CAL_STYLE) {
        css += ".yui-skin-sam .yui-calcontainer .yui-calendar " + target;
        css += "{" + this.CAL_STYLE[target] + "}";
    }
    
    var tmp = document.createElement("div");
    tmp.innerHTML = 'dummy<style type="text/css">' + css + "</style>";

    document.getElementsByTagName("head")[0].appendChild(tmp.lastChild);
};
/**
 *  カレンダーを生成する
 */
YahhoCal.createCalendar = function(insertId) {
    var yDom = YAHOO.util.Dom;
    
    //YUI skinを適用
    yDom.addClass(document.body, "yui-skin-sam");

    //カレンダーの場所を作る
    var place = (this.place = document.createElement("div"));
    yDom.setStyle(place, "position", "absolute");
    yDom.setStyle(place, "z-index", 1);
    yDom.insertAfter(place, insertId);

    //カレンダー生成
    var config = this.YUI_CAL_CONFIG[this.locale];
    config.close = true;
    config.hide_blank_weeks = true;
    var cal = new YAHOO.widget.Calendar(place, config);

    //日付を選択された時のイベント
    cal.selectEvent.subscribe(function(eventName, selectedDate) {
        var date = selectedDate[0][0];
        YahhoCal.adapters[YahhoCal.currentId].setDate(date[0], date[1], date[2]);
        cal.hide();
    });

    //月を移動した時のイベント
    cal.changePageEvent.subscribe(function() {
        YahhoCal.showHolidays(cal.cfg.getProperty("pagedate"));
    });

    //閉じた時のイベント
    cal.hideEvent.subscribe(function() {
        YAHOO.util.Event.removeListener(document, "click", YahhoCal.clickListener);
    });
    
    return cal;
};
/**
 *  祝日を表示する
 */
YahhoCal.showHolidays = function(target) {
    if (!window.GCalHolidays) {     //GCalHolidays.jsを読み込んでいない場合
        return;
    }
    GCalHolidays.get(this.setHolidays, target.getFullYear(), target.getMonth() + 1);
};
/**
 *  祝日表示を設定する
 */
YahhoCal.setHolidays = function(holidays) {
    if (holidays.length === 0) {
        return;
    }
    
    var yDom = YAHOO.util.Dom;
    
    //取得した年月をまだ表示しているかチェック
    var first = holidays[0];
    var table = yDom.getElementsByClassName("y" + first.year, "table", this.place)[0];
    var tbody = yDom.getElementsByClassName("m" + first.month, "tbody", table)[0];
    if (!table || !tbody) {
        return;
    }

    //祝日設定
    for (var i in holidays) {
        var h = holidays[i];
        var td = yDom.getElementsByClassName("d" + h.date, "td", tbody)[0];
        yDom.addClass(td, "holiday");
        td.title = h.title;     //マウスオーバーで祝日名を表示
    }
};
/**
 *  カレンダーの外をクリックされたらカレンダーを閉じる
 */
YahhoCal.clickListener = function(clickedPoint) {
    var xy = YAHOO.util.Event.getXY(clickedPoint);
    var x = xy[0], y = xy[1];
    var r = YAHOO.util.Dom.getRegion(YahhoCal.cal.containerId);
    if (x < r.left || x > r.right || y < r.top || y > r.bottom) {
        YahhoCal.cal.hide();
    }
};
/**
 *  必要なYUIのJavaScriptとCSSを読み込む
 *  @param  String  yuiBase (optional) YUIのベースとなるURL
 */
YahhoCal.loadYUI = function(yuiBase) {
    if (!yuiBase) {
        yuiBase = this.YUI_URL.SERVER + this.YUI_URL.VERSION + this.YUI_URL.DIR;
    }

    //YUI Loaderをload
    var script = document.createElement("script");
    script.type = "text/javascript";
    script.src = yuiBase + "yuiloader-dom-event/yuiloader-dom-event.js";
    document.getElementsByTagName("head")[0].appendChild(script);
    
    //YUI Loaderがloadされるまで待つ
    var limit = 5000, interval = 50, time = 0;
    var loadedId = setInterval(function(){
        if (window.YAHOO) {
            clearInterval(loadedId);
            //YUI Calendarをload
            (new YAHOO.util.YUILoader({ base: yuiBase, require: ["calendar"] })).insert();
        } else if ((time += interval) > limit) {    //タイムアウト
            clearInterval(loadedId);
        }
    }, interval);
};
/**
 *  週の初めを月曜日にする
 */
YahhoCal.setMondayAs1st = function() {
    this.YUI_CAL_CONFIG[this.locale].start_weekday = 1;
};
/**
 *  選択可能な最初の日を指定する
 *  @param  integer y   西暦4桁
 *  @param  integer m   1～12月
 *  @param  integer d
 */
YahhoCal.setMinDate = function(y, m, d) {
    var date = m + "/" + d + "/" + y;
    if (this.cal) {
        this.cal.configMinDate(null, [date]);
    } else {
        this.YUI_CAL_CONFIG[this.locale].mindate = date;
    }
};
/**
 *  選択可能な最後の日を指定する
 *  @param  integer y   西暦4桁
 *  @param  integer m   1～12月
 *  @param  integer d
 */
YahhoCal.setMaxDate = function(y, m, d) {
    var date = m + "/" + d + "/" + y;
    if (this.cal) {
        this.cal.configMaxDate(null, [date]);
    } else {
        this.YUI_CAL_CONFIG[this.locale].maxdate = date;
    }
};
