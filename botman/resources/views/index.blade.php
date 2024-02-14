<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Styles -->
        <style>

        </style>

        <style>
            body {
                font-family: 'Nunito', sans-serif;
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
                background-color: #fff;
                margin: 5% auto; /* Ridotto il margine superiore */
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



        </style>
    </head>
    <body>
    <div class="menu">
        <h1>MiLA4U</h1>
        @if(Auth()->user()->role=="Admin")
        <div class="separator"></div>
        <ul>
            <li><a href="#" onclick="openPopup()">Add microservice</a></li>
        </ul>
        @endif
        <div class="separator"></div>
        <h2>Chat</h2>
        <ul>
            @foreach(\App\Models\Chat::all() as $chat)
                <li><a href="chat?{{$chat->id}}">{{$chat->name}}</a></li>
            @endforeach
        </ul>
        <div class="separator"></div>
        <ul>
            <li><a href="{{ route('logout') }}">Logout</a></li></ul>
        <div class="separator"></div>


    </div>
    <div id="popup" class="popup" style="display: none">
        <div class="popup-content">
            <span class="close" onclick="closePopup()">&times;</span>
            <h2>Add Microservices</h2>
            <div class="input-container">
                <form action="{{ route('addMicroservice') }}" method="POST" class="microservice-form">
                    @csrf
                    <td>
                        <input type="text" id="microserviceName" placeholder="Name" name="name">
                    </td>
                    <td>
                        <input type="text" id="annotation" placeholder="Annotation" name="annotation">
                    </td>
                    <td>
                        <button type="submit" id="addBtn" disabled>Add</button>
                    </td>
                </form>

            </div>
            <table id="microservicesTable">
                <thead>
                <tr>
                    <th>Microservice Name</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach(\App\Models\Microservice::all() as $microservice)
                    <tr>
                        <td>{{ $microservice->name }}</td>
                        <form action="{{ route('microservices.delete', $microservice->id) }}" method="POST" id="deleteForm">
                            @csrf
                            <td><button type="submit" onclick="return confirmDelete()">Delete</button></td>
                        </form>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    </body>

    <body class="antialiased">
        @include('bots.chat')


    </body>

<script>

        function confirmDelete() {

            return confirm('Are you sure you want to delete this microservice?');

            }
    // Funzione per aprire il pop-up
    function openPopup() {
        document.getElementById('popup').style.display = 'block';
        isPopupOpen = true;
    }

    // Funzione per chiudere il pop-up
    function closePopup() {
        document.getElementById('popup').style.display = 'none';
        var isPopupOpen = false;

    }
    document.addEventListener('DOMContentLoaded', function() {
        const microserviceNameInput = document.getElementById('microserviceName');
        const annotationInput = document.getElementById('annotation');
        const addBtn = document.getElementById('addBtn');

        function updateAddBtnState() {
            if (microserviceNameInput.value.trim() !== '' && annotationInput.value.trim() !== '') {
                addBtn.disabled = false;
            } else {
                addBtn.disabled = true;
            }
        }

        microserviceNameInput.addEventListener('input', updateAddBtnState);
        annotationInput.addEventListener('input', updateAddBtnState);
    });



</script>
</html>