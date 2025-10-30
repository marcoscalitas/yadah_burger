<?php

namespace App\Services;

use App\Models\Order;

class WhatsAppService
{
    /**
     * Gera um link do WhatsApp com a mensagem do pedido
     * O link abre o WhatsApp direcionado para o número da empresa
     * com a mensagem do pedido pronta para enviar
     *
     * @param Order $order
     * @return string
     */
    public function generateOrderLink(Order $order): string
    {
        // Número da empresa Yadah Burguer
        $businessPhone = $this->formatPhoneNumber(config('app.whatsapp_business_number'));
        $message = $this->generateOrderMessage($order);

        // Gera o link que abre o WhatsApp do cliente com a mensagem
        // direcionada para o número da empresa
        return "https://wa.me/{$businessPhone}?text=" . urlencode($message);
    }

    /**
     * Gera um link do WhatsApp API (whatsapp://send)
     * Útil para dispositivos móveis onde wa.me pode não funcionar bem
     *
     * @param Order $order
     * @return string
     */
    public function generateOrderApiLink(Order $order): string
    {
        $businessPhone = $this->formatPhoneNumber(config('app.whatsapp_business_number'));
        $message = $this->generateOrderMessage($order);

        return "whatsapp://send?phone={$businessPhone}&text=" . urlencode($message);
    }

    /**
     * Gera a mensagem formatada do pedido
     *
     * @param Order $order
     * @return string
     */
    public function generateOrderMessage(Order $order): string
    {
        $order->load(['orderItems.product']);

        // Cabeçalho do pedido
        $message = "================================\n";
        $message .= "🍔 *YADAH BURGUER* 🍔\n";
        $message .= "================================\n\n";
        $message .= "📋 *PEDIDO #{$order->order_number}*\n";
        $message .= "📅 " . $order->created_at->format('d/m/Y') . " às " . $order->created_at->format('H:i') . "\n";
        $message .= "--------------------------------\n\n";

        // Informações do cliente
        $message .= "👤 *DADOS DO CLIENTE*\n";
        $message .= "Nome: {$order->customer_name}\n";
        $message .= "Telefone: " . $this->formatPhoneForDisplay($order->customer_phone) . "\n\n";

        // Tipo de entrega
        $message .= "--------------------------------\n";
        if ($order->pickup_in_store) {
            $message .= "🏪 *RETIRADA NA LOJA*\n";
        } else {
            $message .= "🚚 *ENTREGA NO ENDEREÇO*\n";
            $message .= "📍 {$order->address_1}\n";
            if ($order->address_2) {
                $message .= "   {$order->address_2}\n";
            }
        }
        $message .= "--------------------------------\n\n";

        // Itens do pedido
        $message .= "🛒 *ITENS DO PEDIDO:*\n\n";
        foreach ($order->orderItems as $index => $item) {
            $message .= ($index + 1) . ". *{$item->product->name}*\n";
            $message .= "   Qtd: {$item->quantity}x | Preço: " .
                        number_format($item->subtotal, 2, ',', '.') . " Kz\n\n";
        }

        // Valores
        $message .= "--------------------------------\n";
        $message .= "💰 *RESUMO DE VALORES:*\n\n";
        $message .= "Subtotal: *" . number_format($order->subtotal, 2, ',', '.') . " Kz*\n";

        if ($order->discount_amount > 0) {
            $message .= "Desconto: *-" . number_format($order->discount_amount, 2, ',', '.') . " Kz*\n";
        }

        $message .= "\n🔥 *TOTAL A PAGAR: " . number_format($order->total_amount, 2, ',', '.') . " Kz* 🔥\n";
        $message .= "--------------------------------\n\n";

        // Forma de pagamento
        $paymentMethods = [
            'cash' => '💵 Dinheiro',
            'transfer' => '🏦 Transferência Bancária',
            'tpa' => '💳 Cartão (TPA)'
        ];
        $message .= "💳 *FORMA DE PAGAMENTO:*\n";
        $message .= "   " . ($paymentMethods[$order->payment_method] ?? $order->payment_method) . "\n";

        // Observações
        if ($order->notes) {
            $message .= "\n--------------------------------\n";
            $message .= "📝 *OBSERVAÇÕES:*\n";
            $message .= "{$order->notes}\n";
        }

        // Rodapé
        $message .= "\n================================\n";
        $message .= "✅ *Aguardamos sua confirmação!*\n";
        $message .= "📞 Qualquer dúvida, estamos à disposição.\n";
        $message .= "\n🍔 *YADAH BURGUER* - O sabor que você ama! 🍔\n";
        $message .= "================================";

        return $message;
    }

    /**
     * Formata o número de telefone para o formato internacional (sem símbolos)
     *
     * @param string $phone
     * @return string
     */
    private function formatPhoneNumber(string $phone): string
    {
        // Remove tudo que não for número
        $phone = preg_replace('/\D/', '', $phone);

        // Se não começar com o código do país, adiciona 244 (Angola)
        if (strlen($phone) === 9 && !str_starts_with($phone, '244')) {
            $phone = '244' . $phone;
        }

        return $phone;
    }

    /**
     * Formata o número de telefone para exibição (Angola)
     *
     * @param string $phone
     * @return string
     */
    private function formatPhoneForDisplay(string $phone): string
    {
        $phone = preg_replace('/\D/', '', $phone);

        // Remove código do país se presente
        if (str_starts_with($phone, '244')) {
            $phone = substr($phone, 3);
        }

        // Formato Angola: XXX XXX XXX (9 dígitos)
        if (strlen($phone) === 9) {
            return substr($phone, 0, 3) . ' ' .
                   substr($phone, 3, 3) . ' ' .
                   substr($phone, 6, 3);
        }

        return $phone;
    }

    /**
     * Envia o pedido via WhatsApp (abre o navegador/app)
     * Nota: Esta função redireciona o usuário para o WhatsApp
     *
     * @param Order $order
     * @return string URL do WhatsApp
     */
    public function sendOrder(Order $order): string
    {
        return $this->generateOrderLink($order);
    }
}
