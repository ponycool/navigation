const defaultShortcuts = [
    {
        name: '酷码工具',
        url: 'https://ponycool.com/tools/index',
        icon: 'https://ponycool.com/img/tools/favicon.ico'
    },
    {
        name: '酷码CMS',
        url: 'https://ponycool.com/cms/index',
        icon: 'https://ponycool.com/img/cms/favicon.ico'
    },
    {
        name: '汽水音乐',
        url: 'https://www.qishui.com',
        icon: 'https://lf-luna.qishui.com/obj/music-luna-fe/luna/home/1.0.0.200/favicon.png'
    },
    {
        name: '西瓜视频',
        url: 'https://www.ixigua.com',
        icon: 'https://sf1-cdn-tos.douyinstatic.com/obj/eden-cn/lpqpflo/ixigua_favicon.ico'
    },
    {
        name: '抖音',
        url: 'https://www.douyin.com',
        icon: 'https://www.douyin.com/favicon.ico'
    },
    {
        name: '京东',
        url: 'https://www.jd.com',
        icon: 'https://www.jd.com/favicon.ico'
    },
    {
        name: '今日头条',
        url: 'https://www.toutiao.com',
        icon: 'https://www.toutiao.com/favicon.ico'
    },
];

// 快捷导航
$(function () {
    let shortcuts = utils.cache.get('shortcuts');
    // 初始化快捷导航
    if (shortcuts === null) {
        shortcuts = defaultShortcuts;
        utils.cache.set('shortcuts', JSON.stringify(shortcuts));
    }
    refreshShortcuts();

    // 新增快捷导航
    $('.nav-group ul').on('click', '.add-shortcut', function () {
        const name = $(this).data('name');
        const url = $(this).data('url');
        const icon = $(this).data('icon');
        if (name === undefined || url === undefined) {
            message("添加失败");
        }
        addShortcut(name, url, icon);
        message('添加成功');
        $(this).parent().slideUp('quick');
    })

    // 删除快捷导航
    $('.hot-nav ul').on('click', '.del-shortcut', function () {
        const url = $(this).data('url');
        delShortcut(url);
        const index = $(this).closest('li').index();
        $('.hot-nav ul li').eq(index).remove();
        $('.shortcut-label-list li').eq(index).remove();
    })
    $('.shortcut-label-list').on('click', '.del-shortcut', function () {
        const url = $(this).data('url');
        delShortcut(url);
        const index = $(this).closest('li').index();
        $('.hot-nav ul li').eq(index).remove();
        $('.shortcut-label-list li').eq(index).remove();
    })
})

// 刷新快捷导航
function refreshShortcuts() {
    let shortcuts = utils.cache.get('shortcuts');
    if (shortcuts === null) {
        return;
    }
    shortcuts = JSON.parse(shortcuts);
    let html = '';
    let desktopShortcutHtml = '';

    for (let i = 0; i < shortcuts.length; i++) {
        html += getShortcutItem(shortcuts[i].name, shortcuts[i].url, shortcuts[i].icon);
        desktopShortcutHtml += getDesktopShortcutItem(shortcuts[i].name, shortcuts[i].url, shortcuts[i].icon);
    }
    // 添加快捷导航
    $('.hot-nav ul li:gt(0)').remove();
    $('.hot-nav ul').append(html);
    // 添加桌面快捷导航
    $('.shortcut-label-list li:gt(0)').remove();
    $('.shortcut-label-list').append(desktopShortcutHtml);
}

// 增加快捷导航
function addShortcut(name, url, icon) {
    let shortcuts = utils.cache.get('shortcuts');
    shortcuts = JSON.parse(shortcuts);
    // 如果存在则不添加
    if (shortcuts.find(item => item.url === url)) {
        return;
    }
    shortcuts.push({
        name: name,
        url: url,
        icon: icon
    });
    utils.cache.set('shortcuts', JSON.stringify(shortcuts));
    const html = getShortcutItem(name, url, icon);
    $('.hot-nav ul').append(html);
    const desktopShortcutHtml = getDesktopShortcutItem(name, url, icon);
    $('.shortcut-label-list').append(desktopShortcutHtml);
}

