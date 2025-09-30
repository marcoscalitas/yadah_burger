<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class UploadService
{
    protected array $allowedMimes = [];
    protected int $maxSizeMB = 10; // padrão 10 MB
    protected string $folder = 'uploads';
    protected string $disk = 'public';

    /**
     * Configura a classe para o upload
     */
    public function configure(array $options = []): self
    {
        if (isset($options['mimes'])) $this->allowedMimes = $options['mimes'];
        if (isset($options['maxSizeMB'])) $this->maxSizeMB = $options['maxSizeMB'];
        if (isset($options['folder'])) $this->folder = $options['folder'];
        if (isset($options['disk'])) $this->disk = $options['disk'];

        // Adiciona subpastas por data automaticamente
        $this->folder .= '/' . date('Y/m/d');

        return $this;
    }

    /**
     * Faz upload do arquivo
     */
    public function upload(UploadedFile $file): array
    {
        $this->validateFile($file);

        $filename = $this->generateFilename($file);

        $path = $file->storeAs($this->folder, $filename, $this->disk);

        return [
            'path' => $path,
            'filename' => $filename,
            'folder' => $this->folder,
            'disk' => $this->disk,
            'url' => Storage::disk($this->disk)->url($path) // URL pública
        ];
    }

    /**
     * Remove arquivo
     */
    public function delete(string $path): bool
    {
        return Storage::disk($this->disk)->exists($path) && Storage::disk($this->disk)->delete($path);
    }

    /**
     * Valida arquivo antes de salvar
     */
    protected function validateFile(UploadedFile $file): void
    {
        $extension = strtolower($file->getClientOriginalExtension());

        // Tipo permitido (case-insensitive)
        if (!empty($this->allowedMimes) && !in_array($extension, array_map('strtolower', $this->allowedMimes))) {
            throw ValidationException::withMessages([
                'file' => 'Tipo de arquivo não permitido. Permitidos: ' . implode(', ', $this->allowedMimes)
            ]);
        }

        // Tamanho máximo
        $sizeMB = $file->getSize() / 1024 / 1024;
        if ($sizeMB > $this->maxSizeMB) {
            throw ValidationException::withMessages([
                'file' => "O arquivo excede o tamanho máximo de {$this->maxSizeMB} MB."
            ]);
        }
    }

    /**
     * Gera nome único
     */
    protected function generateFilename(UploadedFile $file): string
    {
        return time() . '_' . Str::random(12) . '.' . $file->getClientOriginalExtension();
    }
}
