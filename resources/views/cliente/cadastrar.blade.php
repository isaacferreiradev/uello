@extends("layout.layout")
@section("content")
    <div class="container content">
        <div class="card text-center">
            <div class="card-header">
                Featured
            </div>
            <div class="card-body">
                <h5 class="card-title">Cadastrar Cliente - CSV</h5>

                <form id="csvForm" action="/cadastrar/cliente/csv" method="POST" enctype="multipart/form-data">
                    @csrf
                    <label>Insira o arquivo CSV</label>
                    <input type="file" name="arquivo">

                    <div class="alert alert-danger" id="div-erro" style="display: none;">

                    </div>
                    <div class="alert alert-success" id="div-ok" style="display: none;">

                    </div>
                    <button class="btn btn-success" type="submit" id="csvButton">Cadastrar</button>
                </form>
            </div>

            <div class="card-footer text-muted">
                Uello Teste
            </div>
        </div>

        <div class="col-md-12" style="margin-top: 60px;">
            <div class="row" id="clientes-cadastrados">

            </div>
        </div>
    </div>
    <div id="loader" style="text-align: center; display: none;">
        <img src="{{asset('/imagem/loader.gif')}}"/>
    </div>
@endsection
@section("js")
    <script type="text/javascript">

        $(document).ready(function () {
            $("#csvForm").on('submit', function (e) {
                $("#loader").css("display", "block");
                $("#csvButton").prop("disabled",true);
                e.preventDefault();
                var formData = new FormData(this);

                var formurl = $("#csvForm").attr("action");
                $.ajax({
                    url: formurl,
                    data: formData,
                    type: 'POST',
                    contentType: false,
                    processData: false,
                    cache: false,
                    success: function (response) {
                        if (response.status == 0) {
                            $("#div-erro").css("display", "block");
                            $("#div-erro").html(response.erro);
                        } else {
                            $.each(response.clientes, function (key, item) {
                                console.log("Key: " + key);
                                console.log(item);
                                adicionarClientesCadastrados(item);

                            });
                        }
                        $("#csvButton").prop("disabled",false);
                        $("#loader").css("display", "none");
                    },
                    error: function (response) {
                        console.log(response);
                        $("#csvButton").prop("disabled",false);
                        $("#loader").css("display", "none");
                    }

                });

            });


        });

        var adicionarClientesCadastrados = function (cliente) {
            console.log("PASSO " + cliente.nome);
            var clienteHTML = "<div class='col-md-6 box-shadow'>" +
                "<p>Nome: " + cliente.nome + "</p>" +
                "<p>E-mail: " + cliente.email + "</p>" +
                "<p>CPF: " + cliente.cpf + "</p>";

            var status = cliente.status;
            if (status === 0) {
                var erro = "<p class='text-danger'>Não foi possível cadastrar este cliente.</p>Erro: " + "<p>" + cliente.erro + "</p>";
                clienteHTML += erro + "</div>";

                $("#clientes-cadastrados").append(clienteHTML);
            } else {
                var ok = "<p class='text-success'>Cliente cadastrado com sucesso.</p></div>";
                clienteHTML += ok;
                $("#clientes-cadastrados").append(clienteHTML);

            }
        }


    </script>
@endsection
