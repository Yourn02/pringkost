<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminKostController;
use App\Http\Controllers\Admin\AdminFacilityController;
use Illuminate\Http\Request;

/**
 * Legacy AdminController kept for backward compatibility with existing route references.
 * Delegating actions to dedicated Admin controllers.
 */
class AdminController extends Controller
{
    public function index()
    {
        return app(AdminDashboardController::class)->index();
    }

    public function approve($id)
    {
        return app(AdminKostController::class)->approve($id);
    }

    public function reject(Request $request, $id)
    {
        return app(AdminKostController::class)->reject($request, $id);
    }

    public function storeFacility(Request $request)
    {
        return app(AdminFacilityController::class)->store($request);
    }

    public function destroyFacility($id)
    {
        return app(AdminFacilityController::class)->destroy($id);
    }
}
