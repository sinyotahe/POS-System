<?php

namespace App\Http\Middleware;

use App\Models\Branch;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    public function share(Request $request): array
    {
        $user = $request->user();

        $branches = collect();
        $activeBranch = null;

        if ($user) {
            if (in_array($user->role, ['admin', 'owner'])) {
                $branches = Branch::where('status', true)->orderBy('name')->get(['id', 'name', 'code']);
                $activeBranchId = Session::get('active_branch_id', $user->branch_id);
                $activeBranch = $branches->firstWhere('id', $activeBranchId);
            } else {
                $activeBranch = Branch::find($user->branch_id);
                if ($activeBranch) {
                    $branches = collect([$activeBranch]);
                }
            }
        }

        return [
            ...parent::share($request),
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
            ],
            'auth' => [
                'user' => $user ? [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                ] : null,
            ],
            'branches' => $branches,
            'active_branch' => $activeBranch ? [
                'id' => $activeBranch->id,
                'name' => $activeBranch->name,
                'code' => $activeBranch->code,
            ] : null,
            'app' => [
                'tax_rate' => (float) (Setting::get('tax_rate') ?? config('app.tax_rate')),
                'storage_url' => url('/storage'),
            ],
        ];
    }
}
