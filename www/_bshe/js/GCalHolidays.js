/**
 *  GCalHolidays
 *  @see       http://0-oo.net/sbox/javascript/google-calendar-holidays
 *  @version   0.1.3 beta 2
 *  @copyright 2008 dgbadmin@gmail.com
 *  @license   http://0-oo.net/pryn/MIT_license.txt (The MIT license)
 */
var GCalHolidays = {
    userId: "japanese__ja@holiday.calendar.google.com",                      //Google公式版
    //userId: "japanese@holiday.calendar.google.com",                        //もう1つのID
    //userId: "outid3el0qkcrsuf89fltf7a4qbacgt9@import.calendar.google.com", //mozilla.org版
    visibility: "public",
    projection: "full-noattendees",
    maxResults: 30,
    holidays: {}
};
/**
 *  祝日を取得する
 *  @param  Function    callback    データ取得時に呼び出されるfunction
 *  @param  Number      year        (optional) 年（指定しなければ今年）
 *  @param  Number      month       (optional) 月（1～12 指定しなければ1年の全て）
 */
GCalHolidays.get = function(callback, year, month) {
    //日付範囲
    var padZero = function(value) { return ("0" + value).slice(-2); };
    var y = year || new Date().getFullYear();
    var start = [y, padZero(month || 1), "01"].join("-");
    var m = month || 12;
    var end = [y, padZero(m), padZero(new Date(y, m, 0).getDate())].join("-");
    
    //取得済みの場合はそれを使う
    var cache = this.holidays[start + ".." + end];
    if (cache) {
        callback(cache);
        return;
    }

    this.userCallback = callback;
    
    //URL作成
    var url = location.protocol + "//www.google.com/calendar/feeds/";
    url += this.userId + "/" + this.visibility + "/" + this.projection;
    url += "?alt=json-in-script&callback=GCalHolidays.decode";
    url += "&max-results=" + this.maxResults + "&start-min=" + start + "&start-max=" + end;

    //scriptタグ生成
    var script = document.createElement("script");
    script.type = "text/javascript";
    script.src = url;
    script.charset = "UTF-8";
    document.body.appendChild(script);
};
/**
 *  JSONPによりGoogle Calendar APIから呼び出されるfunction
 *  @param  Object  gdata   カレンダーデータ
 */
GCalHolidays.decode = function(gdata) {
    var entries = gdata.feed.entry;
    var days = [];
    
    if (entries) {
        //日付順にソート
        entries.sort(function(a, b) {
            return (a.gd$when[0].startTime > b.gd$when[0].startTime) ? 1 : -1;
        });
        
        //シンプルな器に移す
        for (var i in entries) {
            var arr = entries[i].gd$when[0].startTime.split("-");
            for (var j in arr) {
                arr[j] *= 1;    //数値にする
            }
            days[i] = {year: arr[0], month: arr[1], date: arr[2], title: entries[i].title.$t};
        }
    }
    
    //日付範囲を取得
    var feedParts = gdata.feed.link[3].href.split("&");
    var start = "", end = "";
    for (i in feedParts) {
        var params = feedParts[i].split("=");
        switch (params[0]) {
            case "start-min": start = params[1]; break;
            case "start-max": end = params[1]; break;
        }
    }
    
    this.holidays[start + ".." + end] = days;    //キャッシュする
    
    this.userCallback(days);    //コールバック
};
