<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Revision
 *
 * @author Jeevan
 */
class Revision extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
