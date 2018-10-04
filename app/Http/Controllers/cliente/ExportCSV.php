<?php

namespace App\Http\Controllers\cliente;

use App\Cliente;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ExportCSV implements FromCollection, WithColumnFormatting,WithHeadings {
    use Exportable;

    /**
     * @return Collection
     */
    public function collection()
    {
        $clientes = Cliente::orderBy("clientes.id","DESC")
            ->join("enderecos","clientes.id","enderecos.cliente_id")
            ->select("clientes.nome","clientes.email","clientes.data_nascimento","clientes.cpf"
            ,"clientes.created_at","enderecos.logradouro","enderecos.numero","enderecos.complemento",
                "enderecos.bairro","enderecos.cep","enderecos.cidade","enderecos.longitude","enderecos.latitude")
            ->get();

        return $clientes;

    }

    public function headings(): array
    {
        return [
            'Nome',
            'E-mail',
            'Data de Nascimento',
            'CPF',
            'Gerado Em',
            'Logradouro',
            'Numero',
            'Complemento',
            'Bairro',
            'CEP',
            'Cidade',
            'Longitude',
            'Latitude',
        ];
    }

    /**
     * @return array
     */
    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'C' => NumberFormat::FORMAT_CURRENCY_EUR_SIMPLE,
        ];
    }
}
