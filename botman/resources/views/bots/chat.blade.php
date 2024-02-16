<script>
    const currentUrl = window.location.href;
    const match = currentUrl.match(/chat\/(\d+)/);
    const welcomeMessage = "{{ $welcome }}";
    let chatServer = null;

    @if(isset($messages))
         chatServer = "/api/oldChat";
    @else
         chatServer = "/api/newChat";
    @endif

   /* fetch(chatServer, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            url: currentUrl
        }),
    })
        .then(response => response.json())
        .then(data => {
            alert('Richiesta inviata con successo! Risposta: ' + JSON.stringify(data));
        })
        .catch((error) => {
            alert('Si è verificato un errore durante l\'invio della richiesta: ' + error.message);
        }); */

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
