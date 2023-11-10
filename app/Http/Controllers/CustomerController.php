<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\BulkStoreInvoiceRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Http\Resources\CustomerCollection;
use App\Filters\CustomerFilter;
use App\Http\Resources\CustomerResource;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Instancia de filtro
        $filter = new CustomerFilter();
        // Transformar query
        $queryItems = $filter->transform($request);
        // Obtiene colección de modelo
        $customers = Customer::where($queryItems);

        // Verifica si consulta incluye pagos
        $includeInvoices = $request->query('includeInvoices');

        // Si incluye invoices, obtiene modelo con relación
        if ($includeInvoices)
            $customers = $customers->with('invoices');

        return new CustomerCollection(
            // Agrega filtros de request
            $customers->paginate()->appends($request->query())
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCustomerRequest $request)
    {
        return new CustomerResource(Customer::create($request->all()));
    }

    public function bulkStore(BulkStoreInvoiceRequest $request)
    {
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        $includeInvoices = request()->query('includeInvoices');

        if ($includeInvoices)
            // Cargar relación 'invoices' una vez que haya cargado el modelo
            return new CustomerResource($customer->loadMissing('invoices'));

        return new CustomerResource($customer);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        $customer->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        //
    }
}
