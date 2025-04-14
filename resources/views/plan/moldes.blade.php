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
                    Moldes


                    <br>
                    <br>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#agregarmol">
                        Agregar
                    </button>
                    <br>
                    <table class="table">
                        <thead>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Tipo Mantenmiento</th>
                            <th>Horas</th>
                            <th>Estatus</th>
                            <th>Acciones</th>
                        </thead>
                        <tbody>
                            @php
                                $cont = 1;
                            @endphp
                            @foreach ($moldes as $mol)
                                <tr>
                                    <td>{{ $cont }}</td>
                                    <td>{{ $mol->nombre }}</td>
                                    <td>
                                        @if ($mol->estatus == 1)
                                            Activo
                                        @else
                                            Inactivo
                                        @endif
                                    </td>
                                    <td>{{$mol->tipo_mantenimiento}}</td>
                                    <td>{{$mol->horas}}</td>
                                    <td>
                                        <button class="btn btn-primary"
                                            onclick="editarmolde('{{ $mol->id }}', '{{ $mol->nombre }}')">Editar</button>
                                        @if ($mol->estatus == 1)
                                            <button class="btn btn-warning" onclick="darbaja('{{ $mol->id }}')">Dar
                                                de
                                                baja</button>
                                        @else
                                            <button class="btn btn-warning" onclick="altamol('{{ $mol->id }}')">Dar
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
    <div class="modal fade" id="agregarmol" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Agregar</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('molde.store') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <label for="">Nombre:</label>
                        <input type="text" class="form-control" name="nombre" id="nombre">
                        <label for="">Tipo Mantenmiento:</label>
                        <select name="tipo" id="tipo" class="form-control">
                            <option value="B">Tipo B</option>
                        </select>
                        <label for="">Horas:</label>
                        <input type="text" class="form-control" id="horas" name="horas">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editarmold" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Editar</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="form" method="post">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id_molde" id="id_molde">
                    <div class="modal-body">
                        <label for="">Nombre:</label>
                        <input type="text" class="form-control" name="nombrem" id="nombrem">
                        <label for="">Tipo Mantenmiento:</label>
                        <select name="tipom" id="tipom" class="form-control">
                            <option value="B">Tipo B</option>
                        </select>
                        <label for="">Horas:</label>
                        <input type="text" class="form-control" id="horasm" name="horasm">
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
                        <h4>¿Está seguro de dar de baja este molde?</h4>
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

                        <h4>¿Está seguro de dar de alta este molde?</h4>
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

        function editarmolde(id_molde, nombre, tipo, horas) {
            $('#editarmold').modal('show');
            $('#form').attr('action', "{{ route('molde.update', '') }}" + "/" + id_molde);
            $('#id_molde').val(id_molde);
            $('#nombrem').val(nombre);
            $('#tipom').val(tipo);
            $('#horasm').val(horas);
        }

        function darbaja(id_molde) {
            $('#darbaja').modal('show');
            $('#bajam').attr('action', "{{ route('darbajamolde', '') }}" + "/" + id_molde);

        }

        function altamol(id_molde) {
            $('#daralta').modal('show');
            $('#altam').attr('action', "{{ route('daraltamolde', '') }}" + "/" + id_molde);
        }
    </script>


</x-app-layout>
