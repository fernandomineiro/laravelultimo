<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';
    /**
* The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    'name', 'email', 'password',
];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    'password', 'remember_token',
];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
    'email_verified_at' => 'datetime',
];

    public function hasAcesso($tela)
    {
//return true;
            $perfil = User::select('acesso.id',
            'acesso.acesso',
            'acesso.rota',
            'perfil_acesso.visualizacao',
            'perfil_acesso.cadastro',
            'perfil_acesso.edicao',
            'perfil_acesso.exclusao',
            'perfil_acesso.ativo',
            'perfil_acesso.idperfil')
            ->join('perfil_acesso', 'users.idperfil', 'perfil_acesso.idperfil')
            ->join('acesso', 'acesso.id', 'perfil_acesso.idacesso')
            ->where('users.id', '=', Auth::user()->id)
            ->where('users.ativo', 'A')
            ->where('acesso.acesso', $tela)
            ->where('perfil_acesso.ativo', 'A')
            ->get();
                    
        foreach ($perfil as $p) {
            if(Auth::check() && $p->acesso == $tela){
                return true;
            } else {
                return false;
            }
        }
    }
}