// 删除快捷导航
function delShortcut(url) {
    let shortcuts = utils.cache.get('shortcuts');
    shortcuts = JSON.parse(shortcuts);
    for (let i = 0; i < shortcuts.length; i++) {
        if (shortcuts[i]?.url === url) {
            shortcuts.splice(i, 1);
            break;
        }
    }
    utils.cache.set('shortcuts', JSON.stringify(shortcuts));
}

// 获取快捷导航项
function getShortcutItem(name, url, icon) {
    const defaultIcon = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAABHNCSVQICAgIfAhkiAAAAf9JREFUaIHtmF+SgjAMxr84+6B3Ai236G0Wb8MtQPBO+GT2AZhl1xboHyDO+D1qE/qbJE1a4CNZor03sETnlnMmXIhxq0+Um9aIBklaVkQox781RzLu+WubLbmpB/gGoJbaiANJH1zCAWCQGJC+Dr597Q8xNxMiJlxC7MWAEOMWYi8GpD5RTozrzLLK9ocYEKCDaY5EC4BeJApkkA0oNP2i6aw5TzVzqrmcXz2yazlPH1wmLSvbmk06+1lzzvh7tDKQ3QuqYn1j9dQyQayh1RriAMBrfeCfVonIVlEYK2qNuAA0hXmK9VUUZz3ABQ7DXuxiD64R3zQ6dNDV1JpEs6LOtyLgWhfmSxUQEJEYdTAVFYP/qikos/nyKvZYxUxAmejXJufj3ym11jhSCShTzcBvmikf/4tBNjhSVYjxLMjWjc1XkzWyR2PzlRXknSCACZC+wb2NrDVCwI3NBVgRzBecEbzJblVNNsREszoA6glUruPEYOs6ukxosiFucrEajxoBbuJ3dlfdC6qagjIGrBsJ1aaPDz0QYWZY9NEuryhNQRnB/clnSrs9B9UF5TFhdn3Xigmz+wNdLJjdQYA4MCJAYkgMSGhUxIAAHYyvrSgQAPCNijgQW1RsE/cgcSCAX1REgvhIJIgpvZ4zg6ZIEOAlvZwvdqKUaFaml8iP3kE/R9rLZe1XiTwAAAAASUVORK5CYII=';
    const delIcon = `data:image/svg+xml,%3csvg%20width='24.000000'%20height='24.000000'%20viewBox='0%200%2024%2024'%20fill='none'%20xmlns='http://www.w3.org/2000/svg'%20xmlns:xlink='http://www.w3.org/1999/xlink'%3e%3cdesc%3e%20Created%20with%20Pixso.%20%3c/desc%3e%3cdefs%3e%3cclipPath%20id='clip57_2'%3e%3crect%20id='operation'%20width='24.000000'%20height='24.000000'%20fill='white'%20fill-opacity='0'/%3e%3c/clipPath%3e%3c/defs%3e%3crect%20id='operation'%20width='24.000000'%20height='24.000000'%20fill='%23FFFFFF'%20fill-opacity='0'/%3e%3cg%20clip-path='url(%23clip57_2)'%3e%3ccircle%20id='椭圆%2012'%20cx='12.000000'%20cy='8.000000'%20r='1.000000'%20fill='%236B6B6B'%20fill-opacity='1.000000'/%3e%3ccircle%20id='椭圆%2013'%20cx='12.000000'%20cy='12.000000'%20r='1.000000'%20fill='%236B6B6B'%20fill-opacity='1.000000'/%3e%3ccircle%20id='椭圆%2014'%20cx='12.000000'%20cy='16.000000'%20r='1.000000'%20fill='%236B6B6B'%20fill-opacity='1.000000'/%3e%3c/g%3e%3c/svg%3e`;
    let html = '';
    html += '<li>';
    html += `<a href="${url}" target="_blank">`;
    html += '<div>';
    html += `<img src="${icon ? icon : defaultIcon}" alt="${name}" class="nav-img" />`
    html += '</div>'
    html += `<h6>${name}</h6>`;
    html += '</a>';
    html += `<img src="${delIcon}" alt="" class="operation-btn" />`
    html += `<p class="del-shortcut" data-name="${name}" data-url="${url}">删除</p>`
    html += '</li>';
    return html;
}

