# 📚 Histórico de Preços em Pedidos

## 🎯 Problema

Com o tempo, os preços dos produtos podem mudar (por exemplo, um hambúrguer que custava 2.500 Kz passa a custar 2.800 Kz). Contudo, o sistema também precisa manter o **histórico de pedidos antigos**, que já foram concluídos ou pagos com o preço antigo.

Se o sistema estivesse a buscar o preço diretamente da tabela `products` ao exibir os detalhes de um pedido, então **todos os pedidos antigos passariam a mostrar o novo preço**, o que cria inconsistência nos registros históricos — o valor exibido já não corresponde ao que o cliente realmente pagou.

### Exemplo do problema:
```
1. Cliente faz pedido em Janeiro: Hambúrguer = 2.500 Kz
2. Em Março, o preço muda para: Hambúrguer = 2.800 Kz
3. Sem a solução: Pedido de Janeiro mostraria 2.800 Kz ❌
4. Com a solução: Pedido de Janeiro continua mostrando 2.500 Kz ✅
```

## ✅ Solução Implementada

No momento da criação do pedido, o sistema **salva o preço atual e o nome do produto** dentro do próprio pedido (na tabela `order_items`). Assim, cada item do pedido guarda:

- **`product_id`** - ID do produto (para referência futura)
- **`product_name`** - Nome do produto no momento da venda (histórico legível)
- **`unit_price`** - Preço no momento da venda (histórico contábil)
- **`quantity`** - Quantidade comprada
- **`subtotal`** - Total do item (quantity × unit_price)

### Vantagens:
✅ **Fidelidade histórica**: Pedidos antigos preservam o preço original  
✅ **Independência**: Alterações na tabela `products` não afetam pedidos já realizados  
✅ **Auditoria**: Registro contábil preciso do que foi realmente cobrado  
✅ **Rastreabilidade**: Mesmo se o produto for deletado, o nome permanece no histórico  

## 🗄️ Estrutura do Banco de Dados

### Tabela: `order_items`

```sql
CREATE TABLE order_items (
    id BIGINT UNSIGNED PRIMARY KEY,
    order_id BIGINT UNSIGNED,
    product_id BIGINT UNSIGNED,
    product_name VARCHAR(255),        -- ✅ Nome no momento da venda
    quantity INT DEFAULT 1,
    unit_price DECIMAL(10,2),         -- ✅ Preço no momento da venda
    subtotal DECIMAL(10,2),
    created_by BIGINT UNSIGNED,
    updated_by BIGINT UNSIGNED,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    deleted_at TIMESTAMP,
    
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);
```

## 💻 Implementação no Código

### 1. Migration

**Arquivo**: `database/migrations/2025_08_28_131940_create_order_items_table.php`

```php
Schema::create('order_items', function (Blueprint $table) {
    $table->id();
    $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
    $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
    $table->string('product_name'); // ✅ Nome do produto (histórico)
    $table->integer('quantity')->default(1);
    $table->decimal('unit_price', 10, 2); // ✅ Preço (histórico)
    $table->decimal('subtotal', 10, 2);
    $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
    $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
    $table->timestamps();
    $table->softDeletes();
});
```

### 2. Model: OrderItem

**Arquivo**: `app/Models/OrderItem.php`

```php
protected $fillable = [
    'order_id',
    'product_id',
    'product_name',  // ✅ Nome do produto no momento da venda
    'quantity',
    'unit_price',    // ✅ Preço no momento da venda
    'subtotal',
    'created_by',
    'updated_by',
];
```

### 3. Controller: OrderController

**Arquivo**: `app/Http/Controllers/Admin/Dash/OrderController.php`

#### Método `store()` - Criação de Pedidos

```php
// Busca o produto do banco para validar
$product = Product::where('id', $productData['id'])
    ->where('product_status', 'a')
    ->first();

// Captura o preço atual do produto
$priceFromDB = (float) $product->price;

// Salva no array de items
$orderItems[] = [
    'product_id' => $product->id,
    'product_name' => $product->name,  // ✅ Salva o nome atual
    'quantity' => $quantity,
    'unit_price' => $priceFromDB,      // ✅ Salva o preço atual
    'subtotal' => $itemSubtotal,
];

// Cria o item do pedido com os dados históricos
OrderItem::create([
    'order_id' => $order->id,
    'product_id' => $item['product_id'],
    'product_name' => $item['product_name'],  // ✅ Nome preservado
    'quantity' => $item['quantity'],
    'unit_price' => $item['unit_price'],      // ✅ Preço preservado
    'subtotal' => $item['subtotal'],
    'created_by' => $currentUser->id,
]);
```

