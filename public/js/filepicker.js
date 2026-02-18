/**
 * FilePickerManager - Singleton Modal Manager
 * Robust, API-driven, and globally accessible.
 */
window.FilePickerManager = (function ($) {
    let instance = null;

    class FilePicker {
        constructor() {
            if (instance) return instance;

            this.modalId = 'fp-modal-global';
            this.$modal = null;
            this.currentSettings = {};
            this.state = {
                page: 1,
                search: '',
                isLoading: false,
                totalFiles: 0,
                perPage: 12
            };

            this.init();
            instance = this;
        }

        init() {
            this.createModal();
            this.bindEvents();
        }

        createModal() {
            const html = `
                <div class="fp-modal-backdrop" id="${this.modalId}">
                    <div class="fp-modal-container">
                        <!-- Toast Notification -->
                        <div class="fp-toast" id="fp-toast"></div>
                        
                        <div class="fp-modal-header">
                            <h3 class="fp-modal-title" id="fp-title">Medya Kütüphanesi</h3>
                            <div class="fp-close-btn" id="fp-close-trigger">&times;</div>
                        </div>

                        <div class="fp-modal-tabs">
                            <div class="fp-tab active" data-target="fp-library">Dosyalarım</div>
                            <div class="fp-tab" data-target="fp-upload">Yeni Yükle</div>
                        </div>

                        <div class="fp-modal-content">
                            <!-- Library Panel -->
                            <div class="fp-panel active" id="fp-library">
                                <div class="fp-loading-overlay" id="fp-loader-library">
                                    <div class="fp-spinner"></div>
                                </div>
                                
                                <div class="fp-control-bar">
                                    <div class="fp-search-wrapper">
                                        <span class="fp-search-icon">🔍</span>
                                        <input type="text" class="fp-search-input" id="fp-search-input" placeholder="Dosya ara...">
                                    </div>
                                </div>
                                
                                <div class="fp-grid" id="fp-library-grid"></div>

                                <div class="fp-pagination" id="fp-pagination">
                                    <button class="fp-page-btn" id="fp-prev-page">Geri</button>
                                    <span class="fp-page-info">Sayfa <span id="fp-current-page">1</span></span>
                                    <button class="fp-page-btn" id="fp-next-page">İleri</button>
                                </div>
                            </div>

                            <!-- Upload Panel -->
                            <div class="fp-panel" id="fp-upload">
                                <div class="fp-loading-overlay" id="fp-loader-upload">
                                    <div class="fp-spinner"></div>
                                </div>

                                <div class="fp-upload-area" id="fp-dropzone">
                                    <div class="fp-upload-icon">+</div>
                                    <p style="font-weight: 600; color: #1e293b; font-size: 1.1rem;">Dosyaları Sürükleyin veya Seçin</p>
                                    <p style="color: #64748b; margin-top: 0.5rem;">Resim dosyaları (Max 5MB)</p>
                                    
                                    <div class="fp-progress-container" id="fp-upload-progress">
                                        <div class="fp-progress-bar" id="fp-progress-inner"></div>
                                    </div>
                                    
                                    <input type="file" style="display:none" id="fp-file-input-global" accept="image/*">
                                </div>
                            </div>
                        </div>

                        <div class="fp-modal-footer">
                            <button class="fp-btn fp-btn-secondary" id="fp-cancel-trigger">Vazgeç</button>
                            <button class="fp-btn fp-btn-primary" id="fp-confirm-trigger">Seçimi Tamamla</button>
                        </div>
                    </div>
                </div>
            `;

            if ($('#' + this.modalId).length === 0) {
                $('body').append(html);
            }
            this.$modal = $('#' + this.modalId);
        }

        bindEvents() {
            const self = this;

            this.$modal.on('click', '.fp-tab', function () {
                const target = $(this).data('target');
                self.$modal.find('.fp-tab').removeClass('active');
                $(this).addClass('active');
                self.$modal.find('.fp-panel').removeClass('active');
                const $targetPanel = $('#' + target).addClass('active');

                if (target === 'fp-library') {
                    self.state.page = 1;
                    self.fetchLibrary();
                }
            });

            let searchTimer;
            this.$modal.on('input', '#fp-search-input', function () {
                clearTimeout(searchTimer);
                self.state.search = $(this).val();
                searchTimer = setTimeout(() => {
                    self.state.page = 1;
                    self.fetchLibrary();
                }, 500);
            });

            this.$modal.on('click', '#fp-prev-page', () => {
                if (this.state.page > 1) {
                    this.state.page--;
                    this.fetchLibrary();
                }
            });

            this.$modal.on('click', '#fp-next-page', () => {
                this.state.page++;
                this.fetchLibrary();
            });

            // Select item (stop if clicking delete)
            this.$modal.on('click', '.fp-item', function (e) {
                if ($(e.target).closest('.fp-item-delete').length > 0) return;

                if (self.currentSettings.multiple) {
                    $(this).toggleClass('selected');
                } else {
                    self.$modal.find('.fp-item').removeClass('selected');
                    $(this).addClass('selected');
                }
            });

            // Delete item
            this.$modal.on('click', '.fp-item-delete', function (e) {
                e.preventDefault();
                const $item = $(this).closest('.fp-item');
                const fileId = $item.data('id');
                const fileName = $item.attr('title');

                if (confirm(`"${fileName}" dosyasını silmek istediğinize emin misiniz?`)) {
                    self.deleteFile(fileId, $item);
                }
            });

            // Upload triggers
            this.$modal.on('click', '#fp-dropzone', function (e) {
                if (e.target.id !== 'fp-file-input-global') {
                    $('#fp-file-input-global').click();
                }
            });

            this.$modal.on('change', '#fp-file-input-global', function () {
                if (this.files && this.files.length > 0) {
                    self.uploadFiles(this.files);
                }
            });

            this.$modal.on('click', '#fp-confirm-trigger', () => {
                const $selected = this.$modal.find('.fp-item.selected');
                if ($selected.length > 0) {
                    const urls = $selected.map(function () { return $(this).data('url'); }).get();
                    this.completeSelection(self.currentSettings.multiple ? urls : urls[0]);
                } else {
                    this.notify('Lütfen en az bir dosya seçin!', 'error');
                }
            });

            this.$modal.on('click', '#fp-close-trigger, #fp-cancel-trigger', () => this.close());
            this.$modal.on('click', (e) => {
                if ($(e.target).hasClass('fp-modal-backdrop')) this.close();
            });
        }

        open(options) {
            this.currentSettings = $.extend({
                title: 'Medya Kütüphanesi',
                apiUrl: '/api/library',
                uploadUrl: '/api/upload',
                deleteUrl: '/api/delete',
                multiple: false,
                inputTarget: null,
                previewTarget: null,
                onSelect: null
            }, options);

            $('#fp-title').text(this.currentSettings.title);

            // Set multiple attribute for file input
            const $fileInput = $('#fp-file-input-global');
            if (this.currentSettings.multiple) {
                $fileInput.attr('multiple', 'multiple');
            } else {
                $fileInput.removeAttr('multiple');
            }

            this.$modal.addClass('active');
            $('body').css('overflow', 'hidden');
            this.state.page = 1;
            this.state.search = '';
            $('#fp-search-input').val('');
            this.fetchLibrary();
        }

        close() {
            this.$modal.removeClass('active');
            $('body').css('overflow', '');
        }

        setLoading(panel, loading) {
            const $loader = (panel === 'library') ? $('#fp-loader-library') : $('#fp-loader-upload');
            $loader.toggleClass('active', loading);
        }

        notify(message, type = 'success') {
            const $toast = $('#fp-toast');
            $toast.removeClass('fp-toast-error fp-toast-success').addClass(`fp-toast-${type}`).text(message).addClass('active');

            setTimeout(() => {
                $toast.removeClass('active');
            }, 3000);
        }

        fetchLibrary() {
            this.setLoading('library', true);
            const params = {
                page: this.state.page,
                search: this.state.search,
                per_page: this.state.perPage
            };

            $.ajax({
                url: this.currentSettings.apiUrl,
                method: 'GET',
                data: params,
                dataType: 'json',
                success: (response) => {
                    const files = response.data || response;
                    this.renderLibrary(Array.isArray(files) ? files : []);
                    this.updatePagination(response.meta || {});
                },
                error: (err) => {
                    console.error('Library fetch failed:', err);
                    this.notify('Dosya listesi alınamadı!', 'error');
                    this.showError('Bağlantı hatası.');
                },
                complete: () => this.setLoading('library', false)
            });
        }

        uploadFiles(files) {
            const formData = new FormData();

            if (files.length === 1) {
                formData.append('file', files[0]);
            } else {
                for (let i = 0; i < files.length; i++) {
                    formData.append('files[]', files[i]);
                }
            }

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
                        if (evt.lengthComputable) {
                            const percentComplete = (evt.loaded / evt.total) * 100;
                            $bar.css('width', percentComplete + '%');
                        }
                    }, false);
                    return xhr;
                },
                success: (response) => {
                    this.notify('Yükleme başarılı!', 'success');
                    this.state.page = 1;
                    this.state.search = '';
                    $('#fp-search-input').val('');
                    $('.fp-tab[data-target="fp-library"]').click();
                },
                error: (err) => {
                    this.notify('Yükleme sırasında bir hata oluştu!', 'error');
                    console.error('Upload failed:', err);
                },
                complete: () => {
                    this.setLoading('upload', false);
                    setTimeout(() => $progress.hide(), 1000);
                }
            });
        }

        deleteFile(id, $element) {
            this.setLoading('library', true);
            $.ajax({
                url: this.currentSettings.deleteUrl,
                method: 'POST',
                data: { id: id },
                dataType: 'json',
                success: (response) => {
                    this.notify('Dosya silindi.', 'success');
                    $element.fadeOut(300, () => $element.remove());
                },
                error: (err) => {
                    console.error('Delete failed:', err);
                    this.notify('Dosya silinemedi!', 'error');
                },
                complete: () => this.setLoading('library', false)
            });
        }

        renderLibrary(items) {
            const $grid = $('#fp-library-grid').empty();
            if (items.length === 0) {
                $grid.append('<div class="fp-empty-state">Henüz dosya bulunmuyor.</div>');
                return;
            }

            items.forEach(item => {
                const html = `
                    <div class="fp-item" data-id="${item.id}" data-url="${item.url}" title="${item.name}">
                        <div class="fp-item-delete">🗑️</div>
                        <img src="${item.thumb || item.url}" alt="${item.name}">
                    </div>
                `;
                $grid.append(html);
            });
        }

        updatePagination(meta) {
            const lastPage = meta.last_page || 1;
            const $pagination = $('#fp-pagination');

            if (lastPage <= 1) {
                $pagination.hide();
            } else {
                $pagination.show();
                $('#fp-current-page').text(this.state.page);
                $('#fp-prev-page').prop('disabled', this.state.page <= 1);
                $('#fp-next-page').prop('disabled', this.state.page >= lastPage);
            }
        }

        completeSelection(urls) {
            const value = Array.isArray(urls) ? urls.join(',') : urls;

            if (this.currentSettings.inputTarget) $(this.currentSettings.inputTarget).val(value);

            if (this.currentSettings.previewTarget) {
                const $preview = $(this.currentSettings.previewTarget);
                const firstUrl = Array.isArray(urls) ? urls[0] : urls;
                $preview.attr('src', firstUrl).show();
                $preview.siblings().hide();
            }

            if (typeof this.currentSettings.onSelect === 'function') this.currentSettings.onSelect(urls);
            this.close();
        }

        showError(message) {
            const $grid = $('#fp-library-grid').empty();
            $grid.append(`<div class="fp-empty-state" style="color: #ef4444;">${message}</div>`);
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
