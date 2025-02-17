<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use View;
use App\Imovel;

class ImovelController extends Controller
{
    public function listar(Request $request){
        if(!isset($request->paginacao)){
            $paginacao=10;
        }
        else{
            $paginacao = $request->paginacao;
        }

        if(isset($request->ordenacao) && ($request->ordenacao == "bairro" || $request->ordenacao == "municipio")){
            $ordenacao = $request->ordenacao;
        }
        else{
            $ordenacao = "id";
        }

        if(isset($request->municipio) && isset($request->bairro)){
            $imovel = Imovel::where('municipio','LIKE','%'.$request->municipio.'%')->where('bairro','LIKE','%'.$request->bairro.'%')->orderBy($ordenacao)->paginate($paginacao);
        }
        elseif(isset($request->municipio) && !isset($request->bairro)){
            $imovel = Imovel::where('municipio','LIKE','%'.$request->municipio.'%')->orderBy($ordenacao)->paginate($paginacao);
        }
        elseif(isset($request->bairro) && !isset($request->municipio)){
            $imovel = Imovel::where('bairro','LIKE','%'.$request->bairro.'%')->orderBy($ordenacao)->paginate($paginacao);
        }
        else{
            $imovel = Imovel::orderBy($ordenacao)->paginate($paginacao);
        }
        return json_encode($imovel);
    }
    
	public function cadastrar(Request $request){
		try{
			$imovel = new Imovel();
			$imovel->endereco 		= $request->endereco;
			$imovel->bairro 		= $request->bairro;
			$imovel->municipio 		= $request->municipio;
			$imovel->estado 		= $request->estado;
			$imovel->cep 			= $request->cep;
			$imovel->tipo_imovel 	= $request->tipo_imovel;
			$imovel->proprietario 	= $request->proprietario;
			$imovel->save();
            return json_encode($imovel);
		} catch(\Exception $excecao){
			return json_encode($excecao);
		}
	}

    public function editar(Request $request){
        try{
            $imovel = Imovel::find($request->id);
            if(isset($request->endereco)){
                $imovel->endereco = $request->endereco;
            }
            if(isset($request->bairro)){
                $imovel->bairro = $request->bairro;
            }
            if(isset($request->municipio)){
                $imovel->municipio = $request->municipio;
            }
            if(isset($request->estado)){
                $imovel->estado = $request->estado;
            }
            if(isset($request->cep)){
                $imovel->cep = $request->cep;
            }
            if(isset($request->tipo_imovel)){
                $imovel->tipo_imovel = $request->tipo_imovel;
            }
            if(isset($request->proprietario)){
                $imovel->proprietario = $request->proprietario;
            }
            $imovel->save();
            return json_encode($imovel);
        } catch(\Exception $excecao){
            return json_encode($excecao);
        }
    }
    
    public function remover(Request $request){
        try{
            $remocao_imovel = Imovel::where('id','=',$request->id)->delete();
            return json_encode($remocao_imovel);
        } catch(\Exception $excecao){
            return $excecao;
        }
    }
}