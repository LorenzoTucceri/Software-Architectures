<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">

    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <!-- DataTables JavaScript -->
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>

    <title>{{ config('app.name', 'Laravel') }}</title>


    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <style>

    </style>

    <style>

        @import url('https://fonts.googleapis.com/css?family=Ubuntu');

        html,body {
            background-color: #f0f0f0;
            font-family: 'Ubuntu', 'Helvetica Neue', Arial, sans-serif;
            font-size: 14px;
            margin: 0;
            padding: 0;
        }

        .menu {
            position: fixed;
            top: 0;
            left: 0;
            width: 200px;
            height: 100vh;
            background-color: #0047ab; /* Blu scuro per il menu */
            color: #fff; /* Colore del testo del menu */
            padding: 20px;
            box-sizing: border-box;
            z-index: 100;
        }

        .menu ul {
            list-style: none;
            padding: 0;
            margin: 0;
            max-height: 63%; /* Imposta l'altezza massima del menu delle chat */
        }

        .menu ul {
            overflow-y: auto; /* Aggiunge la barra di scorrimento verticale se necessario */
        }


        .menu li {
            margin-bottom: 10px;
        }

        .menu a {
            color: #fff; /* Colore del testo dei link del menu */
            text-decoration: none;
            display: block;
            padding: 5px 10px;
            border-radius: 3px;
            transition: background-color 0.3s ease;
        }

        .menu a:hover {
            background-color: #0066cc; /* Blu leggermente più chiaro al passaggio del mouse sui link del menu */
        }
        .separator {
            border-top: 1px solid #ccc;
            margin: 10px 0;
        }
        .menu h1 {
            font-size: 24px;
            margin-bottom: 10px;
            margin-top: 0; /* Aggiunto per spostare il titolo più in alto */
        }

        .popup-content {
            position: fixed;
            top: 40%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            width: 60%;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: #000;
            text-decoration: none;
        }

        .input-container {
            margin-bottom: 20px;
            display: flex;
            width: 40%;
        }

        .input-container input {
            flex: 1;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px 0 0 5px;
            width: 65%; /* Ridotto il campo input */
            margin-right: 5px; /* Aggiunto margine destro */
        }

        .input-container button, #microservicesTable button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #408591;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-left: 5px; /* Aggiunto margin-left */
        }

        .input-container button:hover, #microservicesTable button:hover {
            background-color: #00557e;
        }


        #microservicesTable {
            max-height: 300px; /* Altezza massima */
            overflow-y: auto; /* Scroll verticale */
        }


        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }
        .microservice-form {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .microservice-form input[type="text"] {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-right: 5px;
        }

        .microservice-form button {
            padding: 8px 20px;
            border: none;
            border-radius: 5px;
            background-color: #408591;
            color: #fff;
            cursor: pointer;
        }

        .microservice-form button:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }

        .notification {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            padding: 10px 20px;
            border-radius: 5px;
            color: #fff;
            z-index: 9999;
        }

        .notification.success {
            background-color: #28a745;
        }

        .notification.error {
            background-color: #dc3545;
        }




    </style>
</head>
<body>
<div class="menu">
    <h1>MiLA4U</h1>
    <div class="separator"></div>
    <ul>
        <li><a href="#" data-bs-toggle="modal" data-bs-target="#profileModal">Profile</a></li>
        @if(Auth()->user()->role=="Admin")
            <li><a href="#" data-bs-toggle="modal" data-bs-target="#myModal">
                    Microservices</a></li>
        @endif
    </ul>
    <div class="separator"></div>
    <h3>Chat</h3>
    <ul>
        <li><a href="{{route("welcome")}}">New chat</a></li>
        @foreach(\App\Models\Chat::where('user_id', Auth::user()->id)->get() as $chat)
            <li><a href="{{ route('chat.show', ['id' => $chat->id]) }}">{{$chat->name}}</a></li>
        @endforeach
    </ul>
    <div class="separator"></div>
    <ul>
        <li><a href="{{ route('logout') }}">Logout</a></li></ul>
    <div class="separator"></div>


