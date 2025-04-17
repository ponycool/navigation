window.utils = window.utils || {};
window.utils.cache = (function (utils) {

    /**
     * 获取缓存数据
     * @param key
     * @returns {string}
     */
    utils.get = (key) => {
        return localStorage.getItem(key);
    }

    /**
     * 缓存数据
     * @param key
     * @param value
     */
    utils.set = (key, value) => {
        localStorage.setItem(key, value);
    }

    /**
     * 删除缓存数据
     * @param key
     */
    utils.remove = (key) => {
        localStorage.removeItem(key);
    }
    return utils;

})(window.utils.cache || {});