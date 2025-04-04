<style>
    /* Ajusta el ancho mínimo en pantallas pequeñas */
    @media (max-width: 768px) {
        .col-md {
            min-width: 100%;
            /* Apila columnas en móviles */
        }
    }

    .day-tasks {
        overflow-y: auto;
        /* Scroll si hay muchas tareas */
        max-height: 70vh;
        /* Altura máxima */
    }
</style>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="card" style="margin: 50px">
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

        <br>
        <br><br>

        {{-- <div class="container">
                        <h2 class="text-center">
                            {{ \Carbon\Carbon::parse($semana_actual->inicio_semana)->translatedFormat('d-M-Y') }} -
                            {{ \Carbon\Carbon::parse($semana_actual->fin_semana)->translatedFormat('d-M-Y') }} </h2>

                        <div class="row">
                            @foreach ($weekDays as $day)
                                <div class="col-md-2">
                                    <div class="card mb-3">
                                        <div class="card-header bg-primary text-white">
                                            {{ $day['name'] }} ({{ $day['date'] }})
                                        </div>
                                        <div class="card-body">
                                            <h6>Área A</h6>
                                            <p><strong>Horas Disponibles:</strong> {{ $day['limit_A'] }} hrs</p>
                                            <p>
                                                <strong>Horas Ocupadas:</strong>
                                                <span class="{{ $day['exceeded_A'] ? 'text-danger fw-bold' : '' }}">
                                                    {{ $day['hours_A'] }} hrs
                                                </span>
                                            </p>

                                            <h6>Área B</h6>
                                            <p><strong>Horas Disponibles:</strong> {{ $day['limit_B'] }} hrs</p>
                                            <p>
                                                <strong>Horas Ocupadas:</strong>
                                                <span class="{{ $day['exceeded_B'] ? 'text-danger fw-bold' : '' }}">
                                                    {{ $day['hours_B'] }} hrs
                                                </span>
                                            </p>

                                            <h6>Tareas Asignadas:</h6>
                                            <ul class="list-group">
                                                @forelse ($day['tasks'] as $tarea)
                                                    <li class="list-group-item">
                                                        <strong>{{ $tarea->nombre }}</strong>
                                                        ({{ $tarea->estimated_hours }} hrs)
                                                        - Área {{ $tarea->area }}
                                                    </li>
                                                @empty
                                                    <li class="list-group-item text-muted">No hay tareas asignadas</li>
                                                @endforelse
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div> --}}


        <div class="container-fluid">
            <div class="row">
                <!-- Columnas para cada día -->
                <h5 class="text-center">
                    {{ \Carbon\Carbon::parse($semana_actual->inicio_semana)->translatedFormat('d-M-Y') }} -
                    {{ \Carbon\Carbon::parse($semana_actual->fin_semana)->translatedFormat('d-M-Y') }} </h5>

                @foreach ($weekDays as $day)
                    <div class="col-md p-2 border">
                        <h6 class="text-center">{{ $day['name'] }}<br>{{ $day['date'] }}</h6>
                        <hr>
                        <label for="">Técnicos en turno</label>
                        <button class="btn btn-primary"
                            onclick="guardartecdia('{{ $semana_actual->id }}','{{ $day['date'] }}','{{ $day['name'] }}')">Agregar
                            Tecnicos</button>
                        <table>
                            <thead>
                                <th>Nombre</th>
                                <th>Tipo</th>
                                <th>Horas</th>
                            </thead>
                            <tbody>
                                @foreach ($day['tecnicos'] as $tecnico)
                                    <tr>
                                        <td>{{ $tecnico['nombre'] }}</td>
                                        <td>{{ $tecnico['area'] }}</td>
                                        <td>{{ $tecnico['horas'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="day-tasks" style="min-height: 500px;">
                            @foreach ($day['tasks'] as $task)
                                <div class="card mb-2">
                                    <div class="card-body p-2">
                                        <small class="text-muted">{{ $task->hora_inicio }}</small>
                                        <p class="mb-0">{{ $task->molde_id }}</p>
                                        <span class="badge bg-primary">{{ $task->area }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="agregartecnicodia" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Técnico por día</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('disemtec.store') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="id_semana" name="id_semana">
                        <input type="text" id="nomdia" class="form-control-plaintext" name="nomdia" readonly
                            style="font-weight: bold">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1"><svg xmlns="http://www.w3.org/2000/svg"
                                    width="15" height="15" fill="currentColor" class="bi bi-calendar2"
                                    viewBox="0 0 16 16">
                                    <path
                                        d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M2 2a1 1 0 0 0-1 1v11a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1z" />
                                    <path
                                        d="M2.5 4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5H3a.5.5 0 0 1-.5-.5z" />
                                </svg></span>
                            <input type="text" class="form-control" id="dia" name="dia">
                        </div>
                        <label for="">Técnico:</label>
                        <select name="tecnico_id" id="tecnico_id" class="form-control">
                            <option value="">--Selecciona--</option>
                            @foreach ($tecnicos as $tec)
                                <option value="{{ $tec->id }}">{{ $tec->nombre }} (Tipo {{ $tec->area }})
                                </option>
                            @endforeach
                        </select>
                        <label for="">Horas:</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1"><svg xmlns="http://www.w3.org/2000/svg"
                                    width="16" height="16" fill="currentColor" class="bi bi-clock"
                                    viewBox="0 0 16 16">
                                    <path
                                        d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71z" />
                                    <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0" />
                                </svg></span>
                            <input type="number" class="form-control" id="horas" name="horas">
                        </div>
                        <label for="">Tipo:</label>
                        <select name="tipo" id="tipo" class="form-control">
                            <option value="">--Selecciona--</option>
                            <option value="A">Tipo A</option>
                            <option value="B">Tipo B</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
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

        function guardartecdia(semana_id, dia, nomdia) {
            $('#agregartecnicodia').modal('show');
            $('#dia').val(dia);
            $('#id_semana').val(semana_id);
            $('#nomdia').val(nomdia);


        }
    </script>









</x-app-layout>
