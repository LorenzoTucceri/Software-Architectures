<script>
    const currentUrl = window.location.href;
    const match = currentUrl.match(/chat\/(\d+)/);
    const welcomeMessage = "{{ $welcome }}";
    let chatServer = null;
    let loadChat = null



    @if(isset($messages))
        loadChat = "/api/loadChat"
        chatServer = "/api/oldChat";
    @else
         chatServer = "/api/newChat";
    @endif

    const url = chatServer === "/api/newChat" ? "/newChat" : "/oldChat";

    $.ajax({
        url: '/getUserId',
        type: 'GET',
        success: function(response) {
            const userId = response.userId;
            console.log("ID dell'utente autenticato:", userId);

            let chatId = 0;
            if (match && match[1]) {
                chatId = parseInt(match[1]);
                console.log("ID chat:", chatId);
            }

            $.ajax({
                url: '/setSessionData',
                type: 'GET',
                data: {
                    userId: userId,
                    chatId: chatId
                },
                success: function(response) {
                    console.log("Dati della sessione impostati con successo");
                    // Puoi gestire ulteriormente i dati qui se necessario
                },
                error: function(xhr, status, error) {
                    console.error('Errore durante la richiesta AJAX:', error);
                }
            });
        },
        error: function(xhr, status, error) {
            console.error('Errore durante la richiesta AJAX:', error);
        }
    });
    @if(isset($messages))
    $.ajax({
        url: loadChat, // Utilizza la variabile loadChat che hai definito in precedenza
        type: 'GET',
        success: function(response) {
            console.log("Chat caricata con successo:", response);
            // Puoi gestire ulteriormente la chat caricata qui se necessario
        },
        error: function(xhr, status, error) {
            console.error('Errore durante la richiesta AJAX per caricare la chat:', error);
        }
    });
    @endif




    // Create a new observer instance:
    const observer = new MutationObserver(function() {
        if (document.getElementById('botmanChatRoot')) {
            // You must wait until the react component is inserted on the body!
            window.BotmanInstance.chatServer = chatServer;
            window.chatInstance.sayAsBot(welcomeMessage);
            @if(isset($messages))
            const messages = @json($messages);
                messages.forEach(message => {
                    window.chatInstance.say(message.question);
                    window.chatInstance.sayAsBot(message.answer);
                });

            @endif
            disconectObserver();
        }
    });




    // Set configuration object:
    const config = { childList: true };

    // Start the observer
    observer.observe(document.body, config);

    function disconectObserver() {
        observer.disconnect();
    }

</script>
@vite(['resources/js/botman/fullscreen.js', 'resources/css/chat.css'])
