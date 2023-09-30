<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class GetProductsController extends Controller
{
    private $products;

    public function __construct(Product $products)
    {
        $this->products = $products;
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke()
    {
        $products = Cache::get('products');

        if ($products === null) {
            // Cache expirado ou não existe, então você pode executar alguma ação antes de buscar os produtos do banco de dados
            Log::info('Cache expirado ou não existe');
            // Execute qualquer ação adicional, se necessário

            // Agora, busque os produtos do banco de dados
            $products = $this->products->all();

            // Armazene os produtos no cache com um tempo de vida de 30 minutos
            Cache::put('products', $products, 20);
            Log::info('Cache atualizado');
        } else {
            // Os produtos foram encontrados no cache
            Log::info('Produtos encontrados no cache');
        }

        return $products;
    }
}
