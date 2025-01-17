<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    //
    public function index()
    {
        // get all products
        $product = Product::all();
        return response()->json([
            'message' => 'vos produit',
            'data' => $product
        ]);
    }

    public function store(Request $request)
    {
        try {
            // validate request
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:4',
                'price' => 'required',
                'quantity' => 'required',
                'description' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(
                    [
                        'message' => 'Erreur de validation',
                        'error' => $validator->errors()
                    ],
                    400
                );
            }
            // save product in db
            $product = Product::create($request->all());
            return response()->json([
                'message' => 'Produit ajouté',
                'data' => $product
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de l\'ajout du produit',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id) {
        try {
            // validate request
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'price' => 'required',
                'quantity' => 'required',
                'description' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(
                    [
                        'message' => 'Erreur de validation',
                        'error' => $validator->errors()
                    ],
                    400
                );
            }
            // save product in db
            $product = Product::find($id);
            if (!$product) {
                return response()->json([
                    'message' => 'Produit non trouvé',
                ], 404);
            }
            $product->update($request->all());
            return response()->json([
                'message' => 'Produit mis à jour',
                'data' => $product
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la mise ajout du produit',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            // get product by id
            $product = Product::find($id);
            if (!$product) {
                return response()->json([
                    'message' => 'Produit non trouvé',
                ], 404);
            }
            return response()->json([
                'message' => 'Produit trouvé',
                'data' => $product
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la récupération du produit',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                'message' => 'Produit non trouvé',
            ]);
        }
        $product->delete();
        return response()->json([
            'message' => 'Product deleted successfully!',
        ]);
    }
}
