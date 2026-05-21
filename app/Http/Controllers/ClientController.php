<?php
namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::all();
        return view('clients.index', compact('clients'));
    }

    public function create()
    {
        return view('clients.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        Client::create($request->all());
        return redirect()->route('clients.index')
            ->with('success', 'Cliente creado correctamente');
    }

    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $client->update($request->all());
        return redirect()->route('clients.index')
            ->with('success', 'Cliente actualizado correctamente');
    }

    public function destroy(Client $client)
    {
        $client->delete();
        return redirect()->route('clients.index')
            ->with('success', 'Cliente eliminado correctamente');
    }

    public function show(Client $client)
    {
        return redirect()->route('clients.index');
    }

    // API para autocompletar
    public function search(Request $request)
    {
        $clients = Client::where('name', 'like', '%' . $request->q . '%')
            ->limit(10)->get(['id', 'name', 'phone']);
        return response()->json($clients);
    }
}