<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Owner\DashboardController;
use App\Http\Controllers\Owner\KostController;
use Illuminate\Http\Request;

/**
 * Legacy OwnerController kept for backwards compatibility.
 * All core operations are routed to the modular Owner Controllers.
 */
class OwnerController extends Controller
{
    public function dashboard()
    {
        return app(DashboardController::class)->index();
    }

    public function index(Request $request)
    {
        return app(KostController::class)->index($request);
    }

    public function create()
    {
        return app(KostController::class)->create();
    }

    public function store(Request $request)
    {
        return app(KostController::class)->store($request);
    }

    public function show($id)
    {
        return app(KostController::class)->show($id);
    }

    public function edit($id)
    {
        return app(KostController::class)->edit($id);
    }

    public function update(Request $request, $id)
    {
        return app(KostController::class)->update($request, $id);
    }

    public function destroy($id)
    {
        return app(KostController::class)->destroy($id);
    }
}