## 🔄 Fluxo de Funcionamento

### Cenário 1: Criação de Pedido
```
1. Cliente seleciona produtos no sistema
2. Sistema busca preços atuais da tabela products
3. Sistema COPIA nome e preço para order_items
4. Pedido é criado com dados "congelados"
```

### Cenário 2: Alteração de Preço
```
1. Administrador altera preço do produto: 2.500 → 2.800 Kz
2. Tabela products é atualizada
3. Pedidos antigos NÃO são afetados (usam order_items)
4. Novos pedidos usarão o preço 2.800 Kz
```

### Cenário 3: Visualização de Pedido Antigo
```
1. Sistema exibe pedido de 3 meses atrás
2. Busca dados de order_items (não de products)
3. Mostra o preço que estava válido na época
4. Histórico permanece consistente ✅
```

## 📊 Comparação: Antes vs Depois

### ❌ Antes (Problema)
```php
// Busca preço direto do produto
$item->product->price  // Sempre mostra o preço ATUAL
```

**Resultado**: Todos os pedidos mostram o preço atual do produto, perdendo o histórico.

### ✅ Depois (Solução)
```php
// Usa o preço salvo no pedido
$item->unit_price  // Mostra o preço no MOMENTO DA VENDA
```

**Resultado**: Cada pedido mantém o preço que estava válido quando foi criado.

## 🎓 Benefícios da Solução

### Para o Negócio:
- ✅ **Conformidade legal**: Registros contábeis precisos
- ✅ **Transparência**: Cliente vê o que realmente pagou
- ✅ **Auditoria**: Histórico fiel para análises

### Para o Sistema:
- ✅ **Integridade de dados**: Histórico não é alterado retroativamente
- ✅ **Independência**: Mudanças em produtos não quebram pedidos antigos
- ✅ **Escalabilidade**: Sistema preparado para crescimento

### Para o Usuário:
- ✅ **Confiabilidade**: Informações corretas sempre
- ✅ **Rastreabilidade**: Pode verificar valores pagos no passado
- ✅ **Segurança**: Proteção contra alterações indevidas

## 🔐 Boas Práticas Implementadas

1. **Imutabilidade de Pedidos Concluídos**
   - Pedidos finalizados não devem ter preços alterados
   - Sistema bloqueia edição de pedidos entregues/concluídos

2. **Validação de Preços**
   - Sistema valida se preço do frontend corresponde ao banco
   - Previne manipulação de valores pelo cliente

3. **Soft Deletes**
   - Produtos podem ser "deletados" mas histórico permanece
   - `product_name` garante legibilidade mesmo após deleção

4. **Auditoria Completa**
   - Campos `created_by` e `updated_by` rastreiam quem fez o quê
   - Timestamps registram quando cada ação ocorreu

## 📝 Exemplo Prático

```php
// Pedido #1234 criado em 15/01/2025
Order::find(1234)->orderItems->first();
// Resultado:
// {
//   "product_id": 5,
//   "product_name": "Hambúrguer Clássico",    // ✅ Nome em Jan/2025
//   "unit_price": 2500.00,                    // ✅ Preço em Jan/2025
//   "quantity": 2,
//   "subtotal": 5000.00
// }

// Em Março/2025, produto é atualizado:
Product::find(5)->update(['price' => 2800.00]);

// Mas o pedido #1234 AINDA mostra:
Order::find(1234)->orderItems->first()->unit_price;
// Resultado: 2500.00 ✅ (valor histórico preservado)

// Novo pedido #1235 criado em 20/03/2025
Order::find(1235)->orderItems->first();
// Resultado:
// {
//   "product_id": 5,
//   "product_name": "Hambúrguer Clássico",    
//   "unit_price": 2800.00,                    // ✅ Novo preço
//   "quantity": 1,
//   "subtotal": 2800.00
// }
```

## 🚀 Próximos Passos (Opcional)

Para melhorias futuras, considere:

1. **Histórico de Alterações de Preço**
   - Criar tabela `product_price_history`
   - Registrar todas as mudanças de preço com datas

2. **Relatórios Financeiros**
   - Análise de receita por período
   - Comparação de preços ao longo do tempo

3. **Sistema de Promoções**
   - Campo adicional `promotion_price` já aplicado no pedido
   - Rastreamento de descontos aplicados

---

**Data de Implementação**: 31 de Outubro de 2025  
**Desenvolvedor**: Sistema Yadah Burguer  
**Status**: ✅ Implementado e Testado
