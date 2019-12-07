@extends('layouts.app')

@section('content')

    <header>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>
        <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet">
        <script src="https://unpkg.com/filepond/dist/filepond.js"></script>
        <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet">
        <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
        <script src="https://unpkg.com/filepond-plugin-image-exif-orientation/dist/filepond-plugin-image-exif-orientation.min.js"></script>
        <script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
    </header>

    <div class="card" id="app">
        <div class="card-body">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('unidade.listar') }}">Unidade</a></li>
                    @if($unidade->id)
                        <li class="breadcrumb-item active" aria-current="page"><strong>Alterar</strong></li>
                    @else
                        <li class="breadcrumb-item active" aria-current="page"><strong>Cadastro</strong></li>
                    @endif
                </ol>
            </nav>
            <div class="row">
                <div class="col-12">
                    <div class="tab-content" id="nav-tabContent">
                        
                        <div class="tab-pane fade show active" id="nav-unidade" role="tabpanel" aria-labelledby="nav-home-tab">
                            @include('unidade.unidade')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if ($unidade->id)
    <div class="card">
        <div class="card-body">            
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <h5>Fotos</h5>
                    </div>
                </div>
            </div>
            <form method="POST" action="{{route('unidade.storeImage')}}" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">Adicionar</button>                            
                        </div>
                    </div>
                </div>
                @if(!empty($imagens))
                    <div class="row">
                        @foreach($imagens as $imagem)
                        <div class="col-md-3">
                            <div class="form-group">
                                <img src="{{ asset($imagem->arquivo) }}" alt="{{$imagem->legenda}}" width=200 height=170>
                                <div class="col-md-12" style="text-align: center; line-height: 45px;">
                                    <div class="form-group">
                                        <strong>
                                            <p>{{$imagem->legenda}}</p>                                            
                                        </strong>
                                    </div>
                                </div>
                            </div>                    
                        </div>
                        @endforeach
                    </div>
                @else
                   <h1>Não há imagens cadastradas!</h1>
                @endif
                <div class="area-modal">
                    <div class="modal fade" id="exampleModal" tabindex="-1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Foto</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body container">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <input type="file" id="foto" class="filepond" name="foto[]" multiple data-max-files="3" accept="image/*">
                                            <label for="legenda" id="legenda">Legenda</label>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="ativo_foto">Ativo</label>
                                            <select name="ativo_foto" id="ativo_foto" class="form-control">
                                                <option value="A">Ativo</option>
                                                <option value="I">Inativo</option>
                                            </select>
                                        </div>
                                    </div>
                                    <input type="hidden" name="idoperadora_unidade" value="{{$unidade->id}}">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                    <button type="submit" id="salvarModal" class="btn btn-primary" >Salvar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endif
<script>

    $(document).ready(function(){

        FilePond.registerPlugin(
            FilePondPluginImagePreview,
            FilePondPluginImageExifOrientation,
            FilePondPluginFileValidateSize
        );
    
        FilePond.setOptions({
            server: {
                url: "{{route('uploadUnidade')}}",
                process: {
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    onload: (response) => {
                        $('<input type="text" id="txtLegenda" name="legenda[]" class="form-control" maxlength="50" placeholder="Descrição da imagem">').insertAfter('#legenda');
                        return response;
                    }
                }
            }
        });

        const inputElement = document.querySelector('input[type="file"]');
        const pond = FilePond.create( inputElement);

    });

</script>

@endsection