</div>
<div class="modal" id="myModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Add Microservices</h4>

            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="alert alert-danger alert-dismissible fade show" id="addErrorAlert" role="alert" style="display: none;">
                    <strong>Error:</strong> Priority must be unique.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>

                <div class="alert alert-success alert-dismissible fade show" id="addSuccessAlert" role="alert" style="display: none;">
                    Microservice added successfully!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>

                <div class="alert alert-success alert-dismissible fade show" id="deleteSuccessAlert" role="alert" style="display: none;">
                    Microservice deleted successfully!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>



                <div class="input-container">
                    <form action="{{ route('addMicroservice') }}" method="POST" class="microservice-form">
                        @csrf
                        <td>
                            <input type="text" id="microserviceName" placeholder="Name" name="name">
                        </td>
                        <td>
                            <input type="text" id="priority" placeholder="Priority" name="priority">
                        </td>
                        <td>
                            <button type="submit" id="addBtn" disabled>Add</button>
                        </td>
                        <td>
                        </td>

                    </form>
                </div>
                <table id="microservicesTable">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Priority</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach(\App\Models\Microservice::all() as $microservice)
                        <tr>
                            <td>{{ $microservice->name }}</td>
                            <td>{{ $microservice->priority }}</td>
                            <form action="{{ route('microservices.delete', $microservice->id) }}" method="POST"
                                  id="deleteForm">
                                @csrf
                                <td><button type="submit" onclick="return confirmDelete()">Delete</button></td>
                            </form>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<div class="modal" id="profileModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-success alert-dismissible fade show" id="addSuccessUser" role="alert" style="display: none;">
                    User updated successfully!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <!-- Form per la modifica del profilo -->
                <form action="{{ route('updateProfile') }}" method="POST">
                    @csrf
                    <!-- Campo per il nome -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ Auth::user()->name }}">
                    </div>
                    <!-- Campo per l'surname -->
                    <div class="mb-3">
                        <label for="surname" class="form-label">Surname</label>
                        <input type="text" class="form-control" id="surname" name="surname" value="{{ Auth::user()->surname }}">
                    </div>
                    <!-- Campo per l'email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ Auth::user()->email }}">
                    </div>
                    <!-- Campo per la password -->
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password">
                        @error('password')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- Pulsante per salvare le modifiche -->
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>


</body>

<div class="content">
    @yield('content')
</div>


<script>

    function confirmDelete() {

        return confirm('Are you sure you want to delete this microservice?');

    }

    document.addEventListener('DOMContentLoaded', function() {
        const microserviceNameInput = document.getElementById('microserviceName');
        const priorityInput = document.getElementById('priority');
        const addBtn = document.getElementById('addBtn');

        function updateAddBtnState() {
            if (microserviceNameInput.value.trim() !== '' && priorityInput.value.trim() !== '') {
                addBtn.disabled = false;
            } else {
                addBtn.disabled = true;
            }
        }

        microserviceNameInput.addEventListener('input', updateAddBtnState);
        priorityInput.addEventListener('input', updateAddBtnState);
    });

    $(document).ready(function() {
        // Inizializza la tabella DataTables
        var table = $('#microservicesTable').DataTable({
            "pageLength": 5, // Imposta il numero massimo di righe per pagina
            "lengthMenu": [], // Rimuove completamente l'opzione "Show entries"
            "paging": true, // Abilita la paginazione
            "dom": "rtip", // Personalizza gli elementi visualizzati nella parte superiore della tabella
            "pagingType": "simple" // Utilizza un'interfaccia semplificata per la paginazione
        });

        // Impedisci la chiusura del modal quando si fa clic sui controlli di paginazione
        $('#myModal').on('click', '.paginate_button', function(e) {
            e.stopPropagation();
        });
    });


    $(document).ready(function() {
        // Mostra il pop-up se è stata aggiunta un microservizio
        @if(session('microservice_added'))
        $('#addSuccessAlert').fadeIn().delay(3000).fadeOut();
        $('#myModal').modal('show');
        @endif

        // Mostra il pop-up se è stato eliminato un microservizio
        @if(session('microservice_deleted'))
        $('#deleteSuccessAlert').fadeIn().delay(3000).fadeOut();
        $('#myModal').modal('show');
        @endif

        // Mostra il pop-up se c'è un errore di aggiunta microservizio
        @if(session('priority_error'))
        $('#addErrorAlert').fadeIn().delay(3000).fadeOut();
        $('#myModal').modal('show');
        @endif

        @if(session('user_updated'))
        $('#addSuccessUser').fadeIn().delay(3000).fadeOut();
        $('#profileModal').modal('show');
        @endif

        @error('password')
            $('#profileModal').modal('show');
        @enderror


    });



</script>
</html>