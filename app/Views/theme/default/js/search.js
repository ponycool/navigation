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
        default:
            websiteSearch(keyword);
    }
}

function websiteSearch(keyword) {
    const url = `https://www.baidu.com/s?wd=${encodeURIComponent(keyword)}`;
    window.open(url);
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