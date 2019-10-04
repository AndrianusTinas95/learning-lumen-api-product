<?php

use App\Product;

class ProductTest extends TestCase
{
    public function testShouldReturnAllProducts(){
        $this->get('products',[]);
        $this->seeStatusCode(200);
        $this->seeJsonStructure([
            'data' => [ 
                '*' =>[
                    'product_name',
                    'product_description',
                    'created_at',
                    'updated_at',
                    'link'                    
                ]
            ],
            'meta' => [
                '*'=>[
                    'total',
                    'count',
                    'per_page',
                    'current_page',
                    'total_pages',
                    'links'
                ]
            ]
        ]);
    }

    public function testShouldReturnProduct(){
        $product = Product::get()->random();

        $this->get('products/'.$product->id,[]);
        $this->seeStatusCode(200);
        $this->seeJsonStructure([
            'data'  => [
                'product_name',
                'product_description',
                'created_at',
                'updated_at',
                'link'     
            ]
        ]);
    }

    public function testShouldCreateProduct(){
        $parameters = factory(Product::class)->make();
        $this->post('products',$parameters->toArray(),[]);
        $this->seeStatusCode(200);
        $this->seeJsonStructure(
            [ 
                'data' => [
                    'product_name',
                    'product_description',
                    'created_at',
                    'updated_at',
                    'link'     
                ]
            ]
        );
    }

    public function testShouldUpdateProduct(){
        $parameters = factory(Product::class)->make();
        $product = Product::get()->random();

        $this->put('products/'.$product->id,$parameters->toArray(),[]);
        $this->seeStatusCode(200);
        $this->seeJsonStructure(
            [
                'data' => [
                    'product_name',
                    'product_description',
                    'created_at',
                    'updated_at',
                    'link'    
                ]
            ]
        );
    }

    public function testShouldDeleteProduct(){
        $product = Product::get()->random();
        $this->delete('products/'.$product->id,[],[]);
        $this->seeStatusCode(410);
        $this->seeJsonStructure([
            'status',
            'message'
        ]);
    }
}