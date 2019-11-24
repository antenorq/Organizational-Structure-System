<?php

namespace App\Services;

use App\Gestor;
use App\Equipe;
use DB;
use Log;

class EquipeService
{
    public function salvar($gestorData, $equipeData)
    {
        DB::beginTransaction();
        try{
            $gestor = new Gestor;
            //$gestor = Gestor::findOrFail($gestor['id']);
            $gestor->id_orgao = $gestorData['id_orgao'];
            $gestor->nome = $gestorData['nome'];
            $gestor->id_cargo = $gestorData['id_cargo'];
            $gestor->curriculo = $gestorData['curriculo'];
            if(isset($gestorData['foto']))
                $gestor->foto = $this->upload($gestorData['foto']);
            $gestor->save();

            $equipes = [];
            foreach($equipeData['equipe']['nomes'] as $key => $value){
                $nome = $equipeData['equipe']['nomes'][$key];
                $unidade = $equipeData['equipe']['unidades'][$key];
                $cargo = $equipeData['equipe']['cargos'][$key];
                if( $nome != null && $unidade != null && $cargo != null ){
                    $equipes[] = Equipe::updateOrCreate(
                        ['id_cargo' => $cargo, 'id_unidade' => $unidade, 'nome' => $nome],
                        ['id_cargo' => $cargo, 'id_unidade' => $unidade, 'nome' => $nome]
                    );
                }
            }

            DB::commit();
            return back()->with('success', 'Equipe cadastrada com sucesso!');
        }catch(\Exception $e){
            Log::error($e->getMessage());
            DB::rollBack();
            return back()->with('error', 'Houve um erro ao salvar, por favor tente novamente!');
        }
    }

    public function upload($file)
    {
        $nameFile = date('Y-m-d-H-i-s_') . $file->getClientOriginalName();

        $file->move(public_path() . "/fotos_gestor/", $nameFile);
        $caminho = "/fotos_gestor/". $nameFile;

        return $caminho;
    }

}
