<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                @if (session('status'))
                    <div class="alert alert-success" id="success-alert">
                        {{ session('status') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="p-6 bg-white border-b border-gray-200">
                    Técnicos


                    <br>
                    <br>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#agregartec">
                        Agregar
                    </button>
                    <br>
                    <table class="table">
                        <thead>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Area</th>
                            <th>Estatus</th>
                            <th>Acciones</th>
                        </thead>
                        <tbody>
                            @php
                                $cont = 1;
                            @endphp
                            @foreach ($tecnicos as $tec)
                                <tr>
                                    <td>{{ $cont }}</td>
                                    <td>{{ $tec->nombre }}</td>
                                    <td>Tipo {{ $tec->area }}</td>
                                    <td>
                                        @if ($tec->estatus == 1)
                                            Activo
                                        @else
                                            Inactivo
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-primary"
                                            onclick="editartecnico('{{ $tec->id }}', '{{ $tec->nombre }}', '{{ $tec->area }}')">Editar</button>
                                        @if ($tec->estatus == 1)
                                            <button class="btn btn-warning" onclick="darbaja('{{ $tec->id }}')">Dar
                                                de
                                                baja</button>
                                        @else
                                            <button class="btn btn-warning"
                                                onclick="altatec('{{ $tec->id }}')">Dar
                                                de
                                                alta</button>
                                        @endif

                                    </td>
                                </tr>
                                @php
                                    $cont++;
                                @endphp
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="agregartec" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Agregar</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('tecnicos.store') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <label for="">Nombre:</label>
                        <input type="text" class="form-control" name="nombre" id="nombre">
                        <label for="">Tipo:</label>
                        <select name="area" id="area" class="form-control">
                            <option value="">--Selecciona--</option>
                            <option value="A">Tipo A</option>
                            <option value="B">Tipo B</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editartec" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Editar</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="form" method="post">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id_tecnico" id="id_tecnico">
                    <div class="modal-body">
                        <label for="">Nombre:</label>
                        <input type="text" class="form-control" name="nombrem" id="nombrem">
                        <label for="">Tipo:</label>
                        <select name="aream" id="aream" class="form-control">
                            <option value="">--Selecciona--</option>
                            <option value="A">Tipo A</option>
                            <option value="B">Tipo B</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="darbaja" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80"
                        style="color: red; margin: auto" fill="currentColor" class="bi bi-exclamation-circle"
                        viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                        <path
                            d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0M7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0z" />
                    </svg>
                </div>
                <form id="bajam" method="post">
                    @csrf
                    @method('PUT')
                    <div class="modal-body" style="text-align: center">
                        <input type="hidden" name="id_mo" id="id_mo">
                        <h4>¿Está seguro de dar de baja a esté Técnico?</h4>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Si, Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="daralta" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80"
                        style="color: red; margin: auto" fill="currentColor" class="bi bi-exclamation-circle"
                        viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                        <path
                            d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0M7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0z" />
                    </svg>
                </div>
                <form id="altam" method="post">
                    @csrf
                    @method('PUT')
                    <div class="modal-body" style="text-align: center">

                        <h4>¿Está seguro de dar de alta a esté Técnico?</h4>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Si, Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
        crossorigin="anonymous"></script>
    <script>
        $("#success-alert").fadeTo(2000, 500).slideUp(500, function() {
            $("#success-alert").alert('close');
        });

        function editartecnico(id_tec, nombre, area) {
            $('#editartec').modal('show');
            $('#form').attr('action', "{{ route('tecnicos.update', '') }}" + "/" + id_tec);
            $('#id_tecnico').val(id_tec);
            $('#nombrem').val(nombre);
            $('#aream').val(area);
        }

        function darbaja(id_tec) {
            $('#darbaja').modal('show');
            $('#bajam').attr('action', "{{ route('darbajatecnico', '') }}" + "/" + id_tec);

        }

        function altatec(id_tec) {
            $('#daralta').modal('show');
            $('#altam').attr('action', "{{ route('daraltatecnico', '') }}" + "/" + id_tec);
        }
    </script>


</x-app-layout>
