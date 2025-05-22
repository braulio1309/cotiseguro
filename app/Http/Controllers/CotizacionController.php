<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cotizacion;
use App\Models\Compania;
use App\Filters\Common\Auth\CotizacionsFilter as AppUserFilter;
use App\Filters\Core\CotizacionsFilter;
use App\Services\Core\Auth\CotizacionsService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class CotizacionController extends Controller
{

    /**
     * TransactionController constructor.
     *
     * @param TransactionService $Transaction
     * @param TransactionFilter $filter
     */

    public function __construct(CotizacionsService $Transaction, CotizacionsFilter $filter)
    {
        $this->service = $Transaction;
        $this->filter = $filter;
    }

    public function getCotizacions()
    {
        dd('aa');
        $Cotizacions = Cotizacion::select('id', 'nombre')
            ->get()
            ->map(function ($item) {
                $item->value = '000' . $item->id;
                unset($item->nombre);  // Elimina el campo 'nombre' si ya no lo necesitas
                return $item;
            });
        return response()->json($Cotizacions);
    }

    public function listado()
    {
        $user = Auth()->user();
        return (new AppUserFilter(
            $this->service
                ->filters($this->filter)
                ->latest()
        ))->filter()
            ->paginate(request()->get('per_page', 10));
    }

    public function create(Request $request)
    {
        $data = $request->input('data');

        $cotizacion = Cotizacion::create([
            'user_id' => Auth()->user()->id,
            'agente' => $data['agente'],
            'titular' => $data['titular'],
            'email' => $data['email'],
            'telefono' => $data['telefono'],
            'edades' => $data['edades'],
            'funerarios' => $data['funerarios'] ?? false,
            'maternidad' => $data['maternidad'] ?? false,
            'vida' => $data['vida'] ?? false,
            'viajes' => $data['viajes'] ?? false,
            'descuento' => 0,
        ]);

        // 2. Asociar compañías
        $cotizacion->companias()->attach($data['companias']);

        // 3. Obtener las compañías completas
        $companias = Compania::with(['coberturas', 'cuotas'])
            ->whereIn('id', $data['companias'])
            ->whereHas('coberturas', function ($query) use ($data) {
                $query->whereIn('monto', $data['sumas']);
            })
            ->get();


        // 4. Generar el PDF
        $pdf = Pdf::loadView('pdf.coti', [
            'cotizacion' => $cotizacion,
            'companias' => $companias,
            'sumas' => $data['sumas'],
        ]);

        $filename = 'resumen_cotizacion_' . $cotizacion->id . '.pdf';
        $path = "public/{$filename}";
        Storage::put($path, $pdf->output());

        // 5. Guardar ruta en la cotización
        $cotizacion->update([
            'pdf_path' => $filename
        ]);

        // 6. Responder con la URL del PDF
        return response()->json([
            'message' => 'Cotización creada exitosamente.',
            'pdf_url' => asset("storage/{$filename}"),
            'cotizacion' => $cotizacion
        ]);
        return created_responses('Cotizacion');
    }

    public function editCotizacion(Request $request, $id)
    {

        $Cotizacion = Cotizacion::where('id', $id)->first();
        $Cotizacion->update($request->all());

        return created_responses('Transaction');
    }

    public function show(Cotizacion $Cotizacion)
    {
        return response()->json($Cotizacion);
    }

    public function update(Request $request, Cotizacion $Cotizacion)
    {
        $Cotizacion->update($request->all());
        return response()->json($Cotizacion);
    }

    public function destroy(Cotizacion $Cotizacion)
    {
        $Cotizacion->delete();
        return response()->json(null, 204);
    }
}
