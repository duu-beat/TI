import './bootstrap';
import collapse from '@alpinejs/collapse'; // Importa apenas o plugin
import 'cropperjs/dist/cropper.css'; 
import Cropper from 'cropperjs';

// Ouve o evento de inicialização do Alpine (disparado pelo Livewire)
document.addEventListener('alpine:init', () => {
    
    // 1. Regista o plugin Collapse para o FAQ
    Alpine.plugin(collapse);

    // 2. Define o componente do Cropper (o teu código original)
    Alpine.data('photoCropper', (wire) => ({
        cropping: false,
        cropper: null,
        imageUrl: null,
        photoPreview: null,
        loading: false,

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

        startCropper() {
            this.cropping = true;
            // Usa $nextTick para garantir que o modal existe no DOM
            this.$nextTick(() => {
                const imageElement = this.$refs.cropperImage;
                
                if (this.cropper) { 
                    this.cropper.destroy(); 
                    this.cropper = null;
                }

                if (imageElement) {
                    imageElement.src = this.imageUrl;
                    imageElement.classList.remove('opacity-0');

                    this.cropper = new Cropper(imageElement, {
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
                this.$refs.cropperImage.src = "";
                this.$refs.cropperImage.classList.add('opacity-0');
            }
        },

        cropAndSave() {
            if (!this.cropper || this.loading) return;
            this.loading = true;

            this.cropper.getCroppedCanvas({
                width: 600, 
                height: 600,
                fillColor: '#ffffff',
                imageSmoothingEnabled: true,
                imageSmoothingQuality: 'high',
            }).toBlob((blob) => {
                this.photoPreview = URL.createObjectURL(blob);

                if (!wire) {
                    console.error('Livewire não conectado.');
                    this.loading = false;
                    return;
                }

                wire.upload('photo', blob, 
                    () => { this.cancelCrop(); }, 
                    () => { 
                        alert('Erro no upload.'); 
                        this.loading = false; 
                    }
                );
            }, 'image/jpeg', 0.9);
        }
    }));
});