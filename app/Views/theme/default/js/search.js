let searchSource = 'website';

function search(keyword) {
    switch (searchSource) {
        case 'baidu':
            baiduSearch(keyword);
            return;
        case 'google':
            googleSearch(keyword);
            return;
        case 'bing':
            bingSearch(keyword);
            return;
        case 'yandex':
            yandexSearch(keyword);
            return;
        default:
            websiteSearch(keyword);
    }
}

function websiteSearch(keyword) {
    window.location.href = `/?q=${encodeURIComponent(keyword)}`
}

function baiduSearch(keyword) {
    const url = `https://www.baidu.com/s?wd=${encodeURIComponent(keyword)}`;
    window.open(url);
}

function googleSearch(keyword) {
    const url = `https://www.google.com/search?q=${encodeURIComponent(keyword)}`;
    window.open(url);
}

function bingSearch(keyword) {
    const url = `https://www.bing.com/search?q=${encodeURIComponent(keyword)}`;
    window.open(url);
}

function yandexSearch(keyword) {
    const url = `https://yandex.com/search/?text=${encodeURIComponent(keyword)}`;
    window.open(url);
}