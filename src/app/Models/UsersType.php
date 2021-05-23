<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class UsersType extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, HasFactory;

    protected $table        = 'usersType';
    protected $primaryKey   = 'id';

    public const TYPES = [
        1 => 'manager',
        2 => 'technician'
    ];

    /**
     * Returns the Id of the user's role
     */
    public static function getTypeId($type) {
        return array_search($type, self::TYPES);
    }

    /**
     * Returns description of the user's role
     */
    public function getTypeAttribute() {
        return self::TYPES[$this->attributes['usersTypeId']];
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'type', 'created_at', 'updated_at'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        
    ];
}
