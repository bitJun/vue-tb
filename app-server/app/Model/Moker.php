<?php

namespace App\Model;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * @SWG\Definition(definition="Moker", type="object")
 */
class Moker extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $table = 'moker';
    protected $guarded = ['id'];
    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * @SWG\Property(property="id", default=1)
     * @var int
     */
    /**
     * @SWG\Property(property="name", default="道儿瑟老")
     * @var string
     */
    /**
     * @SWG\Property(property="avatar", default="daoerselao.png")
     * @var string
     */
    /**
     * @SWG\Property(property="mobile", default="13968154713")
     * @var string
     */
    /**
     * @SWG\Property(property="gender", default=1)
     * @var integer
     */
    /**
     * @SWG\Property(property="birthday", default="1990-11-11")
     * @var string
     */
    /**
     * @SWG\Property(property="province_id", default=110000)
     * @var integer
     */
    /**
     * @SWG\Property(property="city_id", default=110100)
     * @var integer
     */
    /**
     * @SWG\Property(property="district_id", default=110101)
     * @var integer
     */
}
