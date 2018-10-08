<?php

namespace App\Http\Controllers\cliente;

use App\Cliente;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Excel;
use PhpOffice\PhpSpreadsheet\Calculation\Exception;

class ClienteController extends Controller
{
    public function listar(){
        $clientes = Cliente::paginate(10);
        return view("cliente.clientes",["clientes" => $clientes]);
    }
    private function parserEndereco(Cliente $cliente, $endereco, $cep)
    {
        //parser no endereço
        $enderecoExplodeVirgula = explode(",", $endereco);
        $tamanho = sizeof($enderecoExplodeVirgula);


        //buscar longitude e latitue
        $retorno = new \stdClass();
        try {
            //buscar geoLocalização
            $key = "AIzaSyDPkdTGo8aIQ0H3yMZc72s8PkAbUlvQV8U";
            //parser do endereço para utilizar a API

            $client = new \GuzzleHttp\Client(['base_uri' => 'https://maps.googleapis.com/maps/api/geocode/']);
            $resposta = $client->request("GET", "json?address=" . $endereco . "&key=" . $key);
            if ($resposta->getStatusCode() === 200) {

                $dados = json_decode($resposta->getBody());
                if ($dados->status != "OK") {
                    $retorno->status = 0;
                    $retorno->erro = $dados->status;
                    return $retorno;
                }

                $lat = $dados->results[0]->geometry->location->lat;
                $long = $dados->results[0]->geometry->location->lng;

                if ($tamanho === 3) {
                    $enderecoExplodeIfen = explode("-", $enderecoExplodeVirgula[2]);
                    $cliente->endereco()->create([
                        "logradouro" => $enderecoExplodeVirgula[0],
                        "numero" => $enderecoExplodeVirgula[1],
                        "complemento" => $enderecoExplodeIfen[0],
                        "bairro" => $enderecoExplodeIfen[1],
                        "cep" => $cep,
                        "cidade" => $enderecoExplodeIfen[2],
                        "longitude" => $long,
                        "latitude" => $lat,
                    ]);
                    $retorno->status = 1;
                } else {
                    $enderecoExplodeIfen = explode("-", $enderecoExplodeVirgula[1]);
                    $cliente->endereco()->create([
                        "logradouro" => $enderecoExplodeVirgula[0],
                        "numero" => $enderecoExplodeIfen[0],
                        "bairro" => $enderecoExplodeIfen[1],
                        "cep" => $cep,
                        "cidade" => $enderecoExplodeIfen[2],
                        "longitude" => $long,
                        "latitude" => $lat,
                    ]);

                    $retorno->status = 1;

                }

            } else {
                $retorno->status = 0;
                $retorno->erro = $resposta->getStatusCode();
                return $retorno;
            }
        } catch (\Exception $e) {

            $retorno->status = 0;
            $retorno->erro = $e->getMessage();
            return $retorno;
        }

        return $retorno;


    }

    public function cadastrar(Request $request)
    {
        $arquivo = $request->arquivo;

        $path = $arquivo->getRealPath();
        $csv = fopen($path, "r");
        $clientes = array();
        while (($coluna = fgetcsv($csv, 1000, ";")) !== FALSE) {
            if (!empty($coluna[0])) {
                $contador = 0;
                $cliente = null;
                try {
                    $cliente = new Cliente();
                    $cliente->nome = $coluna[0];
                    $cliente->email = $coluna[1];
                    $cliente->data_nascimento = $this->inverteData($coluna[2]);
                    $cliente->cpf = $coluna[3];

                    $status = $cliente->save();

                    $cliente->status = $status;
                    $clientes[] = $cliente;
                    if (!empty($status)) {
                        $retorno = $this->parserEndereco($cliente, $coluna[4], $coluna[5]);

                        if (empty($retorno->status)) {
                            if ($retorno->erro === "OVER_QUERY_LIMIT") {
                                //faça tudo ou não faça nada.
                                $cliente->delete();
                                return response()->json(["status" => 0, "erro" => $retorno->erro]);
                            }
                            return response()->json(["status" => 0, "erro" => $retorno->erro]);
                        }
                    } else {
                        //faça tudo ou não faça nada.
                        $cliente->delete();
                    }
                } catch (\Exception $ex) {

                    if ($ex->getCode() == 22007) {
                        $cliente->status = 0;
                        $cliente->erro = "Formato de data inválido.";
                        //return response()->json(["status" => 0, "erro" => "Formato de Data inválido."]);
                    } else if ($ex->getCode() == 23000) {
                        $cliente->status = 0;
                        $cliente->erro = "Este CPF já está cadastrado no sistema.";
                        //return response()->json(["status" => 0, "erro" => "Este CPF já está cadastrado no sistema."]);
                    } else {
                        $cliente->status = 0;
                        $cliente->erro = $ex->getMessage();
                        //return response()->json(["status" => 0, "erro" => $ex->getMessage()]);

                    }
                    $clientes[] = $cliente;

                }
            }
        }

        return response()->json(["clientes" => $clientes]);

    }

    public function exportar(Request $request){
        $arquivo = "relatorio-clientes-" . date("d-m-Y") . ".csv";
        return (new ExportCSV())->download($arquivo);

    }public function exportarXLS(Request $request){
        $arquivo = "relatorio-clientes-" . date("d-m-Y") . ".xls";
        return (new ExportCSV())->download($arquivo);

    }

    function inverteData($data)
    {
        if (count(explode("/", $data)) > 1) {
            return implode("-", array_reverse(explode("/", $data)));
        } elseif (count(explode("-", $data)) > 1) {
            return implode("/", array_reverse(explode("-", $data)));
        }
    }
}
