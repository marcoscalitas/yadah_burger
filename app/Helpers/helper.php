<?php

use App\Services\UploadService;
use Illuminate\Http\Request;
use Carbon\Carbon;

if (! function_exists('getBreadcrumb')) {
    /**
     * Retorna o breadcrumb configurado com as rotas resolvidas.
     *
     * @param  string  $key  Nome da trail (ex: 'admin.users.show')
     * @param  array  $extraItems  Itens extras (ex: [['label' => 'João Silva']])
     * @param  string|null  $customTitle  Sobrescreve o título padrão (opcional)
     */
    function getBreadcrumb(string $key, array $extraItems = [], ?string $customTitle = null): array
    {
        $breadcrumbs = config('breadcrumbs');
        $config = $breadcrumbs[$key] ?? null;

        if (! $config) {
            return [
                'title' => $customTitle ?? 'Página',
                'items' => [['label' => 'Home', 'url' => route('admin.index')]],
            ];
        }

        // Resolve rotas para URLs
        $items = array_map(function ($item) {
            if (isset($item['route'])) {
                $params = $item['route_params'] ?? [];
                $item['url'] = route($item['route'], $params);
            }

            return $item;
        }, $config['items']);

        // Junta com itens extras (ex: nome do utilizador)
        $items = array_merge($items, $extraItems);

        return [
            'title' => $customTitle ?? ($config['title'] ?? 'Página'),
            'items' => $items,
        ];
    }
}

// handlePhotoUpload
if (! function_exists('handlePhotoUpload')) {
    function handlePhotoUpload(Request $request, string $folder, string $field): string
    {
        try {
            $fileData = (new UploadService)->configure([
                'mimes' => ['jpg', 'jpeg', 'png'],
                'maxSizeMB' => 3,
                'folder' => $folder,
                'disk' => 'public',
            ])->upload($request->file($field));

            return $fileData['path'];
        } catch (\Throwable $e) {
            Log::error('Erro no upload da foto: ' . $e->getMessage());
            throw new \Exception('Erro ao enviar foto: ' . $e->getMessage());
        }
    }
}

// getStatusBadge
if (! function_exists('getStatusBadge')) {
    function getStatusBadge($status): string
    {
        $badge = fn($class, $label) => "<span class=\"badge text-{$class}-500 bg-{$class}-500/15\">$label</span>";

        $badges = [
            'p' => $badge('warning', 'Pendente'),
            'sp' => $badge('warning', 'Suspenso'),
            'i' => $badge('warning', 'Inativo'),
            'a' => $badge('success', 'Ativado'),
            'st' => $badge('primary', 'Iniciado'),
            'c' => $badge('success', 'Concluído'),
            'x' => $badge('danger', 'Cancelado'),
            'd' => $badge('danger', 'Apagado'),
        ];

        return $badges[$status] ?? $badge('dark', '-');
    }
}


// getStatusBadge
if (! function_exists('getFormattedPhone')) {
    function getFormattedPhone($phone, $code = true): string
    {
        if (empty($phone)) {
            return '-';
        }

        $digits = preg_replace('/\D/', '', $phone);

        if (strlen($digits) !== 9) {
            return $phone;
        }

        $part1 = substr($digits, 0, 3);
        $part2 = substr($digits, 3, 3);
        $part3 = substr($digits, 6, 3);

        return ($code) ? "+244 {$part1}-{$part2}-{$part3}" : "{$part1}-{$part2}-{$part3}";
    }
}


// Get current User
if (! function_exists('getCurrentUser')) {
    function getCurrentUser($role = '')
    {
        $user = auth($role)->user() ?? auth()->user();

        return $user;
    }
}

// isAdmin
if (! function_exists('isAdmin')) {
    function isAdmin(): bool
    {
        $user = getCurrentUser('admin');

        return $user && $user->role === 'admin';
    }
}

// isRouteActive - Verifica se uma rota ou grupo de rotas está ativo
if (! function_exists('isRouteActive')) {
    /**
     * Verifica se a rota atual corresponde a um ou mais padrões fornecidos.
     * Aceita wildcards usando o caractere '*'.
     */
    function isRouteActive(array|string $routePatterns): bool
    {
        $currentRoute = \Route::currentRouteName();

        if (empty($currentRoute)) {
            return false;
        }

        // Garante que estamos lidando com um array
        $patterns = is_array($routePatterns) ? $routePatterns : [$routePatterns];

        foreach ($patterns as $pattern) {
            // Se contém asterisco, trata como wildcard
            if (str_contains($pattern, '*')) {
                // Escapa os caracteres especiais e substitui * por .*
                $escaped = preg_quote($pattern, '/');
                $regex = '/^' . str_replace('\*', '.*', $escaped) . '$/';

                if (preg_match($regex, $currentRoute)) {
                    return true;
                }
            }
            // Verificação exata
            elseif ($currentRoute === $pattern) {
                return true;
            }
        }

        return false;
    }
}

