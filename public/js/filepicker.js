/**
 * FilePickerManager - Singleton Modal Manager
 * Robust, API-driven, and globally accessible.
 * Designed for dynamic use across any system.
 */
window.FilePickerManager = (function ($) {
    let instance = null;

    class FilePicker {
        constructor() {
            if (instance) return instance;

            this.modalId = 'fp-modal-global';
            this.$modal = null;
            this.currentSettings = {};
            this.selectedItems = [];
            this.state = {
                page: 1,
                isLoading: false,
                totalFiles: 0,
                perPage: 24
            };

            this.init();
            instance = this;
        }

        /**
         * Initialize the modal structure and global events
         */
        init() {
            this.createModal();
            this.bindEvents();
        }

        /**
         * Sets default values for the picker instance
         */
        getDefaults() {
            return {
                title: 'Ortam Kütüphanesi',
                apiUrl: '/api/library',
                uploadUrl: '/api/upload',
                deleteUrl: '/api/delete',
                multiple: false,
                inputTarget: null,
                previewTarget: null,
                perPage: 24,
                accept: 'image/*',
                maxSize: 5 * 1024 * 1024, // 5MB
                // Hooks
                onSelect: null,
                onOpen: null,
                onClose: null,
                onUpload: null,
                onDelete: null,
                onError: null
            };
        }

        createModal() {
            const html = `
                <div class="fp-modal-backdrop" id="${this.modalId}">
                    <div class="fp-modal-container">
                        <div class="fp-toast" id="fp-toast"></div>
                        
                        <div class="fp-modal-header">
                            <h3 class="fp-modal-title" id="fp-title">Ortam Kütüphanesi</h3>
                            <div class="fp-close-btn" id="fp-close-trigger">&times;</div>
                        </div>

                        <div class="fp-modal-tabs">
                            <div class="fp-tab active" data-target="fp-library">Kütüphane</div>
                            <div class="fp-tab" data-target="fp-upload">Dosya Yükle</div>
                        </div>

                        <div class="fp-modal-content">
                            <!-- Library Panel -->
                            <div class="fp-panel active" id="fp-library">
                                <div class="fp-library-main">
                                    <div class="fp-loading-overlay" id="fp-loader-library"><div class="fp-spinner"></div></div>
                                    <div class="fp-grid" id="fp-library-grid"></div>
                                    <div class="fp-pagination" id="fp-pagination">
                                        <button class="fp-page-btn" id="fp-prev-page">Önceki</button>
                                        <span class="fp-page-info">Sayfa <span id="fp-current-page">1</span></span>
                                        <button class="fp-page-btn" id="fp-next-page">Sonraki</button>
                                    </div>
                                </div>
                                <div class="fp-sidebar" id="fp-sidebar">
                                    <div class="fp-sidebar-empty">Dosya ayrıntılarını görmek için bir öğeye tıklayın.</div>
                                    <div class="fp-sidebar-content" style="display:none">
                                        <h4 class="fp-sidebar-title">EKLENTİ AYRINTILARI</h4>
                                        <div class="fp-side-preview"><img src="" id="fp-side-img"></div>
                                        <div class="fp-side-meta">
                                            <div class="fp-side-filename" id="fp-side-name"></div>
                                            <div class="fp-side-date" id="fp-side-date"></div>
                                            <div class="fp-side-size" id="fp-side-size"></div>
                                            <div class="fp-side-dim" id="fp-side-type"></div>
                                            <a href="javascript:void(0)" class="fp-side-delete text-danger" id="fp-side-delete-btn">Kalıcı olarak sil</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Upload Panel -->
                            <div class="fp-panel" id="fp-upload">
                                <div class="fp-upload-view">
                                    <div class="fp-loading-overlay" id="fp-loader-upload"><div class="fp-spinner"></div></div>
                                    <div class="fp-upload-zone-wrapper">
                                        <div class="fp-upload-area" id="fp-dropzone">
                                            <div class="fp-upload-icon">+</div>
                                            <p style="font-weight: 500; font-size: 1.2rem;">Dosyaları buraya bırakın</p>
                                            <p style="color: #666; margin-top: 5px;">VEYA</p>
                                            <button class="fp-btn fp-btn-secondary" style="margin-top:15px">Dosya Seçin</button>
                                            <p style="color: #999; margin-top: 15px; font-size: 0.8rem;" id="fp-upload-hint">Maksimum yükleme boyutu: 5 MB.</p>
                                            
                                            <div class="fp-progress-container" id="fp-upload-progress" style="display:none">
                                                <div class="fp-progress-bar" id="fp-progress-inner"></div>
                                            </div>
                                            <input type="file" style="display:none" id="fp-file-input-global">
                                        </div>
                                    </div>
                                    <div class="fp-upload-results-wrapper" id="fp-upload-results-container" style="display:none">
                                        <h4 class="fp-results-title">YENİ YÜKLENENLER</h4>
                                        <div class="fp-grid fp-upload-grid" id="fp-upload-results-grid"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="fp-modal-footer">
                            <div class="fp-selection-counter" id="fp-selection-count"></div>
                            <div class="fp-footer-actions">
                                <button class="fp-btn fp-btn-primary" id="fp-confirm-trigger" disabled>Seç</button>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            if ($('#' + this.modalId).length === 0) $('body').append(html);
            this.$modal = $('#' + this.modalId);
        }

        bindEvents() {
            const self = this;

            this.$modal.on('click', '.fp-tab', function () {
                const target = $(this).data('target');
                self.$modal.find('.fp-tab').removeClass('active');
                $(this).addClass('active');
                self.$modal.find('.fp-panel').removeClass('active');
                $('#' + target).addClass('active');

                if (target === 'fp-library') {
                    self.state.page = 1;
                    self.fetchLibrary();
                } else {
                    $('#fp-upload-results-container').hide();
                    $('#fp-upload-results-grid').empty();
                    $('.fp-upload-area').removeClass('compact');
                }
            });

            this.$modal.on('click', '#fp-prev-page', () => { if (this.state.page > 1) { this.state.page--; this.fetchLibrary(); } });
            this.$modal.on('click', '#fp-next-page', () => { this.state.page++; this.fetchLibrary(); });

            this.$modal.on('click', '.fp-item', function () {
                const $item = $(this);
                const fileData = $item.data('json');

                if (self.currentSettings.multiple) {
                    if ($item.hasClass('selected')) {
                        $item.removeClass('selected');
                        self.selectedItems = self.selectedItems.filter(i => i.id !== fileData.id);
                    } else {
                        $item.addClass('selected');
                        self.selectedItems.push(fileData);
                    }
                } else {
                    self.$modal.find('.fp-item').removeClass('selected');
                    $item.addClass('selected');
                    self.selectedItems = [fileData];
                }

                self.updateSidebar();
                self.updateFooter();
                self.syncSelectionUI(fileData.id, $item.hasClass('selected'));
            });

            this.$modal.on('click', '#fp-side-delete-btn', () => {
                if (self.selectedItems.length === 0) return;
                const file = self.selectedItems[self.selectedItems.length - 1];
                if (confirm(`"${file.name}" dosyasını silmek istediğinize emin misiniz?`)) self.deleteFile(file.id);
            });

            this.$modal.on('click', '#fp-dropzone', (e) => {
                if (e.target.id !== 'fp-file-input-global') $('#fp-file-input-global').click();
            });

            this.$modal.on('change', '#fp-file-input-global', function () {
                if (this.files && this.files.length > 0) self.uploadFiles(this.files);
            });

            this.$modal.on('click', '#fp-confirm-trigger', () => {
                if (this.selectedItems.length > 0) {
                    const urls = this.selectedItems.map(i => i.url);
                    this.completeSelection(self.currentSettings.multiple ? urls : urls[0]);
                }
            });

            this.$modal.on('click', '#fp-close-trigger', () => this.close());
            this.$modal.on('click', (e) => { if ($(e.target).hasClass('fp-modal-backdrop')) this.close(); });
        }

        /**
         * Opens the modal with specified options
         */
        open(options = {}) {
            this.currentSettings = $.extend(this.getDefaults(), options);
            this.state.perPage = this.currentSettings.perPage;

            $('#fp-title').text(this.currentSettings.title);
            $('#fp-file-input-global').attr('accept', this.currentSettings.accept);

            const mbCount = Math.floor(this.currentSettings.maxSize / (1024 * 1024));
            $('#fp-upload-hint').text(`Maksimum yükleme boyutu: ${mbCount} MB.`);

            this.selectedItems = [];
            this.resetUI();

            const $fileInput = $('#fp-file-input-global');
            if (this.currentSettings.multiple) $fileInput.attr('multiple', 'multiple');
            else $fileInput.removeAttr('multiple');

            this.$modal.addClass('active');
            $('body').css('overflow', 'hidden');
            this.state.page = 1;

            if (typeof this.currentSettings.onOpen === 'function') this.currentSettings.onOpen();

            this.updateSidebar();
            this.updateFooter();
            this.fetchLibrary();
        }

        close() {
            this.$modal.removeClass('active');
            $('body').css('overflow', '');
            if (typeof this.currentSettings.onClose === 'function') this.currentSettings.onClose();
        }

        resetUI() {
            this.$modal.find('.fp-tab').removeClass('active').first().addClass('active');
            this.$modal.find('.fp-panel').removeClass('active').first().addClass('active');
            $('#fp-upload-results-container').hide();
            $('#fp-upload-results-grid').empty();
            $('.fp-upload-area').removeClass('compact');
            $('#fp-upload-progress').hide();
        }

        setLoading(panel, loading) {
            const $loader = (panel === 'library') ? $('#fp-loader-library') : $('#fp-loader-upload');
            $loader.toggleClass('active', loading);
            this.state.isLoading = loading;
        }

        notify(message, type = 'success') {
            const $toast = $('#fp-toast');
            $toast.removeClass('fp-toast-error fp-toast-success').addClass(`fp-toast-${type}`).text(message).addClass('active');
            setTimeout(() => $toast.removeClass('active'), 3000);
        }

        handleError(message, err = null) {
            console.error('FilePicker Error:', message, err);
            this.notify(message, 'error');
            if (typeof this.currentSettings.onError === 'function') this.currentSettings.onError(message, err);
        }

        fetchLibrary() {
            this.setLoading('library', true);
            $.ajax({
                url: this.currentSettings.apiUrl,
                method: 'GET',
                data: { page: this.state.page, per_page: this.state.perPage },
                dataType: 'json',
                success: (response) => {
                    this.renderLibrary(Array.isArray(response.data || response) ? (response.data || response) : []);
                    this.updatePagination(response.meta || {});
                },
                error: (xhr) => this.handleError('Dosyalar yüklenemedi!', xhr),
                complete: () => this.setLoading('library', false)
            });
        }

        uploadFiles(files) {
            const formData = new FormData();
            if (files.length === 1) formData.append('file', files[0]);
            else { for (let i = 0; i < files.length; i++) formData.append('files[]', files[i]); }

            this.setLoading('upload', true);
            const $progress = $('#fp-upload-progress').show();
            const $bar = $('#fp-progress-inner').css('width', '0%');

            $.ajax({
                url: this.currentSettings.uploadUrl,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                xhr: () => {
                    const xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener("progress", (evt) => {
                        if (evt.lengthComputable) $bar.css('width', (evt.loaded / evt.total) * 100 + '%');
                    });
                    return xhr;
                },
                success: (response) => {
                    this.notify('Dosyalar yüklendi.');
                    const uploadedData = response.data;
                    const fileList = Array.isArray(uploadedData) ? uploadedData : [uploadedData];

                    if (typeof this.currentSettings.onUpload === 'function') this.currentSettings.onUpload(fileList);
                    this.renderUploadResults(fileList);
                },
                error: (xhr) => this.handleError('Yükleme başarısız!', xhr),
                complete: () => {
                    this.setLoading('upload', false);
                    setTimeout(() => $progress.hide(), 1000);
                }
            });
        }

        deleteFile(id) {
            this.setLoading('library', true);
            $.ajax({
                url: this.currentSettings.deleteUrl,
                method: 'POST',
                data: { id: id },
                dataType: 'json',
                success: () => {
                    this.notify('Silindi.');
                    if (typeof this.currentSettings.onDelete === 'function') this.currentSettings.onDelete(id);
                    this.selectedItems = this.selectedItems.filter(i => i.id !== id);
                    this.updateSidebar();
                    this.updateFooter();
                    this.fetchLibrary();
                    this.$modal.find(`.fp-item`).filter(function () { return $(this).data('json')?.id === id; }).remove();
                },
                error: (xhr) => this.handleError('Silinemedi!', xhr),
                complete: () => this.setLoading('library', false)
            });
        }

        renderLibrary(items) {
            const $grid = $('#fp-library-grid').empty();
            if (items.length === 0) { $grid.append('<div class="fp-empty-state">Kütüphane henüz boş.</div>'); return; }

            items.forEach(item => {
                const isSelected = this.selectedItems.some(i => i.id === item.id);
                $grid.append(this.createItemElement(item, isSelected));
            });
        }

        renderUploadResults(files) {
            const $container = $('#fp-upload-results-container').show();
            const $grid = $('#fp-upload-results-grid');
            $('.fp-upload-area').addClass('compact');

            files.forEach(file => {
                if (!this.currentSettings.multiple) this.selectedItems = [file];
                else {
                    if (!this.selectedItems.some(i => i.id === file.id)) this.selectedItems.push(file);
                }

                const $el = this.createItemElement(file, true);
                $grid.prepend($el);
            });

            this.updateFooter();
            this.updateSidebar();
        }

        createItemElement(item, isSelected) {
            const $el = $(`
                <div class="fp-item ${isSelected ? 'selected' : ''}" title="${item.name}">
                    <div class="fp-item-preview"><img src="${item.url}" alt="${item.name}"></div>
                    <div class="fp-check-icon">✓</div>
                </div>
            `);
            $el.data('json', item);
            return $el;
        }

        syncSelectionUI(id, selected) {
            this.$modal.find('.fp-item').filter(function () {
                return $(this).data('json')?.id === id;
            }).toggleClass('selected', selected);
        }

        updateSidebar() {
            const $sidebar = $('#fp-sidebar'), $empty = $sidebar.find('.fp-sidebar-empty'), $content = $sidebar.find('.fp-sidebar-content');
            if (this.selectedItems.length === 0) { $empty.show(); $content.hide(); return; }
            $empty.hide(); $content.show();

            const file = this.selectedItems[this.selectedItems.length - 1]; // Show info for last interaction
            $('#fp-side-img').attr('src', file.url);
            $('#fp-side-name').text(file.name);
            $('#fp-side-date').text(file.created_at || 'Bilinmiyor');
            $('#fp-side-size').text(this.formatBytes(file.size));
            $('#fp-side-type').text(file.mime_type?.toUpperCase() || 'DOSYA');
        }

        updateFooter() {
            const count = this.selectedItems.length, $btn = $('#fp-confirm-trigger'), $counter = $('#fp-selection-count');
            $btn.prop('disabled', count === 0);
            $counter.text(count > 0 ? `${count} öğe seçildi` : '');
        }

        updatePagination(meta) {
            const lastPage = meta.last_page || 1, $pagination = $('#fp-pagination');
            if (lastPage <= 1) $pagination.hide();
            else {
                $pagination.show();
                $('#fp-current-page').text(this.state.page);
                $('#fp-prev-page').prop('disabled', this.state.page <= 1);
                $('#fp-next-page').prop('disabled', this.state.page >= lastPage);
            }
        }

        formatBytes(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024, i = Math.floor(Math.log(bytes) / Math.log(k)), sizes = ['Bytes', 'KB', 'MB', 'GB'];
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        completeSelection(urls) {
            const value = Array.isArray(urls) ? urls.join(',') : urls;
            if (this.currentSettings.inputTarget) $(this.currentSettings.inputTarget).val(value);
            if (this.currentSettings.previewTarget) {
                $(this.currentSettings.previewTarget).attr('src', Array.isArray(urls) ? urls[0] : urls).show().siblings().hide();
            }
            if (typeof this.currentSettings.onSelect === 'function') this.currentSettings.onSelect(urls);
            this.close();
        }
    }
    return new FilePicker();
})(jQuery);

/**
 * jQuery Plug-in Wrapper
 */
(function ($) {
    $.fn.filePicker = function (options) {
        return this.each(function () {
            $(this).off('click.fp').on('click.fp', function (e) {
                e.preventDefault();
                window.FilePickerManager.open(options);
            });
        });
    };
})(jQuery);
