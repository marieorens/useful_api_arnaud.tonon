<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Module;

class ModulesController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $modules = Module::select('id','name','description')->get();

        $userModules = $user
            ? $user->modules()->pluck('active', 'module_id')->toArray()
            : [];

        $modules = $modules->map(function ($module) use ($userModules) {
            $module->active = isset($userModules[$module->id]) ? (bool)$userModules[$module->id] : false;
            return $module;
        });

        return response()->json($modules);
    }

    public function activate(Request $request, $id)
    {
        $module = Module::find($id);
        if (! $module) {
            return response()->json(['error' => 'Module not found'], 404);
        }

        $user = $request->user();
        $user->modules()->syncWithoutDetaching([$module->id => ['active' => true]]);

        return response()->json(['message' => 'Module activated'], 200);
    }

    public function deactivate(Request $request, $id)
    {
        $module = Module::find($id);
        if (! $module) {
            return response()->json(['error' => 'Module not found'], 404);
        }

        $user = $request->user();
        // si le pivot existe, faire un update; sinon, active=false
        $exists = $user->modules()->wherePivot('module_id', $module->id)->exists();
        if ($exists) {
            $user->modules()->updateExistingPivot($module->id, ['active' => false]);
        } else {
            $user->modules()->attach($module->id, ['active' => false]);
        }

        return response()->json(['message' => 'Module deactivated'], 200);
    }
}
