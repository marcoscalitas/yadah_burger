@section('content')
    <div class="col-span-12 lg:col-span-12">
        <div class="card">
            <div class="card-header">
                <h5>Envio de Foto (Arraste e solte)</h5>
            </div>
            <div class="card-body">
                <div id="files-drag-drop">
                    <div class="for-DragDrop"></div>
                    <div class="for-ProgressBar"></div>
                    <div class="uploaded-files mt-3">
                        <h5>Arquivos enviados:</h5>
                        <ol></ol>
                    </div>

                    <div class="alert text-center" id="photoMessage" style="display:none;">
                        <span id="photoMessageContent"></span>
                    </div>
                    <input type="file" name="photo" id="photo" hidden>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom-scripts')
    <script src="{{ asset('admin/assets/js/plugins/uppy.min.js') }}"></script>

    <script type="module">
        import {
            Uppy,
            DragDrop,
            ProgressBar
        } from 'https://releases.transloadit.com/uppy/v3.23.0/uppy.min.mjs';

        const uppy = new Uppy({
                debug: true,
                autoProceed: false,
                restrictions: {
                    maxNumberOfFiles: 1, // ⬅️ apenas 1 arquivo
                    allowedFileTypes: ['image/*'] // ⬅️ apenas imagens
                }
            })
            .use(DragDrop, {
                target: '#files-drag-drop .for-DragDrop',
                locale: {
                    strings: {
                        dropHereOr: 'Solte aqui ou Navegue',
                        browse: 'navegue', // 🔵 texto azul padrão do Uppy
                        dragAndDrop: 'Arraste e solte o ficheiro aqui',
                        dropHint: 'ou clique em "navegue" para escolher um ficheiro',
                        selectToUpload: 'Selecione o ficheiro para enviar',
                    }
                }
            })
            .use(ProgressBar, {
                target: '#files-drag-drop .for-ProgressBar'
            });

        // ✅ Quando o utilizador escolhe/arrasta um ficheiro
        uppy.on('file-added', (file) => {
            const alertId = 'photoMessage'; // id do container da mensagem
            const fileInput = document.getElementById('photo');

            // --- Remove arquivos anteriores ---
            uppy.getFiles().forEach(f => {
                if (f.id !== file.id) uppy.removeFile(f.id);
            });

            // --- Validação usando tua função global ---
            const {
                valid,
                message
            } = validateImage(file.data);
            if (!valid) {
                fileInput.value = '';
                uppy.removeFile(file.id);
                showError(message, alertId);
                return;
            }

            // --- Se for válido, continua ---
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file.data);
            fileInput.files = dataTransfer.files;

            // Mostra nome do ficheiro escolhido
            const list = document.querySelector('#files-drag-drop .uploaded-files ol');
            list.innerHTML = ''; // limpa antes de adicionar novo
            const li = document.createElement('li');
            li.textContent = file.name;
            list.appendChild(li);

            // Mensagem de sucesso (opcional)
            showMessage('Imagem válida e pronta para envio.', alertId, true);
        });
    </script>

    <script>
        $(document).ready(function() {});
    </script>
@endsection
