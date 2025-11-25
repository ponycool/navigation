/**
 * 科技蓝主题Loading组件
 * 带粒子特效的现代化加载组件，科技感十足
 */

// 全局状态变量
let loadingState = {
    isVisible: false,
    container: null,
    progress: 0,
    particleInterval: null
};

/**
 * 创建loading DOM元素
 */
function createLoadingElement() {
    // 避免重复创建
    if (document.getElementById('tech-loading-overlay')) {
        return document.getElementById('tech-loading-overlay');
    }

    // 创建主容器，确保不添加任何额外样式影响居中
    const overlay = document.createElement('div');
    overlay.id = 'tech-loading-overlay';
    overlay.className = 'loading-overlay';
    // 确保没有内联样式覆盖CSS中的居中设置
    overlay.style.cssText = '';

    // 创建网格背景
    const grid = document.createElement('div');
    grid.className = 'loading-grid';

    // 创建内容容器
    const container = document.createElement('div');
    container.className = 'loading-container';
    container.style.cssText = ''; // 确保不添加内联样式

    // 创建spinner
    const spinner = document.createElement('div');
    spinner.className = 'loading-spinner';

    // 创建粒子容器
    const particlesContainer = document.createElement('div');
    particlesContainer.className = 'loading-particles';

    // 创建成功图标
    const successIcon = document.createElement('div');
    successIcon.className = 'loading-success-icon';

    // 创建文本
    const text = document.createElement('p');
    text.className = 'loading-text';
    text.textContent = '处理中...';
    text.setAttribute('data-text', '处理中...');

    // 创建进度条容器
    const progressContainer = document.createElement('div');
    progressContainer.className = 'loading-progress-container';

    // 创建进度条
    const progressBar = document.createElement('div');
    progressBar.className = 'loading-progress-bar';
    progressBar.style.width = '0%';

    // 组装DOM结构
    spinner.appendChild(particlesContainer); // 将粒子容器添加到spinner中
    progressContainer.appendChild(progressBar);
    container.appendChild(spinner);
    container.appendChild(successIcon);
    container.appendChild(text);
    container.appendChild(progressContainer);
    overlay.appendChild(grid);
    overlay.appendChild(container);

    // 添加到body，确保body没有影响居中的样式
    if (document.body) {
        document.body.appendChild(overlay);

        // 确保body不会影响居中
        if (!document.body.style.margin) {
            document.body.style.margin = '0';
        }
        if (!document.body.style.padding) {
            document.body.style.padding = '0';
        }
    }

    return overlay;
}

/**
 * 显示loading
 * @param {string} text - 加载提示文本
 * @param {boolean} showProgress - 是否显示进度条
 */
function showLoading(text = '处理中...', showProgress = true) {
    // 确保DOM已加载
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            _showLoadingInternal(text, showProgress);
        });
    } else {
        _showLoadingInternal(text, showProgress);
    }
}

/**
 * 内部显示方法
 */
function _showLoadingInternal(text, showProgress) {
    loadingState.container = createLoadingElement();

    // 更新文本
    const textElement = loadingState.container.querySelector('.loading-text');
    if (textElement) {
        textElement.textContent = text;
    }

    // 控制进度条显示
    const progressContainer = loadingState.container.querySelector('.loading-progress-container');
    if (progressContainer) {
        progressContainer.style.display = showProgress ? 'block' : 'none';
    }

    // 重置状态
    loadingState.container.classList.remove('loading-success');
    loadingState.progress = 0;
    _updateProgress(0);

    // 显示loading
    setTimeout(() => {
        loadingState.container.classList.add('active');
        loadingState.isVisible = true;
        // 应用性能优化和粒子效果
        optimizeAnimationForMobile();
    }, 10);
}

/**
 * 隐藏loading
 * @param {boolean} immediate - 是否立即隐藏
 */
function hideLoading(immediate = false) {
    if (!loadingState.isVisible || !loadingState.container) return;

    if (immediate) {
        loadingState.container.classList.remove('active');
        loadingState.isVisible = false;
    } else {
        loadingState.container.classList.remove('active');

        // 等待动画完成后更新状态
        setTimeout(() => {
            loadingState.isVisible = false;
        }, 300);
    }
}

/**
 * 更新loading状态
 * @param {object} options - 更新选项
 */
function updateLoading({ text, progress, success } = {}) {
    if (!loadingState.isVisible || !loadingState.container) return;

    // 更新文本
    if (text !== undefined) {
        const textElement = loadingState.container.querySelector('.loading-text');
        if (textElement) {
            textElement.textContent = text;
            textElement.setAttribute('data-text', text);
        }
    }

    // 更新进度
    if (progress !== undefined) {
        loadingState.progress = Math.max(0, Math.min(100, progress));
        _updateProgress(loadingState.progress);
    }

    // 显示成功状态
    if (success === true) {
        loadingState.container.classList.add('loading-success');
        if (!text) {
            const textElement = loadingState.container.querySelector('.loading-text');
            if (textElement) {
                textElement.textContent = '成功';
                textElement.setAttribute('data-text', '成功');
            }
        }
    } else if (success === false) {
        loadingState.container.classList.remove('loading-success');
    }
}

/**
 * 更新进度条
 */
function _updateProgress(percent) {
    if (!loadingState.container) return;
    const progressBar = loadingState.container.querySelector('.loading-progress-bar');
    if (progressBar) {
        progressBar.style.width = `${percent}%`;
    }
}

/**
 * 显示成功状态并自动隐藏
 * @param {string} text - 成功提示文本
 * @param {number} delay - 延迟隐藏的时间
 */
