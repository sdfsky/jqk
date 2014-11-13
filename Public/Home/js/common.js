$(function() {
//事件绑定
});



//收藏本站
function addfavorite() {
    var url = 'http://www.juqingku.net';
    var title = '剧情库 - 给你看到更多';
    var ua = navigator.userAgent.toLowerCase();
    if (ua.indexOf("msie 8") > -1) {
        external.AddToFavoritesBar(url, title, ''); //IE8
    } else {
        try {
            window.external.addFavorite(url, title);
        } catch (e) {
            try {
                window.sidebar.addPanel(title, url, ''); //firefox
            } catch (e) {
                alert("加入收藏失败，请使用键盘Ctrl+D进行添加");
            }
        }
    }
    return false;
}

