<?php


    function tipoUsuario($tu){
        switch($tu){
            case 0:;
            case 1:;
            case 2:;
            case 3:;
            case 4:return "Académico";break;
            case 5:return "Tesista";break;
            case 9:return "Nuevo";break;

        }
    }

    //los programas del plantel
    function programas($uid){
        if(Auth::user()->priv == 1){//si tiene privilegios 1 (el mas alto)
            return tesis\Programa::all();
        }else{//con privilegios menores o roles entre 1 y 4
            return tesis\Rol::select('programa.programa','programa.id')
                        ->join('programa','rol.idprograma','=','programa.id')
                        ->where([['rol.idusuario',$uid],['rol.rol','<=',4]])
                        ->get();
        }
    }


/*    function tipoUsuario($tu){
        switch($tu){
            case 0:;
            case 1:;
            case 2:;
            case 3:;
            case 4:;
            case 5:;
            case 6:;
            case 7:;
            case 8:return "Académico";break;
            case 5:return "Tesista";break;
            default: return "No definido";break;

        }
    }
*/
