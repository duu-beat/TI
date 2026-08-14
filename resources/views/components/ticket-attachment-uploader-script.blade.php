<script>
    window.attachmentUploader = (options = {}) => ({
        files: [],
        dragover: false,
        errors: [],
        maxFiles: options.maxFiles ?? 5,
        maxSize: options.maxSize ?? 10 * 1024 * 1024,
        allowedMimes: [
            'image/jpeg', 'image/png', 'image/webp', 'application/pdf', 'text/plain',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/zip',
        ],
        allowedExtensions: ['jpg', 'jpeg', 'png', 'webp', 'pdf', 'txt', 'doc', 'docx', 'xls', 'xlsx', 'zip'],

        handleDrop(event) {
            this.dragover = false;
            this.handleFiles(event.dataTransfer.files);
        },

        handleFiles(fileList) {
            this.errors = [];

            Array.from(fileList).forEach((file) => {
                const extension = file.name.split('.').pop()?.toLowerCase();
                const alreadySelected = this.files.some((item) => item.file.name === file.name && item.file.size === file.size);

                if (this.files.length >= this.maxFiles) {
                    this.errors.push(`Limite de ${this.maxFiles} arquivos atingido.`);
                    return;
                }

                if (!this.allowedMimes.includes(file.type) && !this.allowedExtensions.includes(extension)) {
                    this.errors.push(`${file.name}: formato não permitido.`);
                    return;
                }

                if (file.size > this.maxSize) {
                    this.errors.push(`${file.name}: excede ${this.formatBytes(this.maxSize)}.`);
                    return;
                }

                if (alreadySelected) {
                    this.errors.push(`${file.name}: já foi selecionado.`);
                    return;
                }

                const isImage = file.type.startsWith('image/');
                const isPdf = file.type === 'application/pdf' || extension === 'pdf';

                this.files.push({
                    file,
                    isImage,
                    isPdf,
                    preview: (isImage || isPdf) ? URL.createObjectURL(file) : null,
                });
            });

            this.syncInput();
        },

        removeFile(index) {
            const item = this.files[index];

            if (item?.preview) {
                URL.revokeObjectURL(item.preview);
            }

            this.files.splice(index, 1);
            this.syncInput();
        },

        syncInput() {
            const dataTransfer = new DataTransfer();
            this.files.forEach((item) => dataTransfer.items.add(item.file));
            this.$refs.attachmentsInput.files = dataTransfer.files;
        },

        formatBytes(bytes) {
            if (bytes < 1024) return `${bytes} B`;
            if (bytes < 1024 * 1024) return `${Math.round(bytes / 1024)} KB`;

            return `${(bytes / (1024 * 1024)).toFixed(1)} MB`;
        },

        fileKind(item) {
            if (item.isImage) return 'Imagem';
            if (item.isPdf) return 'PDF';

            return 'Arquivo';
        },
    });
</script>