function successLoading(text = '成功', delay = 1000) {
    updateLoading({ success: true, text });

    setTimeout(() => {
        hideLoading();
    }, delay);
}

/**
 * 销毁loading元素
 */
function destroyLoading() {
    // 清除粒子更新定时器
    if (loadingState.particleInterval) {
        clearTimeout(loadingState.particleInterval);
        loadingState.particleInterval = null;
    }

    if (loadingState.container) {
        loadingState.container.remove();
        loadingState.container = null;
    }
    loadingState.isVisible = false;
    loadingState.progress = 0;
}

// 直接暴露简单的函数对象到window
window.Loading = {
    show: showLoading,
    hide: hideLoading,
    update: updateLoading,
    success: successLoading,
    destroy: destroyLoading
};

// 确保方法正确挂载的调试信息
// console.log('Loading模块已加载，方法列表:', Object.keys(window.Loading));
// console.log('show方法类型:', typeof window.Loading.show);

// 导出为ES模块（如果支持）
if (typeof module !== 'undefined' && typeof module.exports !== 'undefined') {
    module.exports = window.Loading;
}

/**
 * 移动端触摸事件处理
 */
function setupMobileEventHandlers() {
    // 防止loading显示时的滚动
    function preventScroll(event) {
        if (loadingState.isVisible) {
            event.preventDefault();
        }
    }

    // 防止双击缩放
    function preventDoubleTapZoom(event) {
        if (loadingState.isVisible && event.touches && event.touches.length === 1) {
            if (event.timeStamp - lastTap < 300) {
                event.preventDefault();
                return false;
            }
            lastTap = event.timeStamp;
        }
    }

    // 防止触摸移动
    function preventTouchMove(event) {
        if (loadingState.isVisible) {
            event.preventDefault();
        }
    }

    // 防止捏合缩放
    function preventPinchZoom(event) {
        if (loadingState.isVisible && event.touches && event.touches.length > 1) {
            event.preventDefault();
        }
    }

    let lastTap = 0;

    // 添加触摸事件监听器，设置passive为false以便可以preventDefault
    document.addEventListener('touchstart', preventDoubleTapZoom, { passive: false });
    document.addEventListener('touchmove', preventTouchMove, { passive: false });
    document.addEventListener('touchmove', preventPinchZoom, { passive: false });
    document.addEventListener('wheel', preventScroll, { passive: false });
    document.addEventListener('mousewheel', preventScroll, { passive: false }); // 兼容旧版浏览器

    // 优化移动设备上的性能
    if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
        // 移动设备上的性能优化标志
        window.isMobileDevice = true;
    }
}

// 初始化移动端事件处理
setupMobileEventHandlers();

/**
 * 生成粒子特效
 */
function generateParticles(spinnerElement) {
    if (!spinnerElement) return;

    const particlesContainer = spinnerElement.querySelector('.loading-particles');
    if (!particlesContainer) return;

    // 清空现有粒子
    particlesContainer.innerHTML = '';

    // 生成6-10个随机粒子
    const particleCount = Math.floor(Math.random() * 5) + 6;

    for (let i = 0; i < particleCount; i++) {
        const particle = document.createElement('div');
        particle.className = 'loading-particle';

        // 随机大小（2-4px）
        const size = Math.floor(Math.random() * 3) + 2;
        particle.style.width = `${size}px`;
        particle.style.height = `${size}px`;

        // 随机位置（在spinner边缘附近）
        const angle = Math.random() * Math.PI * 2;
        const radius = spinnerElement.offsetWidth / 2;
        const x = Math.cos(angle) * radius;
        const y = Math.sin(angle) * radius;

        // 定位到spinner边缘
        particle.style.left = `calc(50% + ${x}px)`;
        particle.style.top = `calc(50% + ${y}px)`;

        // 随机透明度
        particle.style.opacity = (Math.random() * 0.5 + 0.3).toString();

        // 随机动画延迟
        particle.style.animationDelay = (Math.random() * 2).toString() + 's';

        // 随机动画持续时间
        particle.style.animationDuration = (Math.random() * 2 + 2).toString() + 's';

        particlesContainer.appendChild(particle);
    }
}

/**
 * 定时更新粒子效果
 */
function updateParticlesPeriodically() {
    if (loadingState.isVisible && loadingState.container) {
        const spinner = loadingState.container.querySelector('.loading-spinner');
        if (spinner) {
            generateParticles(spinner);
        }

        // 每3秒更新一次粒子
        loadingState.particleInterval = setTimeout(updateParticlesPeriodically, 3000);
    }
}

/**
 * 优化动画性能并添加粒子效果
 */
function optimizeAnimationForMobile() {
    if (loadingState.container) {
        // 为所有设备添加硬件加速
        const overlay = document.getElementById('tech-loading-overlay');
        const container = overlay ? overlay.querySelector('.loading-container') : null;
        const spinner = container ? container.querySelector('.loading-spinner') : null;

        // 优化整体显示层
        if (overlay) {
            overlay.style.willChange = 'opacity';
            overlay.style.backfaceVisibility = 'hidden';
        }

        // 优化容器
        if (container) {
            container.style.willChange = 'opacity';
            container.style.backfaceVisibility = 'hidden';
        }

        // 优化旋转动画，确保科技风格动画流畅运行
        if (spinner) {
            // 为spinner添加适当的硬件加速
            spinner.style.willChange = 'transform';
            spinner.style.backfaceVisibility = 'hidden';
            spinner.style.transformStyle = 'preserve-3d';

            // 生成粒子特效
            generateParticles(spinner);

            // 启动定期更新粒子
            if (loadingState.particleInterval) {
                clearTimeout(loadingState.particleInterval);
            }
            updateParticlesPeriodically();
        }
    }
}