// getActiveClass - Retorna a classe CSS se a rota estiver ativa
if (! function_exists('getActiveClass')) {
    /**
     * Retorna uma classe CSS com base no estado ativo da rota.
     */
    function getActiveClass(array|string $routePatterns, string $activeClass = 'active', string $inactiveClass = ''): string
    {
        return isRouteActive($routePatterns) ? $activeClass : $inactiveClass;
    }
}

// get Formatted Date
if (! function_exists('getFormattedDate')) {
    function getFormattedDate($value, string $format = 'd-m-Y'): ?string
    {
        if (! $value) {
            return null;
        }

        if ($value instanceof Carbon) {
            $date = $value;
        } else {
            try {
                $date = Carbon::parse($value);
            } catch (\Exception $e) {
                return null;
            }
        }

        return $date->format($format);
    }
}

// Check if is Admin
if (! function_exists('checkIfIsAdmin')) {
    function checkIfIsAdmin(string $crudAction, string $entity)
    {
        if (! isAdmin()) {
            return abort(403, "Acesso negado. Apenas administradores podem {$crudAction} {$entity}.");
        }
    }
}

// Handle User Email Update - Processa atualização de email com verificação automática
if (! function_exists('handleUserEmailUpdate')) {
    /**
     * Processa a atualização de dados do usuário, verificando mudança de email
     * e aplicando reset de status e envio de verificação automaticamente.
     *
     * @return string Mensagem de sucesso
     */
    function handleUserEmailUpdate(\App\Models\User $user, array $data, bool $isSelfUpdate = false, ?int $updatedBy = null): string
    {
        $originalEmail = $user->email;
        $emailChanged = $originalEmail !== ($data['email'] ?? $originalEmail);

        // Reset status se email mudou
        if ($emailChanged) {
            $data['user_status'] = 'p';
            $data['email_verified_at'] = null;
            session(['is_email_verified' => false]);

            // SECURITY FIX: Invalidar todos os tokens antigos
            invalidateOldVerificationTokens($user->id);
            invalidatePasswordResetTokens($originalEmail);
        } // Atualizar usuário

        $user->update($data);

        // Se email mudou, enviar verificação e log
        if ($emailChanged) {
            event(new \Illuminate\Auth\Events\Registered($user));

            $logData = [
                'user_id' => $user->id,
                'old_email' => $originalEmail,
                'new_email' => $data['email'],
            ];

            if ($isSelfUpdate) {
                $logData['self_update'] = true;
                $logMessage = 'Email do próprio utilizador alterado - Status resetado e email de verificação enviado';
            } else {
                $logData['updated_by'] = $updatedBy;
                $logMessage = 'Email do utilizador alterado - Status resetado e email de verificação enviado';
            }

            \Illuminate\Support\Facades\Log::info($logMessage, $logData);
        }

        // Retornar mensagem apropriada
        $baseMessage = $isSelfUpdate ? 'Perfil atualizado com sucesso' : 'Utilizador atualizado com sucesso';

        return $emailChanged ? $baseMessage . '! Email de verificação enviado para o novo endereço.' : $baseMessage . '!';
    }
}

