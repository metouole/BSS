<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Product;
use App\Category;


class ProductController extends Controller
{

     /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(\Gate::allows('isAdmin') || \Gate::allows('isAuthor')) {

            return Product::latest()->paginate(3);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'category_id' => 'required',
            'name' => 'required|string',
            'type' => 'required|string',
            'designation' => 'required|string',
            'position' => 'required|string',
            'couleur' => 'required|string',
            'longueur' => 'required|string',
            'largeur' => 'required|string',
            'hauteur' => 'required|string',
            'epaisseur' => 'required|string',
            'poids' => 'required|string',
            'prix_uni' => 'required|string',
            'code_produit' => 'required|string',
            'code_douanier' => 'required|string',
            'port_sortie' => 'required|string',
            'fournisseur' => 'required|string',
            'comment' => 'required|string',
        ]);

        if($request->image){
            $name = time().'.' . explode('/', explode(':', substr($request->image, 0, strpos($request->image, ';')))[1])[1];

            \Image::make($request->image)->save(public_path('img/products/').$name);
            $request->merge(['image' => $name]);
        }

        return Product::create([
            'category_id' => $request['category_id'],
            'name' => $request['name'],
            'type' => $request['type'],
            'designation' => $request['designation'],
            'position' => $request['position'],
            'couleur' => $request['couleur'],
            'longueur' => $request['longueur'],
            'largeur' => $request['largeur'],
            'hauteur' => $request['hauteur'],
            'epaisseur' => $request['epaisseur'],
            'poids' => $request['poids'],
            'prix_uni' => $request['prix_uni'],
            'code_produit' => $request['code_produit'],
            'code_douanier' => $request['code_douanier'],
            'port_sortie' => $request['port_sortie'],
            'fournisseur' => $request['fournisseur'],
            'image' => $request['image'],
            'comment' => $request['comment'],

        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $this->validate($request, [
            'category_id' => 'required',
            'name' => 'required|string',
            'type' => 'required|string',
            'designation' => 'required|string',
            'position' => 'required|string',
            'couleur' => 'required|string',
            'longueur' => 'required|string',
            'largeur' => 'required|string',
            'hauteur' => 'required|string',
            'epaisseur' => 'required|string',
            'poids' => 'required|string',
            'prix_uni' => 'required|string',
            'code_produit' => 'required|string',
            'code_douanier' => 'required|string',
            'port_sortie' => 'required|string',
            'fournisseur' => 'required|string',
            'image' => 'required|string',
            'comment' => 'required|string',
        ]);

        $currentImage = $product->image;

        if($request->image != $currentImage){
            $name = time().'.' . explode('/', explode(':', substr($request->image, 0, strpos($request->image, ';')))[1])[1];

            \Image::make($request->image)->save(public_path('img/products/').$name);
            $request->merge(['image' => $name]);

            $product_image = public_path('img/products/').$currentImage;

             if(file_exists($product_image)){
                @unlink($product_image);
             }


        }

        //return $name;

        $product->update($request->all());
        return ['message' => 'Update Product Controller'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('isAdmin');
        $product = Product::findOrFail($id);

        $product->delete();
        return ['message' => 'The product was deleted'];
    }

    public function search()
    {
        if ($search = \Request::get('q')) {
            list($search1, $search2) = explode(' ', $search);
            $products = Product::where(function($query) use ($search){
                $query->where('name','LIKE',"%$search%")
                        ->orWhere('type','LIKE',"%$search%")
                        ->orWhere('designation','LIKE',"%$search%")
                        ->orWhere('position','LIKE',"%$search%")
                        ->orWhere('couleur','LIKE',"%$search%")
                        ->orWhere('longueur','LIKE',"%$search%")
                        ->orWhere('largeur','LIKE',"%$search%")
                        ->orWhere('hauteur','LIKE',"%$search%")
                        ->orWhere('epaisseur','LIKE',"%$search%")
                        ->orWhere('fournisseur','LIKE',"%$search%")
                        ->orWhere('poids','LIKE',"%$search%")
                        ->orWhere('prix_uni','LIKE',"%$search%")
                        ->orWhere('code_produit','LIKE',"%$search%");
            })->paginate(20);
        }
        return $products;
    }


}
