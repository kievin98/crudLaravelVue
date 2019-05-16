<?php

namespace App\Http\Controllers;

use App\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $customer = Customer::all();
      return response()->json([
          'customers'    => $customer,
      ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
          'nama'   => 'required|max:255',
          'alamat' => 'required',
      ]);

      $customer = Customer::create([
          'nama'   => request('nama'),
          'alamat' => request('alamat'),
      ]);

      return response()->json([
          'customer' => $customer,
          'message'  => 'Success'
      ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
      $this->validate($request, [
          'nama'   => 'required|max:255',
          'alamat' => 'required',
      ]);

      $customer->nama = request('nama');
      $customer->alamat = request('alamat');
      $customer->save();

      return response()->json([
          'message' => 'Task updated successfully!'
      ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
      $customer->delete();
      return response()->json([
          'message' => 'Task deleted successfully!'
      ], 200);
    }
}
