@extends("layout.layout")
@section("content")
    <div class="container">
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
    </div>
@endsection
@section("js")
    <script type="text/javascript">
        $(document).ready(function () {
            $("#csvForm").on('submit', function (e) {
                e.preventDefault();
                var formData = new FormData(this);

                var formurl = $("#csvForm").attr("action");
                $.ajax({
                    url: formurl,
                    data:formData,
                    type: 'POST',
                    contentType: false,
                    processData: false,
                    cache: false,
                    success: function(response) {
                        if(response.status == 0){
                            $("#div-erro").css("display", "block");
                            $("#div-erro").html(response.erro);
                        }
                    },
                    error: function (response) {
                        console.log(response);
                    }
                });

            });



        });
    </script>
@endsection
