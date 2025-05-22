<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Compania;
use App\Filters\Common\Auth\CompaniasFilter as AppUserFilter;
use App\Filters\Core\CompaniasFilter;
use App\Services\Core\Auth\CompaniasService;

class CompaniaController extends Controller
{

    /**
     * TransactionController constructor.
     *
     * @param TransactionService $Transaction
     * @param TransactionFilter $filter
     */

    public function __construct(CompaniasService $Transaction, CompaniasFilter $filter)
    {
        $this->service = $Transaction;
        $this->filter = $filter;
    }

    public function getCompanias(){
   
        $companias = Compania::select('id', 'nombre')
        ->get()
        ->map(function($item) {
            $item->value = $item->nombre;
            unset($item->nombre);  
            return $item;
        });
        return response()->json($companias);
    }

    public function listado()
    {
        $user = Auth()->user();
        return (new AppUserFilter(
            $this->service
                ->filters($this->filter)
                ->with('cuotas', 'coberturas')
                ->latest()
        ))->filter()
            ->paginate(request()->get('per_page', 10));
    }

    public function create(Request $request)
    {
        // Crear la compañía
        $compania = Compania::create([
            'nombre' => $request->data['nombre'],
            'logo' => $request->data['logo'], // Puedes manejar aquí la subida de imagen si lo deseas
        ]);

        // Crear las cuotas
        if (!empty($request->data['cuotas'])) {
            foreach ($request->data['cuotas'] as $cuota) {
                $compania->cuotas()->create([
                    'valor' => $cuota['valor'],
                    'recargo' => $cuota['recargo'],
                ]);
            }
        }

        // Crear las coberturas
        if (!empty($request->data['coberturas'])) {
            foreach ($request->data['coberturas'] as $cobertura) {
                $compania->coberturas()->create([
                    'tipo' => $cobertura['tipo'],
                    'monto' => $cobertura['monto'],
                ]);
            }
        }

        return created_responses('Compania');
    }

    public function editCompania(Request $request, $id)
    {

        $compania = Compania::findOrFail($id);

        // Actualizar datos principales
        $compania->update([
            'nombre' => $request->data['nombre'],
            'logo' => $request->data['logo'], // si es archivo se maneja distinto
        ]);

        // Eliminar cuotas anteriores
        $compania->cuotas()->delete();

        // Insertar nuevas cuotas
        if (!empty($request->data['cuotas'])) {
            foreach ($request->data['cuotas'] as $cuotaData) {
                $compania->cuotas()->create([
                    'valor' => $cuotaData['valor'],
                    'recargo' => $cuotaData['recargo'] ?? null,
                ]);
            }
        }

        // Eliminar coberturas anteriores
        $compania->coberturas()->delete();

        // Insertar nuevas coberturas
        if (!empty($request->data['coberturas'])) {
            foreach ($request->data['coberturas'] as $coberturaData) {
                $compania->coberturas()->create([
                    'tipo' => $coberturaData['tipo'],
                    'monto' => $coberturaData['monto'],
                ]);
            }
        }

        return created_responses('Transaction');
    }

    public function show(Compania $Compania)
    {
        return response()->json($Compania);
    }

    public function update(Request $request, Compania $Compania)
    {
        $Compania->update($request->all());
        return response()->json($Compania);
    }

    public function destroy(Compania $Compania)
    {
        $Compania->delete();
        return response()->json(null, 204);
    }
}
