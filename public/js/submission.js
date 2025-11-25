// 定义提交的分类ID
let categoryId = 0;
// 点击收录时，获取分类ID
document.addEventListener('click', function (e) {
    if (e.target.classList.contains('add-apply')) {
        categoryId = parseInt(e.target.dataset.id);
    }
});
document.addEventListener('click', function (e) {
    if (e.target.classList.contains('footer-add-apply')) {
        categoryId = 0;
    }
});
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('submissionForm');
    const submitBtn = document.getElementById('submitBtn');
    const cancelBtn = document.querySelector('.cancel');
    const closeBtn = document.querySelector('.close-btn');
    const stars = document.querySelectorAll('.star');
    const rating = document.getElementById('rating');
    const tagItems = document.querySelectorAll('.tag-item');
    // 选中的标签
    const selectedTagsInput = document.getElementById('selectedTags');
    let selectedTags = [];
    // 全局错误提示
    let globalErrorContainer = document.querySelector('.global-error-container');

    // -------------------------- 工具函数：错误提示处理 --------------------------
    /**
     * 清除所有输入项的错误提示
     */
    function clearAllErrors() {
        document.querySelectorAll('.error-tip').forEach(tip => tip.remove());
        document.querySelectorAll('.input-item').forEach(item => {
            item.classList.remove('has-error');
        });
        if (globalErrorContainer) {
            globalErrorContainer.style.display = 'none';
        }
    }

    /**
     * 给指定输入项添加错误提示（插入到输入框下方）
     * @param {string} inputName - 输入框的name属性值
     * @param {string} message - 错误提示信息
     */
    function showError(inputName, message) {
        // 如果未指定inputName，显示全局错误
        if (!inputName) {
            if (!globalErrorContainer) {
                globalErrorContainer = document.createElement('div');
                globalErrorContainer.className = 'global-error-container';
                globalErrorContainer.style.cssText = `            color: #ff4d4f;
            background: #fff2f0;
            border: 1px solid #ffccc7;
            border-radius: 4px;
            padding: 12px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
        `;
                // 插入到表单顶部
                form.insertBefore(globalErrorContainer, form.firstChild);
            }
            globalErrorContainer.textContent = message;
            globalErrorContainer.style.display = 'block';
            return;
        }
        // 清除该输入项已有的错误提示
        const input = form.querySelector(`input[name="${inputName}"], textarea[name="${inputName}"]`);
        if (!input) return;

        const inputItem = input.closest('.input-item');
        inputItem.querySelector('.error-tip')?.remove();

        // 创建错误提示元素
        const errorTip = document.createElement('div');
        errorTip.className = 'error-tip';
        errorTip.textContent = message;
        errorTip.style.color = '#ff4d4f';
        errorTip.style.fontSize = '12px';
        errorTip.style.marginTop = '4px';
        errorTip.style.height = '16px'; // 固定高度避免布局跳动

        // 添加错误样式到输入项容器
        inputItem.classList.add('has-error');
        // 插入到输入框/文本域后面
        input.after(errorTip);
    }

    // -------------------星级评分------------------------
    // 重置星级评分
    function resetRating() {
        stars.forEach(star => {
            star.classList.remove('active');
            star.classList.remove('half');
        });
        rating.value = '';
    }

    // 标签选择交互
    tagItems.forEach(tag => {
        tag.addEventListener('click', function () {
            const tagName = this.dataset.name;
            const tagId = this.dataset.id || '';
            const tagData = {id: tagId, name: tagName};

            // 切换选择状态
            const index = selectedTags.findIndex(t => t.name === tagName);
            if (index > -1) {
                selectedTags.splice(index, 1);
            } else {
                selectedTags.push(tagData);
            }

            // 更新隐藏输入值
            selectedTagsInput.value = JSON.stringify(selectedTags);
        });
    });

    // 表单验证
    function validateForm() {
        // 先清除所有错误提示
        clearAllErrors();
        let isValid = true;
        const url = form.url.value.trim();
        const name = form.name.value.trim();
        const favicon = form.favicon.value.trim();
        const github = form.github.value.trim();

        // 验证网站链接
        if (!url) {
            showError('url', '请输入网站链接');
            isValid = false;
        } else {
            const urlRegex = /^https?:\/\/.+/i;
            if (!urlRegex.test(url)) {
                showError('url', '网站链接格式不正确（需以http://或https://开头）');
                isValid = false;
            }
        }

        // 验证网站名称
        if (!name) {
            showError('name', '请输入网站名称');
            isValid = false;
        }

        // 验证图标地址（可选，有值时验证格式）
        if (favicon && !/^https?:\/\/.+/i.test(favicon)) {
            showError('favicon', '图标地址格式不正确');
            isValid = false;
        }

        // 验证GitHub地址（可选，有值时验证格式）
        if (github && !/^https?:\/\/(github\.com\/.+|.+?\.github\.io\/?)/i.test(github)) {
            showError('github', 'GitHub地址格式不正确');
            isValid = false;
        }

        return isValid;
    }

    // 获取表单数据
    function getFormData() {
        const cid = Number.isInteger(categoryId) ? categoryId : 0;
        const description = form.description.value.trim();
        const favicon = form.favicon.value.trim();
        const websiteRating = rating.innerText ? parseInt(rating.innerText) : 0;
        const tags = JSON.stringify(selectedTags);
        const github = form.github.value.trim();
        const formData = {
            url: form.url.value.trim(),
            websiteName: form.name.value.trim(),
        };
        if (cid > 0) {
            formData.cid = cid;
        }
        if (description) {
            formData.description = description;
        }
        if (favicon) {
            formData.favicon = favicon;
        }
        if (websiteRating > 0) {
            formData.rating = websiteRating;
        }
        if (tags) {
            formData.tags = tags;
        }
        if (github) {
            formData.github = github;
        }
        return formData;
    }

    // 提交表单
    async function submitForm() {
        // 先执行验证，验证不通过则不提交
        if (!validateForm()) return;

        const formData = getFormData();
        submitBtn.disabled = true;
        submitBtn.textContent = '提交中...';

        try {
            Loading.show('正在提交表单');
            const url = '/api/open/website/submission/create';
            const response = await fetch(url, {
                method: 'POST', headers: {
                    'Content-Type': 'application/json',
                    // 如果需要CSRF保护
                    // 'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content
                }, body: JSON.stringify(formData)
            });

            const result = await response.json();

            if (!response.ok) {
                showError('', '提交失败，请稍后再试');
                Loading.hide();
                return;
            }
            if (result.code !== 0) {
                showError('', result.message);
                Loading.hide();
                return;
            }

            Loading.show('正在处理...', true);

            // 提交成功：清除错误、重置表单
            clearAllErrors();
            form.reset();
            resetRating();
            selectedTags = [];
            selectedTagsInput.value = '';
            tagItems.forEach(tag => tag.classList.remove('label-active'));

            // 显示成功提示
            setTimeout(() => {
                Loading.update({progress: 100});
                setTimeout(() => {
                    Loading.success('提交成功！', 2000);
                }, 500);
            }, 1500);
        } catch (error) {
            // 网络错误
            showError('', '网络异常，请检查连接');
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = '确定';
        }
    }

    // 事件监听
    submitBtn.addEventListener('click', submitForm);

    // 取消按钮和关闭按钮
    [cancelBtn, closeBtn].forEach(btn => {
        btn.addEventListener('click', function () {
            // 关闭时清除错误提示
            clearAllErrors();
            // 关闭弹窗逻辑
            document.querySelector('.add-pop-up').style.display = 'none';
        });
    });

    // 阻止表单默认提交
    form.addEventListener('submit', function (e) {
        e.preventDefault();
    });
    // 输入框输入时，清除对应项的错误提示
    form.querySelectorAll('input, textarea').forEach(input => {
        input.addEventListener('input', function () {
            // 关闭全局错误提示
            if (globalErrorContainer) {
                globalErrorContainer.style.display = 'none';
            }
            // 清除对应项的错误提示
            // const inputName = this.name;
            const inputItem = this.closest('.input-item');
            inputItem.querySelector('.error-tip')?.remove();
            inputItem.classList.remove('has-error');
        });
    });
});