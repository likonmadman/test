<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class OrganizationActivity extends Pivot
{
    protected $table = 'organization_activity';

    protected $fillable = [
        'organization_id',
        'activity_id',
    ];
}