// Invalidate Old Verification Tokens - Invalida tokens de verificação antigos
if (! function_exists('invalidateOldVerificationTokens')) {
    /**
     * Invalida todos os tokens de verificação de email antigos para um usuário.
     */
    function invalidateOldVerificationTokens(int $userId): void
    {
        try {
            \Illuminate\Support\Facades\DB::table('email_verification_tokens')
                ->where('user_id', $userId)
                ->where('used', false)
                ->update(['used' => true, 'updated_at' => now()]);

            \Illuminate\Support\Facades\Log::info('Tokens de verificação antigos invalidados', [
                'user_id' => $userId,
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('Erro ao invalidar tokens antigos: ' . $e->getMessage(), [
                'user_id' => $userId,
            ]);
        }
    }
}

// Invalidate Password Reset Tokens - Invalida tokens de reset de senha
if (! function_exists('invalidatePasswordResetTokens')) {
    /**
     * Invalida todos os tokens de reset de senha para um email específico.
     */
    function invalidatePasswordResetTokens(string $email): void
    {
        try {
            $deleted = \Illuminate\Support\Facades\DB::table('password_reset_tokens')
                ->where('email', $email)
                ->delete();

            if ($deleted > 0) {
                \Illuminate\Support\Facades\Log::info('Tokens de reset de senha invalidados', [
                    'email' => $email,
                    'tokens_deleted' => $deleted,
                ]);
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('Erro ao invalidar tokens de reset: ' . $e->getMessage(), [
                'email' => $email,
            ]);
        }
    }
}

if (! function_exists('fileExists')) {
    /**
     * Verifica se um arquivo existe no caminho especificado.
     *
     * Suporta diferentes tipos de caminhos:
     * - Caminhos absolutos do sistema
     * - Caminhos relativos ao storage/app/public
     * - URLs de storage público
     * - Caminhos de assets públicos
     *
     * @param  string  $path  Caminho do arquivo
     * @param  string  $disk  Disco de armazenamento (padrão: 'public')
     */
    function fileExists(string $path, string $disk = 'public'): bool
    {
        // Se estiver vazio, retorna false
        if (empty($path)) {
            return false;
        }

        // Se for um URL completo (http/https), verifica se o arquivo existe via cURL
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return urlExists($path);
        }

        // Se for um caminho absoluto no sistema
        if (file_exists($path)) {
            return true;
        }

        // Se começar com /storage/, remove o prefixo para verificar no disco
        if (str_starts_with($path, '/storage/')) {
            $path = str_replace('/storage/', '', $path);
        }

        // Verifica no disco especificado (normalmente 'public')
        try {
            return \Illuminate\Support\Facades\Storage::disk($disk)->exists($path);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('Erro ao verificar existência do arquivo: ' . $e->getMessage(), [
                'path' => $path,
                'disk' => $disk,
            ]);

            return false;
        }
    }
}

if (! function_exists('urlExists')) {
    /**
     * Verifica se uma URL existe fazendo uma requisição HEAD.
     * Usa diferentes métodos dependendo da disponibilidade das extensões.
     *
     * @param  string  $url  URL para verificar
     */
    function urlExists(string $url): bool
    {
        try {
            // Tenta usar cURL se disponível
            if (function_exists('curl_init')) {
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_NOBODY, true);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

                curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                return $httpCode >= 200 && $httpCode < 400;
            }

            // Fallback usando file_get_contents com context
            $context = stream_context_create([
                'http' => [
                    'method' => 'HEAD',
                    'timeout' => 10,
                    'ignore_errors' => true,
                ],
            ]);

            $headers = @get_headers($url, 1, $context);
            if ($headers) {
                $httpCode = (int) substr($headers[0], 9, 3);

                return $httpCode >= 200 && $httpCode < 400;
            }

            return false;
        } catch (\Exception $e) {
            return false;
        }
    }
}
if (! function_exists('getFileInfo')) {
    /**
     * Retorna informações detalhadas sobre um arquivo.
     *
     * @param  string  $path  Caminho do arquivo
     * @param  string  $disk  Disco de armazenamento (padrão: 'public')
     */
    function getFileInfo(string $path, string $disk = 'public'): ?array
    {
        if (! fileExists($path, $disk)) {
            return null;
        }

        try {
            // Se for um caminho absoluto
            if (file_exists($path)) {
                return [
                    'exists' => true,
                    'path' => $path,
                    'size' => filesize($path),
                    'size_human' => formatBytes(filesize($path)),
                    'modified' => filemtime($path),
                    'modified_human' => date('d/m/Y H:i:s', filemtime($path)),
                    'extension' => pathinfo($path, PATHINFO_EXTENSION),
                    'basename' => basename($path),
                    'type' => 'local',
                ];
            }

            // Se for no storage
            $storage = \Illuminate\Support\Facades\Storage::disk($disk);

            if (str_starts_with($path, '/storage/')) {
                $path = str_replace('/storage/', '', $path);
            }

            return [
                'exists' => true,
                'path' => $path,
                'full_path' => $storage->path($path),
                'url' => $storage->url($path),
                'size' => $storage->size($path),
                'size_human' => formatBytes($storage->size($path)),
                'modified' => $storage->lastModified($path),
                'modified_human' => date('d/m/Y H:i:s', $storage->lastModified($path)),
                'extension' => pathinfo($path, PATHINFO_EXTENSION),
                'basename' => basename($path),
                'type' => 'storage',
            ];
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('Erro ao obter informações do arquivo: ' . $e->getMessage(), [
                'path' => $path,
                'disk' => $disk,
            ]);

            return null;
        }
    }
}

if (! function_exists('formatBytes')) {
    /**
     * Converte bytes para formato legível (KB, MB, GB, etc.).
     *
     * @param  int  $size  Tamanho em bytes
     * @param  int  $precision  Precisão decimal
     */
    function formatBytes(int $size, int $precision = 2): string
    {
        if ($size === 0) {
            return '0 B';
        }

        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
        $i = 0;

        while ($size >= 1024 && $i < count($units) - 1) {
            $size /= 1024;
            $i++;
        }

        return round($size, $precision) . ' ' . $units[$i];
    }
}

/**
 * Validate Password
 */
if (! function_exists('validatePassword')) {
    function validatePassword(string $password, callable $fail): void
    {
        if (! preg_match('/[a-z]/', $password)) {
            $fail('A senha deve conter pelo menos uma letra minúscula.');
        }

        if (! preg_match('/[A-Z]/', $password)) {
            $fail('A senha deve conter pelo menos uma letra maiúscula.');
        }

        if (! preg_match('/[0-9]/', $password)) {
            $fail('A senha deve conter pelo menos um número.');
        }

        if (! preg_match('/[@$!%*#?&]/', $password)) {
            $fail('A senha deve conter pelo menos um caractere especial (@$!%*#?&).');
        }

        if (! preg_match('/[a-zA-Z0-9@$!%*#?&]/', $password)) {
            $fail('A senha deve conter apenas caracteres válidos (letras, números e caracteres especiais).');
        }
    }
}

/**
 * Formata uma data ou datetime para exibição
 *
 * @param  \Carbon\Carbon|string|null  $datetime
 * @param  string  $type  Tipo de formatação: 'text' (d/m/Y), 'input' (Y-m-d), 'datetime' (com hora)
 * @return string
 */
if (! function_exists('getFormattedDateTime')) {
    function getFormattedDateTime($datetime, string $type = 'text'): string
    {
        if (empty($datetime)) {
            return '-';
        }

        $date = is_string($datetime) ? \Carbon\Carbon::parse($datetime) : $datetime;

        $formats = [
            'text' => fn() => $date->format('d/m/Y'),
            'input' => fn() => $date->format('Y-m-d'),
            'datetime' => fn() => sprintf(
                '<div class="d-flex flex-column">
                    <span>%s</span>
                    <small class="text-muted">%s</small>
                </div>',
                $date->format('d/m/Y'),
                $date->format('H:i')
            ),
        ];

        return ($formats[$type] ?? $formats['text'])();
    }
}

/**
 * Retorna apenas o primeiro e último nome de um nome completo
 *
 * @param  string|null  $fullName
 * @return string
 */
if (! function_exists('getShortName')) {
    function getShortName(?string $fullName): string
    {
        if (empty($fullName)) {
            return '';
        }

        // Remove espaços extras e separa o nome em partes
        $nameParts = array_filter(explode(' ', trim($fullName)));

        // Se tiver apenas um nome, retorna ele
        if (count($nameParts) === 1) {
            return $nameParts[0];
        }

        // Retorna primeiro e último nome
        $firstName = reset($nameParts);
        $lastName = end($nameParts);

        return $firstName . ' ' . $lastName;
    }
}


/**
 * Retorna valor formatado em Kwanzas com estilo
 *
 * @param  float|int|null  $amount
 * @param  string  $color  Classe de cor (success, danger, warning, primary, etc)
 * @return string
 */
if (! function_exists('getFormattedCurrency')) {
    function getFormattedCurrency($amount, string $color = 'success'): string
    {
        if (is_null($amount)) {
            return '<span class="fw-bold text-muted">0,00 Kz</span>';
        }

        $formatted = number_format($amount, 2, ',', '.');

        return sprintf(
            '<span class="fw-bold text-%s">%s Kz</span>',
            $color,
            $formatted
        );
    }
}

/**
 * Get a truncated description for display in lists.
 */
if (! function_exists('getShortText')) {
    function getShortText(?string $text, int $length = 50): string
    {
        if (! $text || empty($text)) {
            return '-';
        }

        return strlen($text) > $length
            ? substr($text, 0, $length) . '...'
            : $text;
    }
}

// getProductPrice
if (! function_exists('getProductPrice')) {
    /**
     * Formata preços de produtos ou valores numéricos com cores inteligentes.
     *
     * Aceita:
     * - Objetos Product com price e promotion_price
     * - Valores numéricos (int, float, string numérica)
     * - Strings formatadas (ex: "50.000,00" ou "1.500,50")
     *
     * Cores automáticas:
     * - Valores negativos: texto vermelho (text-danger)
     * - Valores positivos/zero: texto verde (text-success)
     *
     * @param  \App\Models\Product|object|numeric|string  $input  O produto ou valor a ser formatado
     * @param  bool  $formatted  Se true, retorna formatado com HTML, se false retorna apenas o valor numérico
     * @param  bool  $withCurrency  Se true, adiciona "Kz" ao final (apenas quando $formatted = false)
     * @param  string|null  $customClass  Classes CSS personalizadas (sobrescreve cores automáticas)
     * @return string|float O preço formatado
     */
    function getProductPrice($input, bool $formatted = true, bool $withCurrency = false, ?string $customClass = null)
    {
        // Se for null ou vazio, retorna 0
        if (is_null($input) || $input === '') {
            return $formatted ? '<span class="text-success">0,00 Kz</span>' : 0.0;
        }

        // Caso 1: É um objeto (Product) com propriedades price/promotion_price
        if (is_object($input)) {
            $hasPromotion = !empty($input->promotion_price) && $input->promotion_price > 0;
            $price = $input->price ?? 0;
            $promotionPrice = $input->promotion_price ?? 0;

            // Converte strings formatadas para decimal se necessário
            $price = convertToDecimal($price);
            $promotionPrice = convertToDecimal($promotionPrice);

            if (!$formatted) {
                return $hasPromotion ? $promotionPrice : $price;
            }

            $priceFormatted = number_format($price, 2, ',', '.') . ' Kz';
            $promotionFormatted = number_format($promotionPrice, 2, ',', '.') . ' Kz';

            // Determina a cor baseada no valor
            $colorClass = $customClass ?? ($promotionPrice > 0 ? 'text-success' : ($promotionPrice < 0 ? 'text-danger' : 'text-success'));

            if ($hasPromotion) {
                return '<div class="flex flex-col">
                            <span class="fw-bold ' . $colorClass . '">' . $promotionFormatted . '</span>
                            <span class="text-gray-400 text-xs line-through">' . $priceFormatted . '</span>
                        </div>';
            }

            $colorClass = $customClass ?? ($price > 0 ? 'text-success' : ($price < 0 ? 'text-danger' : 'text-success'));
            return '<span class="fw-bold ' . $colorClass . '">' . $priceFormatted . '</span>';
        }

        // Caso 2: É um valor numérico ou string (preço simples)
        // Converte para decimal se for string formatada
        $value = is_numeric($input) ? (float) $input : convertToDecimal($input);

        // Retorna valor numérico sem formatação
        if (!$formatted) {
            return $withCurrency ? number_format(abs($value), 2, ',', '.') . ' Kz' : $value;
        }

        // Determina a cor baseada no valor (negativo = vermelho, positivo = verde)
        $colorClass = $customClass ?? ($value < 0 ? 'text-danger' : 'text-success');

        // Formata o valor
        $valueFormatted = number_format(abs($value), 2, ',', '.') . ' Kz';

        // Adiciona sinal de menos para valores negativos
        $prefix = $value < 0 ? '-' : '';

        return '<span class="fw-bold ' . $colorClass . '">' . $prefix . $valueFormatted . '</span>';
    }
}

// convertToDecimal
if (! function_exists('convertToDecimal')) {
    /**
     * Converte uma string de preço formatada (ex: "50.000,00" ou "1,11") para decimal (ex: "50000.00" ou "1.11").
     * Remove pontos de milhar e converte vírgula decimal para ponto.
     *
     * @param  string|float|null  $value  O valor a ser convertido
     * @return float O valor convertido para float
     */
    function convertToDecimal($value): float
    {
        if (is_null($value) || $value === '') {
            return 0.0;
        }

        // Se já for numérico, retorna como float
        if (is_numeric($value)) {
            return (float) $value;
        }

        // Remove pontos de milhar
        $value = str_replace('.', '', $value);
        // Troca vírgula decimal por ponto
        $value = str_replace(',', '.', $value);

        // Garante que é numérico
        return is_numeric($value) ? (float) $value : 0.0;
    }
}
