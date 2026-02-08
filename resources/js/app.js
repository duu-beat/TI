import './bootstrap';
import collapse from '@alpinejs/collapse';

document.addEventListener('alpine:init', () => {
    Alpine.plugin(collapse);

    Alpine.data('photoCropper', (wire) => ({
        cropping: false,
        cropper: null,
        cropperClass: null,
        cropperModulePromise: null,
        imageUrl: null,
        photoPreview: null,
        previewObjectUrl: null,
        loading: false,

        async ensureCropperLoaded() {
            if (this.cropperClass) return true;

            if (!this.cropperModulePromise) {
                this.cropperModulePromise = Promise.all([
                    import('cropperjs'),
                    import('cropperjs/dist/cropper.css'),
                ])
                    .then(([cropperModule]) => {
                        this.cropperClass = cropperModule.default;
                        return true;
                    })
                    .catch((error) => {
                        console.error('Falha ao carregar o editor de imagem.', error);
                        this.cropperModulePromise = null;
                        return false;
                    });
            }

            return this.cropperModulePromise;
        },

        selectFile(e) {
            const file = e.target.files[0];
            if (!file || !file.type.includes('image/')) return;

            const reader = new FileReader();
            reader.onload = (event) => {
                this.imageUrl = event.target.result;
                this.startCropper();
            };
            reader.readAsDataURL(file);
        },

        async startCropper() {
            const loaded = await this.ensureCropperLoaded();
            if (!loaded) return;

            this.cropping = true;
            this.$nextTick(() => {
                const imageElement = this.$refs.cropperImage;

                if (this.cropper) {
                    this.cropper.destroy();
                    this.cropper = null;
                }

                if (imageElement) {
                    imageElement.src = this.imageUrl;
                    imageElement.classList.remove('opacity-0');

                    this.cropper = new this.cropperClass(imageElement, {
                        aspectRatio: 1,
                        viewMode: 1,
                        dragMode: 'move',
                        autoCropArea: 0.9,
                        guides: false,
                        center: true,
                        highlight: false,
                        background: false,
                        modal: true,
                        movable: true,
                        zoomable: true,
                        scalable: false,
                        rotatable: false,
                    });
                }
            });
        },

        updatePreviewUrl(blob) {
            if (this.previewObjectUrl) {
                URL.revokeObjectURL(this.previewObjectUrl);
            }

            this.previewObjectUrl = URL.createObjectURL(blob);
            this.photoPreview = this.previewObjectUrl;
        },

        cancelCrop() {
            this.cropping = false;

            if (this.cropper) {
                this.cropper.destroy();
                this.cropper = null;
            }

            this.imageUrl = null;
            this.loading = false;

            if (this.$refs.photoInput) {
                this.$refs.photoInput.value = null;
            }

            if (this.$refs.cropperImage) {
                this.$refs.cropperImage.src = '';
                this.$refs.cropperImage.classList.add('opacity-0');
            }
        },

        cropAndSave() {
            if (!this.cropper || this.loading) return;
            this.loading = true;

            this.cropper
                .getCroppedCanvas({
                    width: 600,
                    height: 600,
                    fillColor: '#ffffff',
                    imageSmoothingEnabled: true,
                    imageSmoothingQuality: 'high',
                })
                .toBlob((blob) => {
                    if (!blob) {
                        alert('Não foi possível processar a imagem.');
                        this.loading = false;
                        return;
                    }

                    this.updatePreviewUrl(blob);

                    if (!wire) {
                        console.error('Livewire não conectado.');
                        this.loading = false;
                        return;
                    }

                    wire.upload(
                        'photo',
                        blob,
                        () => {
                            this.cancelCrop();
                        },
                        () => {
                            alert('Erro no upload.');
                            this.loading = false;
                        }
                    );
                }, 'image/jpeg', 0.9);
        },
    }));
});