// 获取桌面快捷导航项
function getDesktopShortcutItem(name, url, icon) {
    const defaultIcon = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAABHNCSVQICAgIfAhkiAAAAf9JREFUaIHtmF+SgjAMxr84+6B3Ai236G0Wb8MtQPBO+GT2AZhl1xboHyDO+D1qE/qbJE1a4CNZor03sETnlnMmXIhxq0+Um9aIBklaVkQox781RzLu+WubLbmpB/gGoJbaiANJH1zCAWCQGJC+Dr597Q8xNxMiJlxC7MWAEOMWYi8GpD5RTozrzLLK9ocYEKCDaY5EC4BeJApkkA0oNP2i6aw5TzVzqrmcXz2yazlPH1wmLSvbmk06+1lzzvh7tDKQ3QuqYn1j9dQyQayh1RriAMBrfeCfVonIVlEYK2qNuAA0hXmK9VUUZz3ABQ7DXuxiD64R3zQ6dNDV1JpEs6LOtyLgWhfmSxUQEJEYdTAVFYP/qikos/nyKvZYxUxAmejXJufj3ym11jhSCShTzcBvmikf/4tBNjhSVYjxLMjWjc1XkzWyR2PzlRXknSCACZC+wb2NrDVCwI3NBVgRzBecEbzJblVNNsREszoA6glUruPEYOs6ukxosiFucrEajxoBbuJ3dlfdC6qagjIGrBsJ1aaPDz0QYWZY9NEuryhNQRnB/clnSrs9B9UF5TFhdn3Xigmz+wNdLJjdQYA4MCJAYkgMSGhUxIAAHYyvrSgQAPCNijgQW1RsE/cgcSCAX1REgvhIJIgpvZ4zg6ZIEOAlvZwvdqKUaFaml8iP3kE/R9rLZe1XiTwAAAAASUVORK5CYII=';
    const delIcon = `data:image/svg+xml,%3csvg%20width='24.000000'%20height='24.000000'%20viewBox='0%200%2024%2024'%20fill='none'%20xmlns='http://www.w3.org/2000/svg'%20xmlns:xlink='http://www.w3.org/1999/xlink'%3e%3cdesc%3e%20Created%20with%20Pixso.%20%3c/desc%3e%3cdefs%3e%3cclipPath%20id='clip57_2'%3e%3crect%20id='operation'%20width='24.000000'%20height='24.000000'%20fill='white'%20fill-opacity='0'/%3e%3c/clipPath%3e%3c/defs%3e%3crect%20id='operation'%20width='24.000000'%20height='24.000000'%20fill='%23FFFFFF'%20fill-opacity='0'/%3e%3cg%20clip-path='url(%23clip57_2)'%3e%3ccircle%20id='椭圆%2012'%20cx='12.000000'%20cy='8.000000'%20r='1.000000'%20fill='%236B6B6B'%20fill-opacity='1.000000'/%3e%3ccircle%20id='椭圆%2013'%20cx='12.000000'%20cy='12.000000'%20r='1.000000'%20fill='%236B6B6B'%20fill-opacity='1.000000'/%3e%3ccircle%20id='椭圆%2014'%20cx='12.000000'%20cy='16.000000'%20r='1.000000'%20fill='%236B6B6B'%20fill-opacity='1.000000'/%3e%3c/g%3e%3c/svg%3e`;
    let html = '';
    html += '<li>';
    html += `<a href="${url}" target="_blank">`;
    html += '<div>';
    html += `<img src="${icon ? icon : defaultIcon}" alt="${name}" />`;
    html += '</div>';
    html += `<p>${name}</p>`;
    html += '</a>';
    html += `<img src="${delIcon}" alt="" class="operation-btn" />`
    html += `<p class="tool-del del-shortcut" data-name="${name}" data-url="${url}">删除</p>`
    html += '</li>';
    return html;
}