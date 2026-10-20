<?php

namespace App\Http\Controllers\Audit;

use App\Http\Controllers\Controller;
use OwenIt\Auditing\Models\Audit;

class AuditController extends Controller
{
    public function index()
    {
        $audits = Audit::with(['user', 'auditable'])
            ->latest('created_at')
            ->paginate(15);

        return view('audit.index', compact('audits'));
    }
}