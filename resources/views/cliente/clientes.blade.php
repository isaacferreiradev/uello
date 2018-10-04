@extends("layout.layout")
@section("content")


    <div class="pricing-header px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
        <h1 class="display-4">Lista de Clientes</h1>
        <p class="lead"></p>
    </div>

    <div class="container">
        @empty($clientes[0])
            <h3>Não há dados para listar.</h3>
        @endempty
        @if(!empty($clientes[0]))
            <div class="row">
                <form action="/cliente/exportar/csv" method="post">
                    @csrf

                    <button class="btn btn-primary">Gerar CSV</button>
                    <div style="margin-top: 40px;"></div>
                </form>
                <form action="/cliente/exportar/xls" method="post">
                    @csrf
                    <button class="btn btn-success" style="margin-left: 20px;">Gerar XLS</button>
                </form>
            </div>
            <div class="row">
                @foreach($clientes as $cliente)
                    <div class="col-md-6 box-shadow">
                        <div align="center">
                            <h4>{{$cliente->nome}}</h4>
                        </div>
                        <p>E-mail: {{$cliente->email}}</p>
                        <p>CPF: {{$cliente->cpf}}</p>
                        <p>Data de Nascimento: {{date("d/m/Y",strtotime($cliente->data_nascimento))}}</p>
                        <p class="text-muted"><b>Endereço: </b> {{$cliente->endereco->logradouro}}
                            , {{$cliente->endereco->numero}}
                            @if(!empty($cliente->endereco->complemento))
                                , {{$cliente->endereco->complemento}}
                            @endif
                            - {{$cliente->endereco->bairro}} - {{$cliente->endereco->cidade}}
                            , {{$cliente->endereco->cep}}
                        </p>
                    </div>
                @endforeach
                @endif
                {{$clientes->links()}}
            </div>
    </div>
@endsection
