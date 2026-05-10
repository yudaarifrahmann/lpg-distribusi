<?php

namespace App\Traits;

use App\Models\Scopes\BranchScope;
use Illuminate\Database\Eloquent\Model;

trait BelongsToBranch
{
    protected static function bootBelongsToBranch()
    {
        static::addGlobalScope(new BranchScope);

        static::creating(function (Model $model) {
            if (auth()->check() && !auth()->user()->hasRole('superadmin') && auth()->user()->branch_id) {
                if (empty($model->branch_id)) {
                    $model->branch_id = auth()->user()->branch_id;
                }
            }
        });
    }

    public function branch()
    {
        return $this->belongsTo(\App\Models\Branch::class);
    }
}
