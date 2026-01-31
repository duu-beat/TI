import './bootstrap';
import 'cropperjs/dist/cropper.css'; 
import Cropper from 'cropperjs';

const initPhotoCropper = () => {
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
            
            // Espera o modal (display: block) renderizar para iniciar o Cropper
            setTimeout(() => {
                const imageElement = this.$refs.cropperImage;
                
                // Limpa instância anterior se existir
                if (this.cropper) { 
                    this.cropper.destroy(); 
                    this.cropper = null;
                }

                if (imageElement) {
                    imageElement.src = this.imageUrl;
                    
                    // Remove opacidade para mostrar a imagem só agora
                    imageElement.classList.remove('opacity-0');

                    this.cropper = new Cropper(imageElement, {
                        aspectRatio: 1, // Quadrado
                        viewMode: 1,    // Restringe o corte dentro da tela
                        dragMode: 'move',
                        autoCropArea: 0.9,
                        guides: false,        // Sem grades
                        center: true,
                        highlight: false,
                        background: false,    // Tenta remover fundo padrão
                        modal: true,          // Escurece em volta
                        movable: true,
                        zoomable: true,
                        scalable: false,
                        rotatable: false,
                    });
                }
            }, 100); 
        },

        cancelCrop() {
            this.cropping = false;
            if (this.cropper) { 
                this.cropper.destroy(); 
                this.cropper = null;
            }
            this.imageUrl = null;
            this.loading = false;
            
            // Reseta input e esconde imagem novamente
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
                fillColor: '#ffffff', // Fundo branco caso png transparente
                imageSmoothingEnabled: true,
                imageSmoothingQuality: 'high',
            }).toBlob((blob) => {
                // Preview imediato na tela
                this.photoPreview = URL.createObjectURL(blob);

                if (!wire) {
                    alert('Erro: Livewire não conectado.');
                    this.loading = false;
                    return;
                }

                wire.upload('photo', blob, 
                    () => { this.cancelCrop(); }, // Sucesso
                    () => { 
                        alert('Erro no upload.'); 
                        this.loading = false; 
                    }
                );
            }, 'image/jpeg', 0.9);
        }
    }));
};

if (typeof window.Alpine !== 'undefined') {
    initPhotoCropper();
} else {
    document.addEventListener('alpine:init', initPhotoCropper);
}