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

        <div class="container-fluid">
            <div class="row">
                <!-- Columnas para cada día -->
                <h5 class="text-center">
                    {{ \Carbon\Carbon::parse($semana_actual->inicio_semana)->translatedFormat('d-M-Y') }} -
                    {{ \Carbon\Carbon::parse($semana_actual->fin_semana)->translatedFormat('d-M-Y') }} </h5>

                @foreach ($weekDays as $day)
                    <div class="col-md p-2 border">
                        <h6 class="text-center">{{ ucfirst($day['name']) }}<br>{{ $day['date'] }}</h6>
                        <button class="btn btn-primary"
                            onclick="guardartecdia('{{ $semana_actual->id }}','{{ $day['date'] }}','{{ $day['name'] }}')">Agregar
                            Tecnicos</button>
                        <!-- Indicadores de horas -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header bg-primary text-white">Área A</div>
                                    <div class="card-body">
                                        <p>Asignadas: {{ $day['horas_asignadas']['A'] }}h</p>
                                        <p>Utilizadas: {{ $day['horas_utilizadas']['A'] }}h</p>
                                        <p
                                            class="{{ $day['horas_disponibles']['A'] < 0 ? 'text-danger' : 'text-success' }}">
                                            Disponibles: {{ $day['horas_disponibles']['A'] }}h
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header bg-info text-white">Área B</div>
                                    <div class="card-body">
                                        <p>Asignadas: {{ $day['horas_asignadas']['B'] }}h</p>
                                        <p>Utilizadas: {{ $day['horas_utilizadas']['B'] }}h</p>
                                        <p
                                            class="{{ $day['horas_disponibles']['B'] < 0 ? 'text-danger' : 'text-success' }}">
                                            Disponibles: {{ $day['horas_disponibles']['B'] }}h
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Formulario para agregar tarea -->
                        <button class="btn btn-primary mb-3" onclick="agregartask('{{ $day['date'] }}')">
                            Agregar Tarea
                        </button>

                        <!-- Lista de tareas ordenadas por prioridad -->
                        <div class="task-list">
                            @foreach ($day['tasks'] as $task)
                                <div class="card mb-2 priority-{{ $task->priority }}">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <span class="badge bg-secondary">#{{ $task->priority }}</span>
                                                <strong>{{ $task->molde_id }}</strong>
                                            </div>
                                            <div>
                                                <span class="badge bg-{{ $task->area == 'A' ? 'primary' : 'info' }}">
                                                    Área {{ $task->area }}
                                                </span>
                                                <span class="text-muted">{{ $task->estimated_hours }}h</span>
                                            </div>
                                        </div>
                                        <div class="mt-2">
                                            <small>{{ $task->hora_inicio }} - {{ $task->hora_fin }}</small>
                                            <p class="mb-0">{{ $task->notes }}</p>
                                        </div>
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

    <!-- Modal -->
    <div class="modal fade" id="taskModal" tabindex="-1" role="dialog" aria-labelledby="taskModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form id="taskForm" method="POST" action="{{ route('task.store') }}">
                    @csrf
                    <input type="hidden" name="work_week_id" value="{{ $semana_actual->id ?? '' }}">

                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="taskModalLabel">Nueva Tarea</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="title" class="font-weight-bold">Molde*:</label>
                                    <select name="molde_id" id="molde_id" class="form-control">
                                        <option value="">--Selecciona--</option>
                                        @foreach ($moldes as $molde)
                                            <option value="{{ $molde->id }}"
                                                data-tipo="{{ $molde->tipo_mantenimiento }}"
                                                data-horas="{{ $molde->horas }}">
                                                {{ $molde->nombre }}
                                            </option>
                                        @endforeach

                                    </select>

                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="task_date" class="font-weight-bold">Fecha *</label>
                                    <input type="date" class="form-control" id="task_date" name="task_date"
                                        required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="estimated_hours" class="font-weight-bold">Horas estimadas
                                        *</label>
                                    <input type="number" step="0.5" class="form-control" id="estimated_hours"
                                        name="estimated_hours" min="0.5" max="24" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="start_time" class="font-weight-bold">Hora de inicio *</label>
                                    <input type="time" class="form-control" id="start_time" name="start_time"
                                        required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="end_time" class="font-weight-bold">Hora de fin *</label>
                                    <input type="time" class="form-control" id="end_time" name="end_time"
                                        required>
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="area" class="font-weight-bold">Tipo Mantenimiento *</label>
                                    <select class="form-control" id="area_tipo" name="area_tipo" required>
                                        <option value="">--Selecciona--</option>
                                        <option value="A">Tipo A
                                        </option>
                                        <option value="B">Tipo B
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="priority" class="font-weight-bold">Prioridad *</label>
                                    <select class="form-control" id="priority" name="priority" required>
                                        <option value="1">🔥 Prioridad 1 (Urgente)</option>
                                        <option value="2">🔴 Prioridad 2 (Alta)</option>
                                        <option value="3" selected>🟡 Prioridad 3 (Media)</option>
                                        <option value="4">🔵 Prioridad 4 (Baja)</option>
                                        <option value="5">⚪ Prioridad 5 (Mínima)</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="notes" class="font-weight-bold">Notas adicionales</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3"
                                placeholder="Detalles importantes, enlaces, observaciones..."></textarea>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Guardar Tarea
                        </button>
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

        function agregartask(fecha) {
            $('#taskModal').modal('show');
            $('#task_date').val(fecha);
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const moldeSelect = document.getElementById('molde_id');
            const tipoSelect = document.getElementById('area_tipo');
            const estimatedHoursInput = document.getElementById('estimated_hours');
            const startTimeInput = document.getElementById('start_time');
            const endTimeInput = document.getElementById('end_time');



            moldeSelect.addEventListener('change', function() {
                const selected = this.options[this.selectedIndex];
                const tipo = selected.getAttribute('data-tipo');
                const horas = parseFloat(selected.getAttribute('data-horas')) || 0;

                console.log('Molde seleccionado:', selected.text);
                console.log('Tipo:', tipo);
                console.log('Horas estimadas:', horas);
                console.log(document.getElementById('area_tipo').value);

                tipoSelect.value = tipo;


                if (tipo === 'B') {
                    estimatedHoursInput.value = horas;
                    estimatedHoursInput.readOnly = true;
                    calculateEndTime(); // calcula al asignar fijo
                } else if (tipo === 'A') {
                    estimatedHoursInput.value = '';
                    estimatedHoursInput.readOnly = false;
                    endTimeInput.value = ''; // limpia fin hasta que escriba horas
                } else {
                    // Por si viene vacío o nulo
                    estimatedHoursInput.value = '';
                    estimatedHoursInput.readOnly = false;
                    endTimeInput.value = '';
                }
            });
            tipoSelect.addEventListener('change', function() {
                if (tipoSelect.value === 'A') {
                    console.log("aqui entro");

                    estimatedHoursInput.value = '';
                    estimatedHoursInput.readOnly = false;
                    endTimeInput.value = ''; // limpia fin hasta que escriba horas
                }
            });




            // Calcular hora fin cuando cambia inicio u horas
            startTimeInput.addEventListener('change', calculateEndTime);
            estimatedHoursInput.addEventListener('input', function() {
                if (tipoSelect.value === 'A') {
                    calculateEndTime();
                }
            });

            function calculateEndTime() {
                const start = startTimeInput.value;
                const estimated = parseFloat(estimatedHoursInput.value);

                if (!start || isNaN(estimated)) {
                    endTimeInput.value = '';
                    return;
                }

                const [hours, minutes] = start.split(':').map(Number);
                const totalMinutes = hours * 60 + minutes + estimated * 60;
                const endHours = Math.floor(totalMinutes / 60) % 24;
                const endMinutes = Math.round(totalMinutes % 60);

                const formatted =
                    `${endHours.toString().padStart(2, '0')}:${endMinutes.toString().padStart(2, '0')}`;
                endTimeInput.value = formatted;
            }
        });
    </script>






</x-app-layout>
