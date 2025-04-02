$(function () {
    let clipboard = new ClipboardJS('.share-btn', {
        text: function (trigger) {
            const url = $(trigger).parents("li").find("a").attr("href")
            return window.location.origin + "/?url=" + encodeURIComponent(url);
        }
    })
// 复制成功反馈
    clipboard.on('success', function (e) {
        // 复制成功，显示toast提示
        $(".toast").show().text("复制成功")
        setTimeout(function () {
            $(".toast").hide()
        }, 1500)
    })
// 复制失败反馈
    clipboard.on('error', function (e) {
        // 复制失败，显示toast提示
        $(".toast").show().text("复制失败")
        setTimeout(function () {
            $(".toast").hide()
        }, 1500)
    })
});