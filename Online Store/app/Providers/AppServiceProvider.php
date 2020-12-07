<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        Validator::extend('checkPriceAfterDiscount', function($attribute, $value, $parameters, $validator) {
            $data = $validator->getData();
            $price_after_discount = 0;
            if(!empty($data['discount']) && !empty($data['discount_type'])){
                switch ($data['discount_type']) {
                    case 'PRICE':
                        $price_after_discount = ($data['price'] - $data['discount']);
                        break;
                    case 'PERCENT':
                        $price_after_discount = $data['price'] - (($data['price'] * $data['discount']) / 100);
                        break;
                    default:
                        $price_after_discount = null;
                        break;
                }
            }
            if(!is_null($price_after_discount)){
                if($price_after_discount >= $parameters[0]){
                    if( $data['price'] >= $price_after_discount){
                        return true;
                    }
                }
            }else{
                return true;
            }
            return false;
        });   

        Validator::replacer('checkPriceAfterDiscount', function($message, $attribute, $rule, $parameters) {
            return str_replace(':field', $parameters ? $parameters[0] : "", 'Price After Discount is Greater Then Price or Lower Then 0');
        });
    }

    public function render($request, Exception $exception)
    {
        // This will replace our 404 response with
        // a JSON response.
        if ($exception instanceof ModelNotFoundException && $request->wantsJson())
        {
            return response()->json([
                'data' => 'Resource not found'
            ], 404);
        }

        return parent::render($request, $exception);
    }
}
