<?php

namespace App\Http\Controllers;

use App\Product;
use App\Transformers\ProductTransformer;
use Exception;
use Illuminate\Http\Request;
use League\Fractal;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

class ProductsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $fractal;

    public function __construct()
    {
        $this->fractal = new Manager;
    }

    public function index(){
        $paginator = Product::paginate();
        $products = $paginator->getCollection();
        $resource = new Collection($products,new ProductTransformer);
        $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));
        return $this->fractal->createData($resource)->toArray();
    }

    public function show($id){
        $product = Product::find($id);
        $resource = new Item($product, new ProductTransformer);
        return $this->fractal->createData($resource)->toArray();
    }

    public function store(Request $request){
        $this->validate($request,[
            'product_name'          => 'bail|required|max:255',
            'product_description'   => 'bail|required'
        ]);

        $product = Product::create($request->all());
        $resource = new Item($product,new ProductTransformer);
        return $this->fractal->createData($resource)->toArray();
    }

    public function update($id,Request $request){
        $this->validate($request,[
            'product_name'  => 'max:255',
        ]);
        
        $product = Product::find($id);
        if(!$product) return $this->errorResponse('Product not found!');
        
        try {
            $product->update($request->all());

            $resource = new Item($product, new ProductTransformer);
            return $this->fractal->createData($resource)->toArray();

        } catch (Exception $e) {
            return $this->errorResponse('Failed to update product!',400);
        }
    }

    public function destroy($id){
        $product = Product::find($id);
        try {
            $product->delete();
            return $this->baseResponse('Product deleted successfully!',410);
        } catch (Exception $e) {
            return response()->json('Failed to delete product!',400);
        }
    }

    public function baseResponse($message='success',$status=200){
        return response(['status'=>$status,'message'=>$message],$status);
    }
}
